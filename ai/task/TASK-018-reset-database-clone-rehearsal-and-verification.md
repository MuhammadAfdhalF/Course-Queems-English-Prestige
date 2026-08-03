# TASK-018 — Reset Database Clone Rehearsal & Verification Report

**Project Utama**: `D:\Kerja File\Freelance\E-Course Queens\Coding\Real\queens-english-prestige`  
**Workspace Rehearsal**: `D:\Kerja File\Freelance\E-Course Queens\Coding\Rehearsal\queens-english-prestige-reset-rehearsal`  
**Database Target Rehearsal**: `queens_english_reset_test`  
**Database Development Utama**: `queens_english_db`  
**Status Final TASK-018**: 
```text
TASK-018:
REHEARSAL FAILED — INVESTIGATION REQUIRED
```

---

## 1. Background & Goals

TASK-018 bertujuan untuk menjalankan pengujian rehearsal nyata atas Artisan Command reset yang telah diimplementasikan pada TASK-017:
1. **Reset 1 (`app:reset-pre-production`)**: Pembersihan seluruh data operasional student dan hirarki course master.
2. **Reset 2 (`app:reset-student-operations`)**: Pembersihan data operasional student dengan mempertahankan master course dan sertifikat template.

Seluruh eksekusi dilakukan dalam lingkungan terisolasi penuh:
- Workspace terpisah (clone repository di folder `Rehearsal`).
- Filesystem storage terpisah (physical copy `storage/app/public`).
- Database terpisah (`queens_english_reset_test`).
- Read-only protection penuh terhadap `queens_english_db` dan project utama.

---

## 2. Baseline Source Commit Information

- **Commit SHA**: `cf545ad5b0c95a6c1e30a597a7aed0bbba7c7f1a` (`cf545ad`)
- **Commit Message**: `feat: implement secure data reset command with file quarantine and verification services`
- **Active Branch**: `make_db`
- **Git Status Project Utama**: Clean (`0` uncommitted files).

---

## 3. Safety Preconditions & Isolation Proof

| Parameter | Main Workspace | Rehearsal Workspace | Status Isolation |
|---|---|---|:---:|
| **Project Root Path** | `D:\Kerja File\Freelance\E-Course Queens\Coding\Real\queens-english-prestige` | `D:\Kerja File\Freelance\E-Course Queens\Coding\Rehearsal\queens-english-prestige-reset-rehearsal` | **ISOLATED** |
| **Storage Root Path** | `.../Real/.../storage/app/public` | `.../Rehearsal/.../storage/app/public` | **ISOLATED (Physical Copy)** |
| **Active Environment** | `local` / `testing` | `reset-testing` | **ISOLATED** |
| **Active Database** | `queens_english_db` | `queens_english_reset_test` | **ISOLATED** |
| **Symlink / Junction** | None | None | **NO SYMLINK** |

---

## 4. Development Database Backup (`queens_english_db`)

Dump baseline `queens_english_db` berhasil dibuat sebelum rehearsal:
- **Backup File Path**: `D:\Database Backups\Queens English\queens_english_db_before_task018_20260803_214914.sql`
- **File Size**: `144,214 bytes`
- **SHA-256 Hash**: `bdad22950830c0acac15e9fe8cf4fa7388882477466add8d7b5c27c7510aeaf4`
- **Import Target**: `queens_english_reset_test`

---

## 5. Rehearsal Environment Verification (`.env.reset-testing`)

Konfigurasi `.env.reset-testing` dan `.env` pada rehearsal workspace:
- `APP_ENV=reset-testing`
- `DB_CONNECTION=mysql`
- `DB_DATABASE=queens_english_reset_test`
- `CACHE_STORE=database`
- `SESSION_DRIVER=database`
- `QUEUE_CONNECTION=database`
- `FILESYSTEM_DISK=public`

---

## 6. Reset 1 Execution Results (`app:reset-pre-production`)

### A. Dry-Run Result
- **Exit Code**: `0`
- **Mode**: `DRY-RUN (Simulation Only)`
- **Target Summary**: 30 tabel teridentifikasi, 0 data terhapus.
- **Protected Checksum**: `f24e56a40e52333e80d7550e2926e8528b5d591bd09cc32f4ea4a6aee93417ee`

### B. Execute Result
- **Maintenance Mode**: Activated (`php artisan down`)
- **Input Confirmation**: `RESET PRE PRODUCTION DATA`
- **Exit Code**: `1` (FAILED)
- **Error Output**:
  ```text
  RESET ERROR: Protected data verification failed! The following datasets were modified during reset: [sessions_non_student]. Transaction rolled back.
  ```

