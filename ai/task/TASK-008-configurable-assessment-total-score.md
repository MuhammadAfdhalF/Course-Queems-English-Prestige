# TASK-008 — Configurable Assessment Total Score & Custom Scale Engine

**Status**: READY (REVISED WITH DATABASE AUDIT & BACKFILL)  
**Date Created**: 2026-07-25  
**Priority**: High  
**Author**: AI Pair Programmer (Antigravity)  

---

## 1. Background & Audit Summary Existing Database

Berdasarkan audit database read-only pada data existing, ditemukan bahwa setiap assessment (Final Exam, Module Practice, dan Free Test) memiliki variasi `SUM(active_questions.score)` yang **TIDAK SELALU 100**:

### Pemetaan Data Existing DB:
1. **Final Exam Sections (`final_exams`)**:
   - ID 1 (`Final Exam Basic 1 ye`): SUM Question Score = **11.00** | Passing Grade = **75%** | Status: ACTIVE | Attempts: 5.
   - ID 2 (`Drill Final Exam`): SUM Question Score = **30.00** | Passing Grade = **30%** | Status: ACTIVE | Attempts: 0.
   - ID 353 (`Listening`): SUM Question Score = **100.00** | Passing Grade = **90%** | Status: ACTIVE | Attempts: 1.
   - ID 354 (`Structure`): SUM Question Score = **100.00** | Passing Grade = **80%** | Status: ACTIVE | Attempts: 1.
   - ID 355 (`Reading`): SUM Question Score = **100.00** | Passing Grade = **80%** | Status: ACTIVE | Attempts: 1.
2. **Module Practices (`module_practices`)**:
   - ID 1 (`Daily Conversation Practice`): SUM Question Score = **50.00** | Passing Grade = **75%** | Status: ACTIVE | Attempts: 4.
   - ID 2 (`zzzzzz...`): SUM Question Score = **1.00** | Passing Grade = **100%** | Status: ACTIVE | Attempts: 3.
   - ID 3 (`Drill 1`): SUM Question Score = **32.00** | Passing Grade = **80%** | Status: ACTIVE | Attempts: 0.
   - ID 4 (`asd`): SUM Question Score = **1.00** | Passing Grade = **80%** | Status: ACTIVE | Attempts: 1.
3. **Free Tests (`free_tests`)**:
   - ID 1 (`Free Grammar Test`): SUM Question Score = **20.00** | Passing Grade = **100%** | Status: ACTIVE | Results: 4.
   - ID 3 (`TOEIC`): SUM Question Score = **20.00** | Passing Grade = **100%** | Status: ACTIVE | Results: 1.
   - ID 4 (`asdas`): SUM Question Score = **1.00** | Passing Grade = **83%** | Status: ACTIVE | Results: 0.

### KESIMPULAN AUDIT:
Menyetel default `total_score = 100` pada migration akan **MERUSAK ASSESSMENT EXISTING** (misal ID 1 yang memiliki total poin 11 akan otomatis ter-deactivate). Proposal backfill **WAJIB MENGGUNAKAN `SUM(active_questions.score)` REAL** dari masing-masing record!

---

## 2. Approved Business Decisions & Architecture Rules

1. **Dual Column Passing Threshold**:
   - Kolom `passing_grade` (persentase 0–100%) **TETAP DIPERTAHANKAN** untuk kompatibilitas data lama & `CertificateService`.
   - Kolom baru `passing_score` (`decimal(8,2)`) ditambahkan untuk menyimpan nilai passing raw score dalam skala `total_score`.
2. **Backfill Proposal Formulasi**:
   - Untuk assessment existing dengan `SUM(active_questions.score) > 0`:
     - `total_score` = `SUM(active_questions.score)`
     - `passing_score` = `round((passing_grade / 100) * total_score, 2)`
   - Untuk assessment existing dengan 0 active question atau `SUM == 0`:
     - `total_score` = `100.00`
     - `passing_score` = `round((passing_grade / 100) * 100.00, 2)`
     - Status `is_active` dipaksa `false`.
3. **Penyimpanan Attempt Student & Snapshot**:
   - Attempt lama menyimpang percentage pada `total_score`. Fallback attempt lama: jika `raw_score` NULL, `raw_score = total_score` dan `max_score = 100.00`.
   - Attempt baru menyimpan `raw_score`, `max_score`, dan `total_score` (percentage).
   - Sertifikat lama 100% immutable (JSON snapshot tidak berubah).
