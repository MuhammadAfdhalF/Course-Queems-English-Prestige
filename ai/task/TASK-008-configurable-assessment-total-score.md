# TASK-008 — Configurable Assessment Total Score & Custom Scale Engine

**Status**: READY FOR IMPLEMENTATION  
**Date Created**: 2026-07-25  
**Date Approved**: 2026-07-26  
**Priority**: High  
**Author**: AI Pair Programmer (Antigravity)  

---

## Architecture Approval Summary

Arsitektur TASK-008 telah mencapai kesepakatan final (**FINAL CLOSURE**):
- **Main Business Flow**: Final (Result Type Pass/Fail vs Score Only, Input Total Score di awal, Passing Score numerik langsung, Attempt Limit 1/N/NULL).
- **Master Schema**: `total_score` `DECIMAL(8,2) NOT NULL` (tanpa default DB tersembunyi), `result_mode` `VARCHAR(20) NOT NULL`, `passing_score` `DECIMAL(8,2) NULLABLE`.
- **Attempt Schema**: Rename `total_score` existing menjadi `percentage_score` (`DECIMAL(5,2) NULLABLE`), tambah `raw_score` (`DECIMAL(8,2) NULLABLE`), `max_score` (`DECIMAL(8,2) NOT NULL`), `result_mode` (`VARCHAR(20) NOT NULL`), `passing_score` (`DECIMAL(8,2) NULLABLE`), `is_passed` (`BOOLEAN NULLABLE`).
- **Question & Config Locking**: Jika assessment sudah memiliki attempt (`in_progress` maupun `finished`), **SELURUH KONFIGURASI SKOR DAN QUESTION SET DIKUNCI PERMANEN**.
- **Data Reset Strategy**: Reset transaksi dummy (`certificates`, `final_exam_answers`, `final_exam_attempts`, `module_practice_answers`, `module_practice_attempts`, `free_test_results`) setelah SQL backup berhasil. `student_module_progress` DIPERTAHANKAN.
- **Certificate Integration**: Eligibility berbasis Qualifying Attempt per section; snapshot `section_scores` menyimpan data skor presisi; formula `final_score` tetap menggunakan behavior existing.
- **No Remaining Blockers**: Siap memasuki Phase A setelah persetujuan User.

---

## 1. Background

Sistem e-course saat ini secara implisit mengasumsikan bahwa setiap assessment (Final Exam Section, Module Practice, dan Free Test) dinilai dalam skala persentase $0-100\%$, dengan persentase kelulusan (`passing_grade`) bertipe integer.

Untuk mendukung skala penilaian kuis modul (misal 50 poin), tes komprehensif 100 poin, atau custom scale lainnya (misal 677 atau 990), TASK-008 dirancang untuk mentransformasi arsitektur penilaian menjadi **Configurable Assessment Total Score & Custom Scale Engine**, di mana Admin dapat menentukan `total_score` bebas, memilih `result_mode` (`score_only` vs `pass_fail`), serta menetapkan `passing_score` berupa skor numerik langsung.

> [!IMPORTANT]
> **Zero-Based Custom Scale Scope**: `TASK-008 supports configurable zero-based assessment scales. Non-zero-based standardized scoring and official TOEFL/TOEIC conversion tables are outside the current scope.`
> Penggunaan angka 677 atau 990 hanya merupakan batas skor maksimal (*custom maximum score*). Skor siswa tetap dihitung dari 0 sampai `total_score`. TASK-008 belum mendukung konversi resmi TOEFL (310–677), TOEIC (10–990), *score floor*, *conversion table*, atau *section scaled score*.

---

## 2. Main Business Flow (CONFIRMED)

Berikut alur Admin utama yang telah **DISEPAKATI & FINAL**:

Saat membuat assessment, Admin menentukan sejak awal:
1. **Result Type**:
   - `Pass / Fail`
   - `Score Only`
