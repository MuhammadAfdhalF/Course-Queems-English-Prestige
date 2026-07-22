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
5. Mengimplementasikan dan menguji secara penuh **Phase 1 (Database and Models)**, **Phase 2 (Transactional Central Completion)**, **Phase 3 (Auto and Manual Grading Integration)**, **Phase 4 (Admin Multi-Section Management)**, dan **Phase 5 (Student Multi-Section Final Exam UI)**.
6. Menjalankan pengujian perilaku komprehensif (behavioral & regression testing) untuk memastikan kelayakan Phase 1–5 sebelum melanjutkan ke Phase 6.

---

## Deployment Warning — Minimum Package Notice (Phase 4 & 5)

> [!IMPORTANT]
> **MINIMUM PACKAGE DEPLOYMENT NOTICE**:
> Phase 4 (Admin Multi-Section Management) dan Phase 5 (Student Multi-Section Final Exam UI) telah selesai dikerjakan dan diuji secara komprehensif pada environment lokal.
> Dengan selesainya Phase 5, Admin sudah dapat mengelola banyak section dan Student sudah dapat melihat serta mengerjakan seluruh active section secara mandiri pada Student Learning Path.
> **Fitur belum boleh dinyatakan COMPLETED atau di-deploy penuh ke production** hingga Phase 6 (Certificate PDF Score Layout) dan Phase 7 (Final Regression & True Parallel Concurrency Test) selesai diuji.
> Status task dipertahankan pada **`TESTING`**.

---

## Implementation Result — Phase 5 (Student Multi-Section Final Exam UI)