4. **Comprehensive Auto-Deactivation Guard**:
   Assessment yang `is_active = true` akan **OTOMATIS DI-DEACTIVATE (`is_active = false`)** jika terjadi salah satu aksi berikut yang menyebabkan total poin soal tidak sama dengan `total_score`:
   - Edit bobot/score soal;
   - Delete soal;
   - Toggle status active/inactive soal;
   - Edit `total_score` assessment di form admin;
   - Perubahan `passing_score` yang tidak valid (`passing_score > total_score` atau `passing_score <= 0`).
5. **2-Decimal Safe Comparisons**:
   Semua perbandingan total poin soal dan `total_score` di PHP WAJIB menggunakan pembulatan presisi 2 desimal:
   `round((float) $sumActiveScores, 2) === round((float) $assessment->total_score, 2)`
   *(Mencegah bug ketidakcocokan floating point FP precision)*.
6. **Pembedaan Lifecycle Assessment**:
   - **Final Exam**: Multi-section per Course Level. Memerlukan `CertificateService` evaluation.
   - **Module Practice**: Single practice per Module. Memerlukan `StudentProgressService` module completion evaluation.
   - **Free Test**: Public lead generation test (tidak ada enrollment student).

---

## 3. Database Impact & Precise Migration Proposal

```php
// Migration Proposal Draft
Schema::table('final_exams', function (Blueprint $table) {
    $table->decimal('total_score', 8, 2)->nullable()->after('description');
    $table->decimal('passing_score', 8, 2)->nullable()->after('passing_grade');
});

// Backfill Script inside Migration
foreach (FinalExam::with('questions')->get() as $exam) {
    $sumScore = (float) $exam->questions->where('is_active', true)->sum('score');
    if ($sumScore > 0) {
        $exam->update([
            'total_score' => $sumScore,
            'passing_score' => round(($exam->passing_grade / 100) * $sumScore, 2),
        ]);
    } else {
        $exam->update([
            'total_score' => 100.00,
            'passing_score' => round(($exam->passing_grade / 100) * 100.00, 2),
            'is_active' => false,
        ]);
    }
}
```

Pola migration & backfill yang sama persis diterapkan untuk tabel `module_practices` dan `free_tests`.

---

## 4. Implementation Phases

- **Phase A — Schema Migration & Backfill Foundation**:
  Jalankan migration penambahan `total_score` & `passing_score` (nullable) dengan backfill terhitung dari `SUM(active_questions.score)` existing.
- **Phase B — Admin Assessment Form (Total Score & Passing Score)**:
  Update Create & Edit form Admin untuk Final Exam, Module Practice, dan Free Test.
- **Phase C — Question Live Helper & Auto-Deactivation Guard**:
  Widget `Current / Total / Remaining` pada Question Index & Guard otomatis mematikan `is_active` jika poin berubah.
- **Phase D — Student Scoring Engine & Attempt Snapshot**:
  Penyimpanan `raw_score`, `max_score`, dan percentage pada attempt disertai evaluasi `raw_score >= passing_score`.
- **Phase E — Completion & Certificate Service Parity**:
  Snapshot `raw_score`, `max_score`, `percentage_score`, dan `passing_score` pada `section_scores` JSON.
- **Phase F — Module Practice & Free Test Parity**:
  Penerapan parity untuk Module Practice dan Free Test.
- **Phase G — Regression Verification**:
  Verifikasi attempt & certificate lama.

---

## 5. Backward Compatibility & Production Safety

- **0 Breaking Changes pada Data Lama**: Attempt lama ter-fallback aman (`max_score = 100`).
- **Sertifikat Lama 100% Utuh**: `section_scores` JSON snapshot tidak tersentuh.
- **TASK-005 & TASK-006 Safe**: Seluruh arsitektur multi-section dan layout PDF baru tetap aman 100%.

---

## 6. Acceptance Criteria

1. Migration melakukan backfill `total_score` sesuai `SUM` poin soal real data existing, bukan hardcoded 100.
2. Form Admin mendukung input `total_score` dan `passing_score`.
3. Auto-Deactivation Guard mematikan status `is_active` secara otomatis jika soal diedit, dihapus, atau di-toggle sehingga total poin tidak pas `total_score`.
4. Perbandingan desimal menggunakan `round(..., 2)` yang aman dari FP floating precision error.
5. Attempt dan sertifikat lama tetap valid tanpa bug.