2. **Total Score** (misal 100, 50, 677) diinput pada awal pembuatan assessment. (Form Admin wajib mengirim `total_score`, tanpa mengandalkan default DB).
3. Jika **Pass / Fail**:
   - Input **Passing Grade (Minimum Score)** berupa skor numerik langsung;
   - Database menyimpan `passing_score` (misal 75.00). Nilai 75 adalah skor numerik langsung, bukan 75%.
4. Jika **Score Only**:
   - Passing Grade tidak ditampilkan di form;
   - Database menyimpan `passing_score = NULL` dan `is_passed = NULL`;
   - Hasil siswa hanya menampilkan `Score: 82 / 100` tanpa status Passed atau Failed.
5. **Attempt Limit** (Khusus `Final Exam` dan `Module Practice`):
   - `max_attempts = 1` $\rightarrow$ One Attempt (Input max attempts tersembunyi).
   - `max_attempts = N` ($N \ge 2$) $\rightarrow$ Multiple Attempts (Input max attempts terisi angka $\ge 2$).
   - `max_attempts = NULL` $\rightarrow$ Unlimited Attempts (Input max attempts tersembunyi).
   - Tidak memerlukan kolom `attempt_policy`.
6. **Free Test Attempt Limit**:
   - Free Test TIDAK memiliki `max_attempts` pada TASK-008 (publik / multi-submission).
7. **Alokasi Poin Soal**:
   - Admin mendistribusikan poin ke masing-masing soal hingga `SUM(active_questions.score) == total_score`.
   - Form Builder menampilkan 4 indikator: `Total Score`, `Allocated Score`, `Remaining Score`, dan `Readiness Status`.
8. **Pengaktifan Manual**:
   - Admin mengaktifkan assessment secara manual setelah `SUM(score active questions) == total_score`. Tidak ada auto-activation.

---

## 3. Existing System Audit (EXISTING BEHAVIOR)

Berdasarkan audit *read-only* pada codebase dan database per 26 Juli 2026:

### A. Database Schema Existing
- **`final_exams`**: `passing_grade` (integer default 0), `max_attempts` (integer nullable), `grading_method` (`auto`, `manual`, `mixed`), `is_active` (boolean). Belum ada `total_score`, `result_mode`, `passing_score`.
- **`module_practices`**: `passing_grade` (integer default 0), `max_attempts` (integer nullable), `is_required` (boolean default true), `is_active` (boolean). Belum ada `total_score`, `result_mode`, `passing_score`.
- **`free_tests`**: `passing_grade` (integer default 0), `duration_minutes`, `total_questions`. Belum ada `total_score`, `result_mode`.
- **`final_exam_attempts` & `module_practice_attempts`**: Memiliki `total_score` (decimal 5,2) yang saat ini **menyimpan nilai persentase ($0-100\%$)**, `status` (`in_progress`, `submitted`, `passed`, `failed`, `waiting_review`). Belum ada snapshot `raw_score`, `max_score`, `result_mode`, `passing_score`, `is_passed`.
- **`certificates`**: `section_scores` (JSON) menyimpan snapshot `score` dan `passing_grade` lama.

### B. Business Logic Existing
- **Scoring Logic** (`Student/FinalExamController`, `Student/PracticeController`):
  Menghitung `$percentageScore = ($earnedScore / $maxScore) * 100`, lalu membandingkan `$percentageScore >= $assessment->passing_grade`.
- **Certificate Issuance** (`CertificateService`):
  Memeriksa seluruh `final_exams` yang `is_active = true` pada CourseLevel. Sertifikat terbit jika **setiap active section** memiliki attempt dengan `status = 'passed'` dan `submitted_at IS NOT NULL` (serta `graded_at IS NOT NULL` jika manual/mixed).
- **Certificate Final Score Formula**:
  Mengambil rata-rata persentase skor attempt (`$totalPercentageScores / count($activeSections)`).

---

## 4. Confirmed Decisions (CONFIRMED)

