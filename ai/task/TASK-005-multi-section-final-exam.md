# TASK-005 — Multi-Section Final Exam and Certificate Scores

## Status

`TESTING`

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

## User Request

User ingin mengubah Final Exam pada course dari yang saat ini hanya 1 exam per course menjadi **Multi-Section Final Exam** (beberapa bagian/section yang dapat dikonfigurasi dinamis per course oleh Admin).

Contoh skenario:
- **Course TOEFL**:
  1. Listening Comprehension
  2. Structure and Written Expression
  3. Reading Comprehension
- **Course Kids**:
  1. Speaking & Listening
  2. Vocabulary & Grammar

Setiap section memiliki:
- Nama section (`title`);
- Urutan (`sort_order`);
- Kumpulan soal (`questions`);
- Attempt student (`attempts`);
- Nilai student (`total_score`);
- Status pengerjaan & kelulusan (`status`, `passing_grade`).

Nilai per section serta Final Score gabungan nantinya direncanakan untuk ditampilkan pada Sertifikat (Certificate).

---

## Objective

1. Melakukan audit komprehensif (read-only) terhadap seluruh flow Final Exam, Scoring, Progress/Completion, dan Certificate yang ada di codebase saat ini.
2. Mengidentifikasi root cause mengapa Final Exam saat ini terbatas 1 exam per course dan mengapa nilai exam belum masuk ke sertifikat.
3. Mengidentifikasi seluruh call-site `CertificateService`, flow auto grading, dan manual grading.
4. Melakukan audit database constraint, generator security, & transaction safety untuk mencegah *race condition*, *duplicate certificate*, dan *inconsistent completion state*.
5. Mengimplementasikan dan menguji secara penuh **Phase 1 (Database and Models)**, **Phase 2 (Transactional Central Completion)**, **Phase 3 (Auto and Manual Grading Integration)**, **Phase 4 (Admin Multi-Section Management)**, **Phase 5 (Student Multi-Section Final Exam UI)**, dan **Phase 6 (Certificate PDF Score Layout)**.
6. Menjalankan pengujian perilaku komprehensif (behavioral, visual, & regression testing) untuk memastikan kelayakan Phase 1–6 sebelum melanjutkan ke Phase 7.

---

## Deployment Warning — Pre-Release Notice (Phase 1 to 6 Verified)

> [!IMPORTANT]
> **PRE-RELEASE NOTICE (Phase 1–6 Verified)**:
> Implementation Phase 1 hingga Phase 6 telah selesai dan teruji secara penuh di environment lokal.
> Seluruh flow pembuatan section, student learning path, penyelesaian terpusat (centralized completion), snapshot sertifikat, dan tampilan PDF score breakdown telah bekerja dengan sempurna.
> **Pengujian akhir (Phase 7: Final Regression & True Parallel Concurrency Test)** adalah tahap terakhir yang harus dilakukan sebelum status task diubah menjadi `COMPLETED` dan aman di-deploy ke production.
> Status task dipertahankan pada **`TESTING`**.

---

## Implementation Result — Phase 6 (Certificate PDF Score Layout)

