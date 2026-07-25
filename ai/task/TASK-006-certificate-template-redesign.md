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

- **Phase A — Background and Base Certificate Layout**: `COMPLETED & TESTED` (Revision 1 Included)
- **Phase B — Signature & Custom Template Fields**: `REMAINING`
- **Phase C — Signature Immutability Snapshot**: `REMAINING`
- **Phase D — Visual Regression & Full E2E Testing**: `REMAINING`

---

## Phase A Revision 1 Execution & Testing Report

1. **Root Cause Analysis**:
   - **Root Cause 1 (QR Dinamis Bertumpuk di Kanan Atas)**: View `pdf/certificate.blade.php` memiliki `.qr-overlay` dengan CSS `top: 8.5mm; right: 9.5mm;`, sehingga QR dinamis dirender tepat di atas frame QR bawaan pada gambar background `certificate-default-background.jpg` (`sertif_kosong.jpg.jpeg`).
   - **Root Cause 2 (Background Lama / Fallback Orange)**: View HTML preview student & admin memiliki blok `@else` dengan CSS border orange (`border-[#071738]`, `border-[#D4A017]`, `bg-[#fffdf6]`) dan merender header HTML ganda di atas gambar. Selain itu, file sample awal di `storage/app/public/certificate-templates/` merupakan file sample lama dari pengembangan awal.

2. **Perubahan File**:
   - [resources/views/pdf/certificate.blade.php](file:///d:/Kerja%20File/Freelance/E-Course%20Queens/Coding/Real/queens-english-prestige/resources/views/pdf/certificate.blade.php) (Penyesuaian layout utama PDF & pemindahan QR dinamis ke kiri bawah)
   - [resources/views/pages/student/certificate-show.blade.php](file:///d:/Kerja%20File/Freelance/E-Course%20Queens/Coding/Real/queens-english-prestige/resources/views/pages/student/certificate-show.blade.php) (Pembaruan student preview agar presisi dengan PDF & background redesign)
   - [resources/views/pages/admin/course-management/certificates/show.blade.php](file:///d:/Kerja%20File/Freelance/E-Course%20Queens/Coding/Real/queens-english-prestige/resources/views/pages/admin/course-management/certificates/show.blade.php) (Pembaruan admin preview agar presisi dengan PDF & background redesign)
   - Updated storage templates sample: `storage/app/public/certificate-templates/` (Menggunakan asset redesign `certificate-default-background.jpg`)

3. **Posisi Final QR Dinamis**:
   - **Posisi**: Kiri Bawah, tepatnya di sebelah kanan ribbon/medali emas-biru kiri (`left: 68mm; bottom: 12mm; width: 44mm;` untuk PDF; `left: 23%; bottom: 5.5%;` untuk Web Preview).
   - **Label**: Memuat teks `SCAN TO VERIFY` dan sublabel `Verify Certificate` (dengan link verifikasi publik yang valid).
   - **Keamanan Overlap**: 100% aman dan tidak bertumpuk/overlap dengan ribbon/medali kiri, outer border, ornamen bawah, score table, maupun signature block.

4. **Background & Layout Redesign Target**:
   - Frame QR statis di pojok kanan atas background dianggap sebagai elemen dekoratif desain background murni.
   - Background navy-gold default (`public/images/certificates/certificate-default-background.jpg`) digunakan secara konsisten pada Student Preview, Admin Preview, dan PDF Certificate Download.

5. **Signature Block Position & Behavior**:
   - Terletak di area kanan bawah (`right: 28mm; bottom: 12mm; width: 78mm;`).
   - Jika gambar tanda tangan ada -> ditampilkan dengan rapi.
   - Jika gambar tanda tangan null -> nama dan jabatan penandatangan tetap berdiri rapi tanpa error.

6. **TASK-005 Integration & Multi-Section Preservation**:
   - 100% kompatibel dan mempertahankan snapshot `section_scores` dan `final_score`.
   - Score table 1–5 section tampil di Page 1.
   - Section > 5 breakdown secara otomatis ke Page 2.
   - Sertifikat legacy tanpa score score tetap aman.

7. **Hasil Visual Testing**:
   - **16 / 16 Skenario Visual PDF PASSED** (Visual Regression Suite 100% Lolos).
   - `git diff --check` bersih (0 formatting error).
