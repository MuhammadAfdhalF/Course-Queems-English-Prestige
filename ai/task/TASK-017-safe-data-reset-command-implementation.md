# TASK-017 — Safe Data Reset Commands Implementation & Automated Tests

## Status: IMPLEMENTED & AUTOMATED TESTED — MANUAL REHEARSAL PENDING

> [!IMPORTANT]
> **PHASE STATUS**: This document details the implementation of the safe data reset service, console commands, safety guards, file quarantine handler, protected data verifier, and automated test suite.
> **MANUAL REHEARSAL PENDING**: Manual execution on a dedicated database clone (`queens_english_reset_test`) has NOT yet been run. Development database (`queens_english_db`) and production remain 100% untouched.

---

## 1. Executive Summary

Implemented two production-guarded Artisan CLI reset commands for Queens English Prestige:
1. `php artisan app:reset-pre-production` (Reset 1 — Full Pre-Production Reset)
2. `php artisan app:reset-student-operations` (Reset 2 — Student & Operational Reset)

Both commands strictly enforce:
- Exact environment guard (`APP_ENV=reset-testing` for manual execute, `*_test` DB for PHPUnit).
- Database name guard (`queens_english_reset_test` for manual execute). Development database (`queens_english_db`) and production databases are **strictly forbidden**.
- Application Maintenance Mode check (`php artisan down`) for `--execute`.
- Pending queue job check (`jobs` and `job_batches`).
- Exact typed confirmation phrase matching (`RESET PRE PRODUCTION DATA` or `RESET STUDENT OPERATIONS`).
- Full database transaction with step-by-step Query Builder deletion (`DB::table(...)->delete()`).
- Pre/Post protected data checksum verification (SHA-256). Any protected data mutation triggers instant transaction rollback.
- Post-commit file quarantine handler for generated student PDFs and operational testimonial photos.
- Post-commit cache clearing.

---

## 2. Architecture & File Inventory

### A. Created Source Files
1. `app/Services/DataReset/ResetSafetyGuard.php` — Safety guards for environment, database name, maintenance mode, and queue jobs.
2. `app/Services/DataReset/ProtectedDataVerifier.php` — Deterministic SHA-256 checksum calculator & verifier for protected datasets.
3. `app/Services/DataReset/ResetFileQuarantine.php` — Post-commit file quarantine handler and manifest writer (`storage/app/data-reset-quarantine/{timestamp}/{type}/manifest.json`).
4. `app/Services/DataReset/ResetPlan.php` — Abstract reset plan definition.
5. `app/Services/DataReset/ResetPreProductionPlan.php` — Reset 1 step plan (32 steps).
6. `app/Services/DataReset/ResetStudentOperationsPlan.php` — Reset 2 step plan (18 steps).
7. `app/Services/DataReset/DataResetService.php` — Master orchestrator service.
8. `app/Console/Commands/ResetPreProduction.php` — Artisan command `app:reset-pre-production`.
9. `app/Console/Commands/ResetStudentOperations.php` — Artisan command `app:reset-student-operations`.
10. `.env.reset-testing.example` — Safe example configuration for manual rehearsal environment.
11. `tests/Feature/DataResetCommandTest.php` — Automated test suite.
12. `ai/task/TASK-017-safe-data-reset-command-implementation.md` — Task documentation.

### B. Modified Files
1. `.gitignore` — Added `.env.reset-testing`.
2. `ai/task/TASK-016-pre-production-data-reset-audit-and-safety-plan.md` — Corrected step count typos (32 steps for Reset 1, 18 steps for Reset 2).

---

## 3. Command Usage & Signatures

### A. Reset 1 — Full Pre-Production Reset
```bash
# Dry-run simulation (No DB or file mutation)
php artisan app:reset-pre-production --dry-run

# Execution (Requires maintenance mode, exact DB, and typed confirmation)
php artisan app:reset-pre-production --execute
```
**Typed Confirmation Phrase**: `RESET PRE PRODUCTION DATA`

### B. Reset 2 — Student & Operational Data Reset
```bash
# Dry-run simulation (No DB or file mutation)
php artisan app:reset-student-operations --dry-run

# Execution (Requires maintenance mode, exact DB, and typed confirmation)
php artisan app:reset-student-operations --execute
```
**Typed Confirmation Phrase**: `RESET STUDENT OPERATIONS`

---

## 4. Deletion Order Protocols

### Reset 1 Deletion Order (32 Steps):
1. Memory extraction of `$studentIds` & `$studentEmails`.
2. Collection of generated student certificate relative file paths.
3. Collection of testimonial photo relative file paths.
4. `notifications`
5. `certificates`
6. `certificate_templates`
7. `testimonials`
8. `free_test_results`
9. `free_test_questions`
10. `free_tests`
11. `free_test_categories`
12. `payments`
13. `student_module_progress`
14. `student_course_enrollments`
15. `orders`
16. `final_exam_answers`
17. `final_exam_attempts`
18. `final_exam_question_options`
19. `final_exam_questions`
20. `final_exams`
21. `module_practice_answers`
22. `module_practice_attempts`
23. `module_practice_question_options`
24. `module_practice_questions`
25. `module_practices`
26. `module_materials`
27. `modules`
28. `course_levels`
29. `course_programs`
30. `sessions` (`whereIn('user_id', $studentIds)`)
31. `password_reset_tokens` (`whereIn('email', $studentEmails)`)
32. `student_profiles`
33. `users` (`where('role', 'student')`)