### 1. File Diubah (Phase 5)
- **Controllers**:
  - [app/Http/Controllers/Student/LearningController.php](file:///d:/Kerja%20File/Freelance/E-Course%20Queens/Coding/Real/queens-english-prestige/app/Http/Controllers/Student/LearningController.php):
    - Memuat seluruh active final exam sections (`is_active = true`) milik `CourseLevel` diurutkan berdasarkan `sort_order ASC, id ASC`.
    - Menghitung eager count `active_questions_count`.
    - Memuat attempt student (`student_id = auth()->id()`) untuk seluruh active section dalam **single query grouped by `final_exam_id`** (zero N+1 query issue).
    - Menyusun collection `$finalExamSections` dengan data status resolution (`passed`, `waiting_review`, `in_progress`, `failed`, `not_started`), official display score (latest passed attempt), count attempts used/remaining, serta kriteria aksi (`can_start`, `can_continue`, `can_retake`).
    - Penerapan **Completed Enrollment Exemption**: Student yang sudah memiliki status `completed` dan sertifikat issued tidak dipaksa untuk mengerjakan active section baru yang ditambahkan Admin belakangan.
    - Backward Compatibility: Tetap mengirim `$finalExam`, `$latestFinalExamAttempt`, `$finalExamAttemptCount`, `$canRetakeFinalExam` untuk menjaga kompatibilitas dengan komponen legacy.
- **Views**:
  - [resources/views/partials/student/learning-path/final-exam.blade.php](file:///d:/Kerja%20File/Freelance/E-Course%20Queens/Coding/Real/queens-english-prestige/resources/views/partials/student/learning-path/final-exam.blade.php):
    - Merender daftar seluruh section card dengan penomoran urut, judul section, deskripsi, passing grade, max attempts, attempt counters, status badge (Passed, Waiting for Review, In Progress, Failed, Not Started, Temporarily Unavailable), score, serta tombol aksi (*Start Exam*, *Continue Exam*, *Retake Exam*, *View Result*, *Locked*, *Unavailable*).

---

## Behavioral Testing Result — Phase 5

### Test Environment
- **OS**: Windows (x64)
- **Database**: MySQL 8.0 / MariaDB lokal (`queens_english_db`) pada `127.0.0.1:3306`
- **Isolation Strategy**: `DB::beginTransaction()` & `DB::rollBack()` (Zero side effect / Zero persistent synthetic test data)

### Test Suite Summary (Phase 5)
- **Total Scenarios**: 18 Scenarios
- **Passed**: 18 Scenarios (100% Pass Rate)
- **Failed**: 0 Scenarios

### Detailed Test Scenario Results (Phase 5)
1. **Scenario A (Existing Single Section)**: `[PASS]` — Course dengan 1 active section merender single card secara bersih dengan atribut dan link yang tepat.
2. **Scenario B (Three Active Sections Ordering)**: `[PASS]` — 3 active section dimuat dalam urutan presisi `sort_order ASC, id ASC`.
3. **Scenario C (Inactive Section Hidden)**: `[PASS]` — Section berstatus `is_active = false` sepenuhnya tersembunyi dari Student UI.
4. **Scenario D (Not Started Status)**: `[PASS]` — Section yang belum pernah dikerjakan berstatus `not_started`, `can_start = true`, tombol *Start Exam* aktif.
5. **Scenario E (In Progress Status)**: `[PASS]` — Section dengan attempt `in_progress` berstatus `in_progress`, `can_continue = true`, tombol *Continue Exam* aktif memakai section ID yang sesuai.
6. **Scenario F (Waiting Review Status)**: `[PASS]` — Section berstatus `waiting_review` memblokir pembukaan attempt baru (`can_start = false`, `can_retake = false`).
7. **Scenario G (Failed with Attempts Remaining)**: `[PASS]` — Section gagal dengan sisa kesempatan berstatus `failed`, menampilkan score (50.00%), `can_retake = true`, `attempts_remaining = 2`.
8. **Scenario H (Failed with Max Attempts Reached)**: `[PASS]` — Section gagal yang telah mencapai max attempts berstatus `failed`, `can_retake = false`, tombol retake dinonaktifkan.
9. **Scenario I (Passed Section)**: `[PASS]` — Section yang lulus menampilkan latest passed score (85.00%), tombol *View Result* aktif, dan section lain yang belum lulus tetap dapat dikerjakan secara bebas.
10. **Scenario J (Latest Passed Rule Selection)**: `[PASS]` — Attempt 1 passed (score 90), Attempt 2 passed (latest, score 80) $\rightarrow$ Display score = 80.00% (konsisten dengan `CertificateService`).
11. **Scenario K (Independent Attempt Counters)**: `[PASS]` — Attempt count terpisah secara independen antar section (Section 1 = 2 attempts, Section 2 = 1 attempt).
12. **Scenario L (Course Progress Locked)**: `[PASS]` — Modul belum 100% selesai $\rightarrow$ `isFinalExamUnlocked = false`, seluruh section terkunci (`can_start = false`).
13. **Scenario M (Active Section Without Question)**: `[PASS]` — Section tanpa active question berstatus `Temporarily Unavailable`, `can_start = false`.
14. **Scenario N (Completed Student & New Section Exemption)**: `[PASS]` — Student yang sudah completed dan memiliki sertifikat tidak ditandai belum lulus ketika Admin menambah section baru.
15. **Scenario O (Legacy Certificate Compatibility)**: `[PASS]` — Certificate legacy dengan `section_scores = null` dimuat tanpa error Blade/collection.
16. **Scenario Q (Direct Inactive Section Access Security)**: `[PASS]` — Akses URL langsung ke section inactive menangkap error 404 security block.
17. **Scenario R (Cross-Course IDOR Security)**: `[PASS]` — Akses URL langsung ke section milik Course B menggunakan enrollment Course A menangkap error 404 IDOR block.
18. **Scenario S (No Certificate PDF Code Changes)**: `[PASS]` — Memverifikasi `CertificateService.php` dan `resources/views/pdf/certificate.blade.php` tidak tersentuh.

---

## Baseline Post-Test Database Audit (Phase 5)

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
- **Phase 6 — Certificate PDF Score Layout**: `READY TO START`
- **Phase 7 — Regression and Concurrency Testing**: `REMAINING`

---

## Remaining Notes

- Phase 1, 2, 3, 4, dan 5 telah diuji 100% secara perilaku, sekuritas, dan regresi dengan **0 Failures**.
- Status task berada pada **`TESTING`** (Phase 1–5 verified, Phase 6 siap dikerjakan).
- Tidak ada file dari PDF Certificate, `CertificateService`, atau TASK lain yang diubah.