1. **Main Business Flow**: 100% mengikuti Bab 2.
2. **Attempt Limit Scope**: Berlaku pada `Final Exam` dan `Module Practice`. TIDAK berlaku pada `Free Test`.
3. **Attempt Policy Column**: Tidak menggunakan kolom DB `attempt_policy`. Cukup `max_attempts` (`1`, `2..N`, `NULL`).
4. **UI Label Passing Score**: `Passing Grade (Minimum Score)`.
5. **Master `total_score` Not Null**: Form create wajib mengirim `total_score`. Tanpa default database tersembunyi.
6. **Data Reset Strategy**: Reset transaksi dummy (`certificates`, `final_exam_answers`, `final_exam_attempts`, `module_practice_answers`, `module_practice_attempts`, `free_test_results`) setelah SQL backup dev berhasil.
7. **`student_module_progress` Protection**: `student_module_progress` TIDAK DI-RESET.
8. **Permanent Question & Config Locking**: Jika assessment memiliki attempt (`in_progress` atau `finished`), seluruh scoring config & question set DIKUNCI PERMANEN.
9. **Question Versioning**: OUT OF SCOPE.
10. **Certificate Final Score Formula**: Tetap menggunakan behavior existing (`average percentage score of active sections`).

---

## 5. Proposed Master Assessment Schema & Backfill (PROPOSED)

### A. Tabel Master Assessment (`final_exams`, `module_practices`, `free_tests`)
- **`total_score`** `DECIMAL(8,2) NOT NULL`: Total skor target assessment. (Saat migrasi: buat nullable $\rightarrow$ backfill $\rightarrow$ set NOT NULL).
- **`result_mode`** `VARCHAR(20) NOT NULL`:
  - Default `final_exams`: `'pass_fail'`
  - Default `module_practices`: `'pass_fail'`
  - Default `free_tests`: `'score_only'`
  - Application layer menggunakan Constants / PHP 8.1 Enum `App\Enums\AssessmentResultMode`.
- **`passing_score`** `DECIMAL(8,2) NULLABLE`: Nilai ambang batas lulus numerik. (Wajib `NULL` jika `result_mode = 'score_only'`).
- **`passing_grade`**: Deprecated column (di-DROP pada Phase G).

### B. Free Test Existing Backfill Rules
1. **Free Test dengan `passing_grade > 0` dan Active Question Score $> 0$**:
   - `total_score = SUM(active_questions.score)`
   - `result_mode = 'pass_fail'`
   - `passing_score = round((passing_grade / 100) * total_score, 2)`
2. **Free Test / Assessment dengan `passing_grade <= 0` atau Tanpa Soal**:
   - `total_score = SUM(active_questions.score)` (jika sum = 0, fallback `100.00`)
   - `result_mode = 'score_only'`
   - `passing_score = NULL`
   - `is_active = false` (Status: `Needs Configuration`, Admin wajib diisi ulang sebelum diaktifkan).

---

## 6. Proposed Attempt Schema & Column Rename (PROPOSED)

Pada tabel `final_exam_attempts` dan `module_practice_attempts`:

1. **Rename Kolom Existing**: `total_score` $\rightarrow$ **`percentage_score`** (`DECIMAL(5,2) NULLABLE`).
   *(Rename ini destruktif secara semantik, namun 100% aman karena seluruh transaksi dummy di-reset setelah backup SQL).*
2. **Tambah Kolom Snapshot**:
   - **`raw_score`** `DECIMAL(8,2) NULLABLE`: Skor fisik poin saat submit/graded.
   - **`max_score`** `DECIMAL(8,2) NOT NULL`: Snapshot total poin saat attempt start.
   - **`result_mode`** `VARCHAR(20) NOT NULL`: Snapshot mode saat attempt start.
   - **`passing_score`** `DECIMAL(8,2) NULLABLE`: Snapshot batas lulus saat attempt start.
   - **`is_passed`** `BOOLEAN NULLABLE`: Snapshot kelulusan saat submitted/graded.

