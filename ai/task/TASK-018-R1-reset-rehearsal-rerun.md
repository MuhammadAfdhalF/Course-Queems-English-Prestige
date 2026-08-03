# TASK-018-R1 — Reset Database Clone Rehearsal Rerun Report

**Project Utama**: `D:\Kerja File\Freelance\E-Course Queens\Coding\Real\queens-english-prestige`  
**Workspace Rehearsal**: `D:\Kerja File\Freelance\E-Course Queens\Coding\Rehearsal\queens-english-prestige-reset-rehearsal`  
**Database Target Rehearsal**: `queens_english_reset_test`  
**Database Development Utama**: `queens_english_db`  
**Baseline Commit TASK-017-R1**: `38e144092ea457526f4fda4d4d4c4cafcbd7f36c` (`38e1440`)  

**Status Final TASK-018-R1**: 
```text
TASK-018-R1:
RESET 1 & RESET 2 ISOLATED REHEARSAL PASSED
— PRODUCTION EXECUTION STILL BLOCKED
```

---

## 1. Background & Goals

Setelah perbaikan *Protected Session Verifier Hardening* pada TASK-017-R1 (commit `38e1440`), rehearsal ulang TASK-018-R1 dijalankan untuk menguji keandalan Artisan Command data reset pada lingkungan terisolasi penuh:
1. **Reset 1 (`app:reset-pre-production`)**: Pembersihan total seluruh data operasional student dan hirarki master course.
2. **Reset 2 (`app:reset-student-operations`)**: Pembersihan data operasional student dengan mempertahankan master course, modul, materi, ujian, dan sertifikat template.

---

## 2. Commit Baseline & Environment Verification

- **Rehearsal Source Commit SHA**: `38e144092ea457526f4fda4d4d4c4cafcbd7f36c` (`38e1440`)
- **Main Project Status**: Clean (`0` uncommitted files).
- **Rehearsal Environment**: `APP_ENV=reset-testing`, `DB_DATABASE=queens_english_reset_test`
- **Rehearsal Storage Root**: `D:\Kerja File\Freelance\E-Course Queens\Coding\Rehearsal\queens-english-prestige-reset-rehearsal\storage\app\public`
- **Baseline Backup SHA-256**: `bdad22950830c0acac15e9fe8cf4fa7388882477466add8d7b5c27c7510aeaf4`

---

## 3. Hasil Rerun Reset 1 (`app:reset-pre-production`)

### A. Dry-Run Result
- **Exit Code**: `0`
- **Mode**: `DRY-RUN (Simulation Only)`
- **Target Summary**: 30 tabel teridentifikasi, 0 data terhapus.

### B. Execute Result
- **Maintenance Mode**: Activated (`php artisan down`)
- **Input Confirmation**: `RESET PRE PRODUCTION DATA`
- **Exit Code**: `0` (SUCCESS & COMMITTED)
- **Total Deleted Records**: `295`
- **Quarantined Files**: `3` PDF certificates & testimonial photos safely isolated.
- **Protected Checksum**: `f24e56a40e52333e80d7550e2926e8528b5d591bd09cc32f4ea4a6aee93417ee` (PASSED & COMMITTED)

### C. Post-Verification Record Counts (Reset 1)
- `users` (student): `0`
- `users` (admin): `1` (Preserved)
- `course_programs`: `0`
- `course_levels`: `0`
- `modules`: `0`
- `module_materials`: `0`
- `module_practices`: `0`
- `final_exams`: `0`
- `testimonials`: `0`
- `free_tests`: `0`
- `certificates`: `0`
- `orders`: `0`

---

## 4. Baseline Restore Before Reset 2

- Database `queens_english_reset_test` di-drop dan di-import ulang dari backup baseline `bdad2295...`.
- Filesystem storage rehearsal di-restore total dari physical storage baseline awal.

---

## 5. Hasil Rerun Reset 2 (`app:reset-student-operations`)

### A. Dry-Run Result
- **Exit Code**: `0`
- **Mode**: `DRY-RUN (Simulation Only)`
- **Target Summary**: 16 tabel operasional teridentifikasi, 0 data terhapus.

### B. Execute Result
- **Maintenance Mode**: Activated (`php artisan down`)
- **Input Confirmation**: `RESET STUDENT OPERATIONS`
- **Exit Code**: `0` (SUCCESS & COMMITTED)
- **Total Deleted Records**: `159`
- **Quarantined Files**: `3` PDF certificates & testimonial photos safely isolated.
- **Protected Checksum**: `f5c3e80dc3385e2085ce87228074d19483fe515952a1aa585332dd3e366d9519` (PASSED & COMMITTED)

### C. Post-Verification Record Counts (Reset 2)
- `users` (student): `0` (Cleared)
- `users` (admin): `1` (Preserved)
- `course_programs`: `3` (Preserved)
- `course_levels`: `5` (Preserved)
- `modules`: `6` (Preserved)
- `module_materials`: `16` (Preserved)
- `module_practices`: `5` (Preserved)
- `final_exams`: `7` (Preserved)
- `testimonials`: `0` (Cleared)
- `free_tests`: `3` (Preserved)
- `certificates`: `0` (Cleared)
- `orders`: `0` (Cleared)

---

## 6. Primary Development Verification (`queens_english_db` & Main Storage)

Pemeriksaan read-only akhir pasca-rerun rehearsal:

```text
Development Database Active: queens_english_db
----------------------------------------
users                     : 5 (expected 5) [MATCH]
course_programs           : 3 (expected 3) [MATCH]
course_levels             : 5 (expected 5) [MATCH]
modules                   : 6 (expected 6) [MATCH]
module_materials          : 16 (expected 16) [MATCH]
module_practices          : 5 (expected 5) [MATCH]
final_exams               : 7 (expected 7) [MATCH]
migrations                : 62 (expected 62) [MATCH]
```

- **Database Development Utama (`queens_english_db`)**: **100% Utuh / Match Baseline**.
- **Storage Project Utama**: **100% Utuh / Hash Identik dengan Manifest Baseline**.
- **Maintenance Mode Main Workspace**: **OFF**.

---

## 7. Status Final & Production Guard

```text
TASK-018-R1:
RESET 1 & RESET 2 ISOLATED REHEARSAL PASSED
— PRODUCTION EXECUTION STILL BLOCKED
```

*(Catatan: Flag production execution tetap terkunci rapat. Jalur eksekusi ke production tidak dibuka.)*
