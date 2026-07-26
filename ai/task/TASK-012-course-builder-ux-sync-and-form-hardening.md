# TASK-012 — Course Builder UX Synchronization, Active Defaults, Price Formatting, dan Practice Form Hardening

**Status**: COMPLETED — IMPLEMENTED & TESTED  
**Date Created**: 2026-07-26  
**Priority**: High  
**Author**: AI Pair Programmer (Antigravity)  

---

## 1. Background & Business Goals

Task ini bertujuan menyelesaikan 4 masalah usability dan form integrity pada Unified Course Builder:
1. **Price Formatting**: Format tampilan harga pada form Admin Course Level dengan pemisah ribuan Indonesia (`100.000`), sedang database & payload tetap menerima integer (`100000`). Display public tetap `Rp 100.000`.
2. **Tree–Workspace Synchronization**: Ketika entity dibuka dari workspace kanan, tree kiri otomatis membuka seluruh parent (ancestor), menyorot node yang aktif, dan meng-scroll node ke area viewport yang terlihat.
3. **Default Active Behavior & Assessment Auto-Activation**:
   - `CourseProgram`, `CourseLevel`, `Module`, `ModuleMaterial` baru otomatis berstatus `is_active = true`.
   - `ModulePractice` & `FinalExam` baru tanpa soal berstatus `is_active = false`.
   - **Continuous Readiness Auto-Activation**: Assessment otomatis berstatus `is_active = true` apabila memenuhi kriteria readiness (exact allocation score, valid scoring config), dan otomatis `is_active = false` bila under-allocation/mismatch.
4. **Practice & Final Exam Form Payload Hardening (Fix 422)**:
   - **Confirmed Root Cause**: Pada `course-builder.js` method `submitDrawerForm()`, pengisian `formData` untuk `practice` & `final_exam_section` mengecualikan field `result_mode`, `total_score`, `passing_score`, dan `attempt_mode`. Karena elemen form di `drawer.blade.php` tidak memiliki atribut `name="..."` (hanya `x-model`), `FormData` bawaan browser tidak menangkap nilainya, sehingga dikirimkan ke backend tanpa 4 field tersebut dan memicu error HTTP 422.

---

## 2. Technical Implementation Plan

### A. Price Formatting & Server Normalization
- UI Form (`drawer.blade.php` & legacy forms): Gunakan input formatted `100.000` dengan `inputmode="numeric"`.
- JavaScript (`course-builder.js`): Konversi/format angka ke `100.000` saat drawer dibuka/diisi, serta normalisasi ke raw integer `100000` sebelum dimasukkan ke `FormData`.
- Backend Controller (`CourseLevelController` / `CourseBuilderController`): Tambahkan normalisasi harga (`str_replace(['Rp', '.', ' '], '', $price)`) sebelum validasi `numeric`.

### B. Tree-Workspace Synchronization
- JavaScript (`course-builder.js`):
  - Buat helper `selectNodeAndReveal(type, id, ancestors = [])`.
  - Pastikan semua ID ancestor dimasukkan ke `expandedNodes[ancestorId] = true`.
  - Setelah DOM update (`Alpine.nextTick`), scroll node yang terpilih ke area viewport dengan `element.scrollIntoView({ block: 'nearest', behavior: 'smooth' })`.
  - Pertahankan node terpilih setelah mutation (create, update, reorder). Jika delete, pilih parent node.

### C. Assessment Auto-Activation & Readiness Guards
- Update `AssessmentConfigService` & Assessment Controllers (`ModulePracticeController`, `FinalExamController`, `ModulePracticeQuestionController`, `FinalExamQuestionController`):
  - Saat question ditambahkan/diubah/dihapus atau config diubah, hitung total alokasi poin soal.
  - Jika alokasi poin tepat sama dengan `total_score` dan config valid, set `is_active = true` secara otomatis.
  - Jika under-allocation atau question score belum exact, set `is_active = false`.

### D. Fix 422 Payload Error in Drawer & Legacy Forms
- Update `drawer.blade.php`: Tambahkan atribut `name="..."` pada input `result_mode`, `total_score`, `passing_score`, `practice_attempt_mode`, dan `max_attempts`.
- Update `course-builder.js`:
  - Di method `submitDrawerForm()`, secara eksplisit set `result_mode`, `total_score`, `passing_score`, `attempt_mode`, dan `max_attempts` ke dalam `formData`.
  - Jika `result_mode === 'score_only'`, kosongkan `passing_score` (kirim `null`/empty).
  - Jika `attempt_mode === 'unlimited'`, set `max_attempts` `null`/empty.

---

## 3. Implementation Checklist

- [x] **Phase A — Price Formatting & Server Normalization**: Strict `parseRupiahPrice()` parser implemented (rejects negative numbers, negative prefixes, decimals, and leading/trailing alpha strings).
- [x] **Phase B — Tree-Workspace Synchronization & Ancestor Expansion**: Typed node keys (`level_{id}`, `module_{id}`, `level_{id}_exam`) used to prevent ID collision. Smooth `scrollIntoView()` implemented.
- [x] **Phase C — Practice & Final Exam Form Payload Hardening (Fix 422)**: Fixed missing `result_mode`, `total_score`, `passing_score`, `attempt_mode`, `max_attempts` fields in JS `submitDrawerForm()` and added missing HTML `name` attributes.
- [x] **Phase D — Continuous Assessment Readiness Auto-Activation**: `syncAssessmentReadinessState()` parity added across both Practice and Final Exam (auto-activates when question total score equals assessment total_score, auto-deactivates on mismatch).
- [x] **Phase E — Isolated Testing & Parity Verification**: 65/65 tests passed on `queens_english_test`. Zero data corruption on `queens_english_db`.

---

## 4. Safety & Non-Goals

- Database testing wajib mengarah ke `queens_english_test` (terverifikasi via `tests/TestCase.php`).
- Tidak boleh menjalankan `RefreshDatabase` pada database `queens_english_db`.
- Tidak ada perubahan pada Student UI atau scoring attempt engine TASK-008.