---

## 7. Result Modes Detail (CONFIRMED)

| Feature | `score_only` | `pass_fail` |
| :--- | :--- | :--- |
| **Passing Score DB** | `NULL` | Required Decimal ($0 < \text{passing\_score} \le \text{total\_score}$) |
| **Attempt Status DB** | `'submitted'` / `'waiting_review'` | `'passed'` / `'failed'` / `'waiting_review'` |
| **Attempt `is_passed`** | `NULL` | `true` jika $\text{raw\_score} \ge \text{passing\_score}$, `false` jika tidak |
| **UI Label** | "Score Only (No Pass/Fail)" | "Passing Grade (Minimum Score)" |
| **Certificate Rule** | Wajib Qualifying Attempt (`graded_at != NULL`) | Wajib Qualifying Attempt (`is_passed = true`) |

---

## 8. Attempt Limit & `max_attempts` Guard Rules (CONFIRMED)

- **One Attempt**: `max_attempts = 1`.
- **Multiple Attempts**: `max_attempts = N` ($N \ge 2$).
- **Unlimited Attempts**: `max_attempts = NULL`.

### Guard Perubahan `max_attempts` Setelah Attempt Exist:
- `1 -> 3`: Diizinkan.
- `3 -> 5`: Diizinkan.
- `3 -> NULL`: Diizinkan.
- `5 -> 2`: Diizinkan **HANYA JIKA** tidak ada siswa yang memiliki `attempt_count > 2`.
- `Multiple -> One`: Diizinkan **HANYA JIKA** tidak ada siswa yang memiliki `attempt_count > 1`.
- `Unlimited -> N`: Diizinkan **HANYA JIKA** max attempts per siswa yang pernah digunakan $\le N$.
- Perubahan `max_attempts` tidak menghapus atau membatalkan attempt yang sudah ada.

---

## 9. Question Score Allocation (PROPOSED)

- `allocated_score = SUM(score active questions)`
- `remaining_score = total_score - allocated_score`
- Perbandingan presisi: `round((float)$allocatedScore, 2) === round((float)$totalScore, 2)`

### Validation Rules:
1. **Over-allocation (`allocated_score > total_score`)**: Request simpan/edit nilai soal **LANGSUNG DITOLAK (Validation Error 422)** ("Allocated question scores exceed total score").
2. **Under-allocation (`allocated_score < total_score`)**: Request hapus/deaktivasi/edit soal **DITERIMA**, namun assessment **OTOMATIS TER-DEAKTIVASI (`is_active = false`)** dengan peringatan ("Assessment auto-deactivated due to score mismatch").
3. **Exact-allocation (`allocated_score == total_score`)**: Assessment berstatus **Ready to Activate**, tetapi **TIDAK auto-activate**.

---

## 10. Activation / Deactivation Lifecycle (CONFIRMED)

```
[Admin Create/Edit Assessment & Questions]
                   │
                   ▼
       Allocated == Total Score?
            ├── YES ──> Status: "Ready to Activate" (Admin Activates Manually)
            └── NO  ──> Auto-set `is_active = false` (Warning Displayed)
```

---

## 11. Attempt Snapshot at Start (PROPOSED)

Saat attempt siswa dibuat / dimulai (`in_progress` state), sistem **LANGSUNG MENYIMPAN SNAPSHOT** konfigurasi assessment ke tabel attempt:
- `max_score = assessment.total_score`
- `result_mode = assessment.result_mode`
- `passing_score = assessment.passing_score`

Nilai hasil diinisialisasi sebagai **`NULL`** (BUKAN `0.00` karena 0.00 adalah nilai skor valid setelah submit):
- `raw_score = NULL`
- `percentage_score = NULL`
- `is_passed = NULL`
- `submitted_at = NULL`
- `graded_at = NULL`