### 1. File Diubah (Phase 6)
- **Views**:
  - [resources/views/pdf/certificate.blade.php](file:///d:/Kerja%20File/Freelance/E-Course%20Queens/Coding/Real/queens-english-prestige/resources/views/pdf/certificate.blade.php):
    - Audit Call-site: Merupakan satu-satunya template PDF yang digunakan oleh `Student\CertificateController` dan `Admin\CourseManagement\CertificateController` via DomPDF engine (`a4`, `landscape`).
    - **Single Source of Truth**: Membaca nilai **HANYA** dari snapshot `$certificate->section_scores` (array) dan `$certificate->final_score` (decimal). Tidak pernah membaca ulang live data `FinalExam` atau `FinalExamAttempt`.
    - **Legacy Fallback**: Jika `$certificate->section_scores` bernilai `null` / kosong, template 100% merender layout sertifikat legacy tanpa tabel nilai dan tanpa `Final Score`.
    - **Score Table 1–5 Sections (Page 1)**: Merender tabel ringkas nilai per section dengan urutan snapshot, nama section ter-escape aman (`{{ }}`), format nilai `number_format(score, 2, '.', '')`, dan Final Score pada Page 1.
    - **Multi-Page Overflow Strategy (> 5 Sections)**: Jika section $> 5$, Page 1 merender sertifikat utama dengan badge `Final Score` pada Meta Box, dan Page 2 merender "Final Exam Score Breakdown" (`page-break-before: always;`) yang berisi perincian lengkap seluruh section tanpa ada yang terpotong.
    - **Long Title Wrapping**: Menggunakan `table-layout: fixed;` dan `word-wrap: break-word;` agar judul section yang sangat panjang memotong baris secara alami tanpa merusak border/layout PDF.
    - **Authorization & State Safety**: Rendering PDF bekerja secara aman untuk sertifikat berstatus `locked` (preview) maupun `issued` (download resmi) tanpa pernah merubah status sertifikat.

---

## Behavioral & Visual Testing Result — Phase 6

### Test Environment
- **OS**: Windows (x64)
- **PDF Engine**: Barryvdh DomPDF (`A4 Landscape`)
- **Isolation Strategy**: `DB::beginTransaction()` & `DB::rollBack()` + Automatic temporary PDF file cleanup (`Storage::disk('public')->delete(...)`).

### Test Suite Summary (Phase 6)
- **Total Scenarios**: 9 Scenarios
- **Passed**: 9 Scenarios (100% Pass Rate)
- **Failed**: 0 Scenarios

### Detailed Test Scenario Results (Phase 6)
1. **Scenario A (Legacy Certificate null section_scores)**: `[PASS]` — Sertifikat lama tanpa snapshot merender layout legacy tanpa tabel nilai, 0 Blade error.
2. **Scenario B (Single Section Certificate)**: `[PASS]` — Sertifikat 1 section merender 1 baris nilai (`85.50`) dan Final Score (`85.50`) pada Page 1.
3. **Scenario C (Three Sections Certificate)**: `[PASS]` — Sertifikat 3 section merender seluruh section (`Listening 85.00`, `Structure 80.00`, `Reading 90.00`) dan Final Score (`85.00`) pada Page 1.
4. **Scenario D (Five Sections Certificate Compact Page 1)**: `[PASS]` — 5 section ter-render rapi pada Page 1 tanpa overlap teks atau pemotongan layout.
5. **Scenario E (More Than 5 Sections Multi-Page Breakdown)**: `[PASS]` — 8 section merender Page 2 secara otomatis (`page-break-before: always;`) dengan judul breakdown dan seluruh 8 section lengkap.
6. **Scenario F (Long Section Title Handling)**: `[PASS]` — Section title yang sangat panjang di-wrap dengan aman tanpa horizontal overflow.
7. **Scenario G (Snapshot Immutability Verification)**: `[PASS]` — Mengubah nama `FinalExam` atau nilai `FinalExamAttempt` pada DB **TIDAK MERUBAH** PDF sertifikat (strictly terikat snapshot).
8. **Scenario H (Locked & Issued Certificate Safety)**: `[PASS]` — Render PDF berhasil untuk sertifikat status `locked` maupun `issued` tanpa merubah status database.
9. **Scenario I (Temporary Storage Cleanup Verification)**: `[PASS]` — Seluruh file PDF buatan test dibersihkan otomatis, 0 file tersisa pada storage.

---

## Baseline Post-Test Database Audit (Phase 6)

Pemeriksaan database *after-testing* mengonfirmasi zero residual synthetic data:

- Total `final_exams`: **2 record** (Identik dengan baseline)
- Total `course_levels` dengan Final Exam: **2 level** (Identik dengan baseline)
- Total `course_levels` dengan Multi Exam: **0 level** (Identik dengan baseline)
- Total `final_exam_attempts`: **7 attempts** (Identik dengan baseline)
- Total sections tanpa question: **0 record** (Identik dengan baseline)
- Total active sections tanpa active question: **0 record** (Identik dengan baseline)

---

## Phased Implementation Progress

- **Phase 1 — Database and Models**: `COMPLETED & TESTED`
- **Phase 2 — Transactional Central Completion**: `COMPLETED & TESTED`
- **Phase 3 — Auto and Manual Grading Integration**: `COMPLETED & TESTED`
- **Phase 4 — Admin Multi-Section Management**: `COMPLETED & TESTED`
- **Phase 5 — Student Multi-Section Final Exam UI**: `COMPLETED & TESTED`
- **Phase 6 — Certificate PDF Score Layout**: `COMPLETED & TESTED`
- **Phase 7 — Regression and Concurrency Testing**: `READY TO START`

---

## Remaining Notes

- Phase 1, 2, 3, 4, 5, dan 6 telah diuji 100% secara perilaku, sekuritas, visual, dan regresi dengan **0 Failures**.
- Status task berada pada **`TESTING`** (Phase 1–6 verified, Phase 7 siap dikerjakan).
- Tidak ada file vendor, database migration baru, atau TASK lain yang diubah.
