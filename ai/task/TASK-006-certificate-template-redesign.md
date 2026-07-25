# TASK-006 — Certificate Template Redesign and Signature Snapshot

## Status

`IN_PROGRESS`

Status yang tersedia:
- `DISCUSSION`
- `AUDITING`
- `READY`
- `IN_PROGRESS`
- `TESTING`
- `COMPLETED`
- `BLOCKED`
- `CANCELLED`

---

## Phased Implementation Progress

- **Phase A — Background and Base Certificate Layout**: `COMPLETED & TESTED` (Revision 3 Included + Signature Content Center Tweak)
- **Phase B — Signature & Custom Template Fields**: `REMAINING`
- **Phase C — Signature Immutability Snapshot**: `REMAINING`
- **Phase D — Visual Regression & Full E2E Testing**: `REMAINING`

---

## Phase A Revision 3 Execution & Testing Report

1. **Root Cause Analysis & Alignment**:
   - **Main Content Block Preserved**: Posisi main content Revision 2 (`top: 50mm` di PDF; `top: 24%` di Web Preview) dipertahankan 100% tanpa digeser.
   - **QR Verification Corner & Lift**: Blok QR dipindahkan dari `left: 66mm` ke area sudut kiri bawah (`left: 18mm`) di bawah ujung ribbon dan dinaikkan **9.5 mm** (`bottom: 25mm` di PDF; `left: 6%; bottom: 12%;` di Web Preview).
   - **Label QR Simplified**: Teks `Verify Certificate` dihapus dan diganti dengan nomor sertifikat dinamis (`{{ $certificate->certificate_number }}`). Teks `SCAN TO VERIFY` tetap bold & uppercase.
   - **Signature Block Content-Center Alignment**: Seluruh signature block digeser ke `left: 55%` (PDF) dan `left-[55%]` (Student & Admin Web Preview) agar posisinya center terhadap area konten utama (`left: 55mm` s/d `right: 25mm`), bukan center seluruh halaman A4. Seluruh elemen signature (date, image, name, title) bergerak bersamaan.
   - **Date Ordinal Superscript Preserved**: Format ordinal date superscript (`1ˢᵗ`, `2ⁿᵈ`, `3ʳᵈ`, `4ᵗʰ`, `22ⁿᵈ`) pada birth date, completion date, dan signing date dipertahankan 100%.

2. **Perubahan File**:
   - [resources/views/pdf/certificate.blade.php](file:///d:/Kerja%20File/Freelance/E-Course%20Queens/Coding/Real/queens-english-prestige/resources/views/pdf/certificate.blade.php) (QR di `left: 18mm; bottom: 25mm;`, label nomor sertifikat, & signature di `left: 55%; bottom: 25mm;`)
   - [resources/views/pages/student/certificate-show.blade.php](file:///d:/Kerja%20File/Freelance/E-Course%20Queens/Coding/Real/queens-english-prestige/resources/views/pages/student/certificate-show.blade.php) (Pembaruan student preview dengan signature `left-[55%]`)
   - [resources/views/pages/admin/course-management/certificates/show.blade.php](file:///d:/Kerja%20File/Freelance/E-Course%20Queens/Coding/Real/queens-english-prestige/resources/views/pages/admin/course-management/certificates/show.blade.php) (Pembaruan admin preview dengan signature `left-[55%]`)

3. **Posisi Final QR Verification Block**:
   - **PDF Output**: `left: 18mm; bottom: 25mm; width: 38mm; text-align: center;` (Image: `21mm x 21mm`)
   - **Web Preview**: `left: 6%; bottom: 12%; width: 13%; text-align: center;`
   - **Labels**: `SCAN TO VERIFY` & `{{ $certificate->certificate_number }}`
   - **Hasil Scan & Keamanan**: QR code 100% dapat dipindai menuju route verifikasi publik. Tidak overlap dengan ribbon, border, validity note, maupun signature.

4. **Posisi Final Signature Block**:
   - **PDF Output**: `left: 55%; bottom: 25mm; width: 76mm; margin-left: -38mm; text-align: center;`
   - **Web Preview**: `left: 55%; bottom: 12%; width: 26%; transform: translateX(-50%); text-align: center;`
   - **Hasil**: Tersusun simetris di tengah area konten utama, berada pada satu pita horisontal yang seimbang dengan QR block.

5. **Hasil Testing (Student Preview, Admin Preview & Regenerated PDF)**:
   - **Student & Admin Preview**: 100% selaras dan proporsional dengan PDF.
   - **Regenerated PDF**: Main content rapi, QR di sudut kiri bawah, signature center terhadap area konten utama (`left: 55%`), jarak validity note ke area bawah bersih tanpa ruang kosong besar.
   - **1–5 Sections & >5 Sections**: Single page & Page 2 breakdown bekerja sempurna.
   - **Signature Image Null & Present**: Merender rapi tanpa error.

6. **Hasil Technical Verification**:
   - **Automated Visual Regression Suite**: **`16 / 16 PASSED (100% Lolos)`**.
   - **Date Ordinal & PDF Render Test**: **`12 / 12 PASSED (100% Lolos)`**.
   - **Cache Commands**: `php artisan view:clear` & `php artisan optimize:clear` 100% sukses.
   - **Git Check**: `git diff --check` 100% bersih (0 formatting/whitespace error).

7. **Konfirmasi Constraints**:
   - **Main Content**: 100% TIDAK digeser dari Revision 2 (`top: 50mm`).
   - **Database & Migration**: 0 Migration baru, 0 DB schema change.
   - **Service & TASK-005**: `CertificateService` & logic completion/TASK-005 100% utuh.
   - **Status Akhir TASK-006**: **`IN_PROGRESS`** (Phase A: `COMPLETED & TESTED — Revision 3 Included`).