---

## 12. Submission & Manual Grading Lifecycle (CONFIRMED)

TIDAK MENAMBAHKAN STATUS BARU `'completed'`. Tetap menggunakan 5 status existing (`in_progress`, `submitted`, `passed`, `failed`, `waiting_review`).

### A. Mode `score_only` (Tanpa Manual Review)
- Submit: `status = 'submitted'`, `submitted_at = now()`, `graded_at = now()`, `raw_score` & `percentage_score` diisi, `is_passed = NULL`.

### B. Mode `score_only` (Dengan Manual Review Essay/Upload)
- Submit: `status = 'waiting_review'`, `submitted_at = now()`, `graded_at = NULL`, `is_passed = NULL`.
- After Manual Grading: `status = 'submitted'`, `graded_at = now()`, final `raw_score` & `percentage_score` diisi, `is_passed = NULL`.

### C. Mode `pass_fail` (Tanpa Manual Review)
- Submit: `status = 'passed'` / `'failed'`, `submitted_at = now()`, `graded_at = now()`, `is_passed = true` / `false`.

### D. Mode `pass_fail` (Dengan Manual Review)
- Submit: `status = 'waiting_review'`, `submitted_at = now()`, `graded_at = NULL`, `is_passed = NULL`.
- After Manual Grading: `status = 'passed'` / `'failed'`, `graded_at = now()`, `is_passed = (raw_score >= passing_score)`.

Completion untuk `score_only` ditentukan dari: `submitted_at IS NOT NULL AND graded_at IS NOT NULL`.

---

## 13. Permanent Question & Config Locking After Attempt Exists (CONFIRMED)

> `If an assessment has ANY attempt (in_progress or finished), its scoring configuration and complete question set are permanently locked.`

Jika assessment sudah memiliki attempt apa pun (`in_progress` maupun `finished`):
- **DITOLAK (LOCKED)**: Tambah soal, edit teks soal, edit tipe soal, edit opsi, edit kunci jawaban, edit poin soal, hapus soal, toggle active/inactive soal, pindah soal, reorder soal, edit `total_score`, edit `result_mode`, edit `passing_score`, atau edit `grading_method` yang mempengaruhi scoring.
- **DIZINKAN**: Edit `title`, `description`, `is_active`, dan `max_attempts` (dengan guard Bab 8).
- Jika Admin membutuhkan perubahan skor atau pertanyaan baru, Admin wajib membuat Final Exam Section / Practice baru.
- Question versioning dinyatakan **OUT OF SCOPE**.

---

## 14. Free Test Architecture (PROPOSED)

- Free Test adalah placement test diagnostik publik.
- Schema `free_tests`: `total_score`, `result_mode` (default `'score_only'`), `passing_score` (nullable).
- Schema `free_test_results`: Simpan snapshot `raw_score`, `max_score`, `percentage_score`, `result_mode`, `passing_score`, `is_passed`, `submitted_at`.
- Free Test TIDAK memiliki `max_attempts`, TIDAK menerbitkan sertifikat, dan TIDAK mengunci progress course.

---

## 15. Module Practice Completion (CONFIRMED)

- `is_required = true` + `pass_fail`: Modul selesai & modul berikutnya terbuka jika attempt `graded_at IS NOT NULL` AND `is_passed = true`.
- `is_required = true` + `score_only`: Modul selesai & modul berikutnya terbuka jika attempt `submitted_at IS NOT NULL` AND `graded_at IS NOT NULL`.
- `is_required = false`: Modul practice bersifat opsional dan tidak mengunci progress modul.
- `ModulePractice` HANYA mempengaruhi **Module Completion & Unlocking**, TIDAK mempengaruhi **Course Level Certificate**.

---

## 16. Certificate Eligibility & Section Snapshot (CONFIRMED)