### Reset 2 Deletion Order (18 Steps):
1. Memory extraction of `$studentIds` & `$studentEmails`.
2. Collection of generated student certificate relative file paths.
3. Collection of testimonial photo relative file paths.
4. `notifications`
5. `certificates`
6. `testimonials`
7. `free_test_results`
8. `payments`
9. `student_module_progress`
10. `student_course_enrollments`
11. `orders`
12. `final_exam_answers`
13. `final_exam_attempts`
14. `module_practice_answers`
15. `module_practice_attempts`
16. `sessions` (`whereIn('user_id', $studentIds)`)
17. `password_reset_tokens` (`whereIn('email', $studentEmails)`)
18. `student_profiles`
19. `users` (`where('role', 'student')`)

---

## 5. File Quarantine & Protection Protocol

- **Design Assets (100% Preserved)**: Certificate template backgrounds (`storage/app/public/certificate-templates/`), Signatures (`certificate-signatures/`), Logos, Stamps, Default templates (`public/images/certificates/certificate-default-background.jpg`).
- **Student Generated PDFs & Testimonial Photos (Quarantined Post-Commit)**: Generated student PDFs (`certificates/*.pdf`) and testimonial photos (`testimonials/*`) are collected before deletion, and safely moved to `storage/app/data-reset-quarantine/{timestamp}/{type}/` AFTER database transaction commit.
- **Manifest Writer**: Generates `manifest.json` recording source path, quarantine path, file size, and SHA-256 hash.

---

## 6. Exit Codes

- `0`: Success (Dry-run completed or Execute completed with 0 warnings).
- `1`: Error / Guard Failure (Invalid options, environment mismatch, confirmation failed, queue jobs pending, checksum mismatch, or exception).
- `2`: Warning Status (DB reset transaction committed successfully, but post-commit warning occurred e.g. cache clear error or missing file).

---

## 7. Automated Test Suite Results

Full PHPUnit test suite executed against isolated testing DB `queens_english_test`:
```text
  Tests:    87 passed (335 assertions)
  Duration: 16.89s
```
**Seluruh 87 test cases (termasuk 8 test cases DataResetCommandTest baru) LULUS 100%.**

---

## 8. Development DB Safety Verification

Development database `queens_english_db` baseline record counts remain 100% intact:
- `users`: 5
- `course_programs`: 3
- `course_levels`: 5
- `modules`: 6
- `module_materials`: 16
- `module_practices`: 5
- `final_exams`: 7
- `migrations`: 62

---

## 9. TASK-017-R1 — Protected Session Verifier Hardening

### A. TASK-018 Rehearsal Finding & Confirmed Root Cause
- **Finding**: Saat rehearsal TASK-018 dijalankan pada database clone `queens_english_reset_test`, baik Reset 1 maupun Reset 2 memicu rollback dengan error `Protected data verification failed! [sessions_non_student]`.
- **Root Cause**:
  1. Parameter `studentIds` yang dikirim ke `calculateChecksum` berbeda antara sebelum deletion (`$studentIds` lengkap) dan sesudah deletion (`[]` kosong), menyebabkan kueri filter `whereNotIn('user_id', $studentIds)` bergeser dari subset filter menjadi `DB::table('sessions')` tanpa filter.
  2. Kueri `whereNotIn('user_id', $studentIds)` dalam standar SQL mengabaikan baris di mana `user_id IS NULL` (sesi guest/CLI).
  3. Menyertakan kolom volatile (`payload`, `last_activity`) membuat checksum peka terhadap perubahan runtime timestamp session driver Laravel.

### B. Hardened Strategy for Session & Password Reset Tokens
- **Explicit Identity Strategy**: Mengambil `$protectedUserIds` (user role `!= student`) dan `$protectedUserEmails` sebelum transaksi dimulai. Daftar yang persis sama dikirim ke checksum `before` dan `after`.
- **Protected Sessions Query**: `DB::table('sessions')->whereIn('user_id', $protectedUserIds)`
- **Stable Identity Columns**: Hanya menyertakan `id` dan `user_id` (mengabaikan `payload` dan `last_activity`).
- **Guest Session Policy**: Sesi guest (`user_id IS NULL`) tidak dikategorikan sebagai protected session dan tidak memicu false positive mismatch.
- **Protected Password Reset Tokens Query**: `DB::table('password_reset_tokens')->whereIn('email', $protectedUserEmails)` dengan kolom stabil `['email']`.

---

## 10. Recommended Next Steps (Re-run TASK-018)

- Re-run TASK-018 rehearsal pada database clone `queens_english_reset_test` dengan verifier session yang telah diperbarui.
