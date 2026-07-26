# INCIDENT-001 — Test Database Data Loss & Isolation Failure

**Date of Incident**: 2026-07-26  
**Status**: RESOLVED — DATA RESTORED & TEST DATABASE ISOLATED (2026-07-26)  
**Impact**: High (Development database wiped during test execution, now 100% restored)  
**Root Cause**: Lack of dedicated test database isolation (`.env.testing` missing, `phpunit.xml` DB overrides commented out) combined with `RefreshDatabase` trait usage in `PublicCourseModulePreviewTest.php`.

---

## 1. Summary of Incident

During verification of TASK-011 (Public Course Module Preview), `php artisan test` was executed. Because `phpunit.xml` did not specify a dedicated testing database and `.env.testing` was missing, PHPUnit connected directly to the primary MySQL development database (`queens_english_db`). When `PublicCourseModulePreviewTest.php` ran using the `RefreshDatabase` trait, Laravel executed `php artisan migrate:fresh` on `queens_english_db`, wiping all development data.

---

## 2. Root Cause Analysis

1. **Environment Configuration Gap**: `.env.testing` did not exist in project root. `phpunit.xml` lines 25–26 (`DB_CONNECTION` sqlite, `DB_DATABASE` :memory:) were commented out.
2. **Implicit Destructive Trait**: `PublicCourseModulePreviewTest` used `use RefreshDatabase;` whereas prior feature tests used `use DatabaseTransactions;`.
3. **Execution Path**: Running `php artisan test` triggered `RefreshDatabase::refreshTestDatabase()` $\rightarrow$ `Artisan::call('migrate:fresh')` against the active MySQL connection `queens_english_db`.

---

## 3. Recovery & Prevention Controls Applied

1. **Backup Verification & Hash Lock**:
   - Original backup copied to `D:\Database Backups\Queens English\queens_english_db_backup_2026_07_26_154452.sql`.
   - SHA-256 hash verified (100% match: `864834c66597c055584d9e128613c29bf3f169773a08631426df01efdbe3bfe1`).

2. **Verified Recovery Database & Option A Restore**:
   - Created `queens_english_recovery` database and imported backup.
   - Applied all 61 migrations with backfilling for legacy snapshot columns.
   - Verified 100% data integrity (5 users, 3 programs, 4 levels, 5 modules, 15 materials, 10 orders, 7 enrollments, 4 certificates).
   - Created verified dump `queens_english_recovery_verified_2026_07_26.sql` (SHA-256: `0ce9cc8eff00c7f3c3bb56ae5d7ee9cb7e15518dbaa5b5f8719e7da6ca4acc58`).
   - Created pre-restore wiped backup `queens_english_db_wiped_before_restore_2026_07_26.sql`.
   - Executed **Option A Restore** into primary database `queens_english_db`.

3. **Isolated Test Database & Safety Guard**:
   - Created dedicated MySQL testing database `queens_english_test`.
   - Created `.env.testing` and `.env.testing.example` pointing to `queens_english_test`.
   - Added `.env.testing` to `.gitignore`.
   - Updated `phpunit.xml` to lock `DB_CONNECTION=mysql` and `DB_DATABASE=queens_english_test`.
   - Implemented **Critical Safety Guard** in `tests/TestCase.php` that immediately throws a `RuntimeException` if `DB_DATABASE` is `queens_english_db` or does not end with `_test`.

---

## 4. Verification

- Verified `queens_english_db` contains all 5 users, 3 programs, 4 levels, 5 modules, 15 materials, 10 orders, 7 enrollments, 4 certificates.
- Ran `PublicCourseModulePreviewTest` and full test suite (`58/58 passed`).
- Verified `queens_english_db` and `queens_english_recovery` remained 100% untouched during test execution.

---

## 5. Lessons Learned & Policy Rules

- Never run `php artisan test` without explicit test database isolation (`*_test`).
- `tests/TestCase.php` must always enforce the Safety Guard check before running any test setup.