### A. Certificate Eligibility (Qualifying Attempt)
Siswa eligible mendapatkan Sertifikat pada Course Level (`CertificateService`) jika seluruh active `final_exams` pada Level memiliki **Qualifying Attempt**:
- **Section `pass_fail`**: Memiliki minimal 1 attempt dengan `submitted_at IS NOT NULL`, `graded_at IS NOT NULL`, dan `is_passed = true`.
- **Section `score_only`**: Memiliki minimal 1 attempt dengan `submitted_at IS NOT NULL` dan `graded_at IS NOT NULL`.

### B. Certificate `section_scores` Snapshot JSON
Snapshot `section_scores` pada tabel `certificates` untuk sertifikat baru menyimpan:
```json
[
  {
    "section_id": 1,
    "section_title": "Listening Comprehension",
    "attempt_id": 12,
    "raw_score": 45.00,
    "max_score": 50.00,
    "percentage_score": 90.00,
    "result_mode": "pass_fail",
    "passing_score": 35.00,
    "is_passed": true
  }
]
```

### C. Certificate Final Score Formula (EXISTING BEHAVIOR PRESERVED)
- `$finalScore` dihitung dari rata-rata `percentage_score` seluruh active section (`$totalPercentageScores / count($activeSections)`). Formula ini **PRESERVED** dan tidak diubah oleh TASK-008.

---

## 17. Existing Data Reset & Master Backfill (CONFIRMED)

### A. Data Transaksi Dummy yang Dibersihkan (Setelah Backup SQL Dev)
1. `certificates`
2. `final_exam_answers`
3. `final_exam_attempts`
4. `module_practice_answers`
5. `module_practice_attempts`
6. `free_test_results`

> [!CAUTION]
> **Protection of `student_module_progress`**: Tabel `student_module_progress` **TIDAK DI-RESET**. Master data (`course_programs`, `course_levels`, `modules`, `materials`, `final_exams`, `questions`, `users`) PRESERVED 100%.

---

## 18. Passing Grade Deprecation (PROPOSED)

1. **Phase A–F**: Kolom `passing_grade` (persentase) dipertahankan di database sebagai *deprecated column*.
2. **Phase G**: Setelah seluruh Controller, Service, dan Blade views beralih ke `passing_score`, lakukan migrasi penutupan untuk **DROP COLUMN `passing_grade`** dari `final_exams`, `module_practices`, dan `free_tests`.

---

## 19. Implementation Phases

- [x] **Phase A — Preparation, Reset, Schema & Backfill**: (IMPLEMENTED & TESTED 2026-07-26)
  Backup SQL dev (`queens_english_db_backup_2026_07_26_154452.sql`), catat record count, FK audit, reset 44 dummy transaction records, migrasi schema master (`total_score`, `result_mode`, `passing_score`) & attempt snapshot (`percentage_score`, `raw_score`, `max_score`, `result_mode`, `passing_score`, `is_passed`), backfill master data, set zero-question assessment `is_active = false`.
- [x] **Phase B — Admin Assessment Configuration**: (IMPLEMENTED & TESTED 2026-07-26)
  Form Admin (Result Type, Total Score, Passing Score, Attempt Limit) & validasi server-side (`AssessmentConfigService`), normalisasi `passing_score = NULL` untuk mode `score_only`, config mutability guard jika attempts exist, max attempts guard, deactivation guard pada perubahan config scoring.
- [ ] **Phase C — Course Builder Allocation & Locking Guards**:
  Score allocation bar, over-allocation rejection (422), under-allocation auto-deactivation, manual activation guard, dan permanent config/question locking rule.
- **Phase D — Student Attempt Snapshot & Submission Engine**:
  Snapshot saat attempt start (`raw_score = NULL`), percentage calculation, pass/fail evaluation, dan score_only flow.
- **Phase E — Manual Grading Lifecycle**:
  Handling status `waiting_review`, recompute final raw/percentage score & `is_passed` saat `graded_at = now()`.