### C. Analysis Findings on Reset 1 Failure
- **Penyebab Root Cause**: Pada `ProtectedDataVerifier.php`, dataset `sessions_non_student` menghitung hash untuk seluruh baris tabel `sessions` di mana `user_id NOT IN ($studentIds)`. Karena `SESSION_DRIVER=database`, saat Artisan CLI mengeksekusi request reset, driver sesi Laravel secara otomatis memperbarui/menambahkan baris sesi guest/CLI (di mana `user_id` NULL) di tabel `sessions`.
- **Efek Verifikasi Guard**: `ProtectedDataVerifier` secara tepat mendeteksi bahwa checksum `sessions_non_student` sebelum dan sesudah deletion berbeda (karena perubahan `last_activity` & payload sesi runtime CLI).
- **Integritas Database**: `DB::rollBack()` berhasil dipicu 100%, seluruh transaksi database dibatalkan, dan data `queens_english_reset_test` tetap utuh.

---

## 7. Reset 2 Execution Results (`app:reset-student-operations`)

### A. Baseline Restore
Database `queens_english_reset_test` di-drop dan di-import ulang dari backup baseline `bdad2295...`. Filesystem storage rehearsal dikembalikan ke kondisi fisik awal.

### B. Dry-Run Result
- **Exit Code**: `0`
- **Mode**: `DRY-RUN (Simulation Only)`
- **Target Summary**: 16 tabel operasional teridentifikasi, 0 data terhapus.
- **Protected Checksum**: `f5c3e80dc3385e2085ce87228074d19483fe515952a1aa585332dd3e366d9519`

### C. Execute Result
- **Input Confirmation**: `RESET STUDENT OPERATIONS`
- **Exit Code**: `1` (FAILED)
- **Error Output**:
  ```text
  RESET ERROR: Protected data verification failed! The following datasets were modified during reset: [sessions_non_student]. Transaction rolled back.
  ```

---

## 8. Primary Development Verification (`queens_english_db` & Main Storage)

Pemeriksaan read-only akhir pada database development utama `queens_english_db` dan storage project utama:

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

- **`queens_english_db`**: **100% Intact / Tidak Tersentuh**.
- **Main Workspace Storage (`storage/app/public`)**: **100% Intact / Hash Identik dengan Manifest Awal**.
- **Maintenance Mode Main Workspace**: **OFF (Normal Operation)**.

---

## 9. Rollback & Recovery Verification

Karena `ProtectedDataVerifier` melempar `RuntimeException` sebelum `DB::commit()`, fitur keandalan transaksi TASK-017 terbukti bekerja sempurna:
1. **Database Rollback**: Tidak ada record yang terhapus secara parsial.
2. **File Quarantine Safeguard**: Tidak ada file sertifikat/foto yang dipindahkan ke karantina karena pemindahan file hanya dipanggil *post-commit*.

---

## 10. Recommended TASK-017 Revision (Bug Fix Plan)

Untuk mengatasi hambatan verifikasi `sessions_non_student` saat `SESSION_DRIVER=database`, diperlukan revisi minor pada `ProtectedDataVerifier.php`:

### Proposed Fix in `ProtectedDataVerifier.php`:
Filter `sessions_non_student` harus mengabaikan kolom runtime session `payload` dan `last_activity`, serta hanya mengabaikan perubahan baris sesi yang dibuat oleh CLI runner aktif, atau fokus pada kueri `sessions` milik Admin user eksplisit (`whereIn('user_id', $adminUserIds)`):

```php
// Revisi usulan pada TASK-017 Revision
$adminUserIds = DB::table('users')->where('role', '!=', 'student')->pluck('id')->toArray();
$adminSessionsQuery = DB::table('sessions')->whereIn('user_id', $adminUserIds);
```

---

## 11. Git Status Project Utama

- `git status --short`: Clean (Hanya untracked helper script di `scratch/` yang belum di-stage).
- Tidak ada commit otomatis yang dilakukan.

---

## 12. Conclusion & Recommended Next Task

- **Hasil TASK-018**: **REHEARSAL FAILED — INVESTIGATION REQUIRED**.
- **Safety Mechanism Result**: Success. Guard dan Verifier mencegah pembersihan database yang belum tervalidasi 100%.
- **Rekomendasi Task Berikutnya**: **TASK-017 Revision — Session Verifier Hardening for Database Session Driver**, dilanjutkan dengan re-run TASK-018 Rehearsal.