- **Phase F — Certificate Eligibility, Progress & Free Test Parity**:
  Qualifying attempt check, certificate section snapshot, module practice completion, dan free test parity.
- **Phase G — Regression, Cleanup & Deprecation**:
  E2E Testing, audit 25+ referensi `passing_grade`, drop column `passing_grade`, dan pembaruan akhir dokumentasi.

---

## 20. Testing Strategy (PROPOSED)

Rencana test E2E (tanpa eksekusi di tahap review ini):
1. **Admin Form**: Pass/Fail vs Score Only, Passing score validation ($0 < \text{passing\_score} \le \text{total\_score}$), Attempt limit UI (1/N/NULL).
2. **Question Allocation**: `allocated < total` (Auto-Deactivate), `allocated = total` (Ready to Activate), `allocated > total` (HTTP 422 Reject).
3. **Locking & Mutability**: Permanent locking config & question set jika attempt exist (`in_progress` / `finished`).
4. **Attempt Snapshot**: Snapshot saat attempt start (`raw_score = NULL`).
5. **Result Modes & Manual Review**: Status `submitted`/`passed`/`failed`/`waiting_review` dan `is_passed` untuk Pass/Fail vs Score Only.
6. **Certificate Eligibility**: Qualifying attempt pada multi-section Final Exam & snapshot `section_scores`.
7. **Module Practice & Free Test**: Unlocking modul dan public free test result rendering.

---

## 21. Security & Validation (CONFIRMED)

- Input numeric decimal positif untuk `total_score` & `passing_score`.
- Enforce $0 < \text{passing\_score} \le \text{total\_score}$ pada mode `pass_fail`.
- Enforce `passing_score = NULL` pada mode `score_only`.
- Impresisi history: Data attempt lama tidak boleh berubah ketika master assessment diedit.

---

## 22. Open Decisions (CLOSED / CONFIRMED)

 Seluruh open decisions sebelumnya telah **DITUTUP**:
1. **Master Backfill `passing_grade = 0`**: Set `result_mode = score_only`, `passing_score = NULL`, `is_active = false` (Status: `Needs Configuration`).
2. **UI Label Passing Score**: `Passing Grade (Minimum Score)`.
3. **`student_module_progress`**: EXCLUDED dari reset default.
4. **Question Versioning**: OUT OF SCOPE.
5. **Certificate `final_score` Formula**: EXISTING BEHAVIOR PRESERVED.
6. **Dummy Transactions Reset**: Reset setelah SQL backup berhasil.

---

## 23. Destructive Changes & Impact Analysis

1. **Reset Data Transaksi Dummy**: Hapus record pada `certificates`, `final_exam_answers`, `final_exam_attempts`, `module_practice_answers`, `module_practice_attempts`, `free_test_results`.
2. **Rename `attempts.total_score` ke `percentage_score`**: Mengubah nama kolom pada attempt table (aman karena data dummy di-reset).
3. **Depresiasi & Drop Kolom `passing_grade`**: Kolom persentase lama akan di-DROP pada Phase G setelah seluruh rujukan kode migrasi ke `passing_score`.

---

## 24. Rollback Plan (HONEST RESTORE PLAN)

> [!CAUTION]
> **Rollback Limitations**: Reset data transaksi dummy bersifat destruktif dan **TIDAK DAPAT DIPULIHKAN** hanya dengan menjalankan `php artisan migrate:rollback`.

### Prosedur Rollback:
1. Sebelum eksekusi Phase A: Wajib membuat SQL dump/backup database dev dan mengidentifikasi jumlah record.
2. Jika terjadi kegagalan kritis:
   a. Rollback migrasi database TASK-008 (`php artisan migrate:rollback`).
   b. Revert source code ke commit sebelum Phase A.
   c. **Restore SQL dump database dev** dari file backup untuk mengembalikan data dummy.
   d. Clear Laravel cache (`php artisan optimize:clear`) dan jalankan regression test.
