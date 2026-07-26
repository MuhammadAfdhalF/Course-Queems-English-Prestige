# TASK-015 — Dynamic Certificate Course Identity & Configurable Score Label

## Executive Summary
Audit and implementation of dynamic course identity presentation (`{Course Program} — {Course Level}`) and configurable score label heading (`certificate_score_label`) for student completion certificates in Queens English Prestige.

---

## 1. Background & Audit Findings
- **Previous Issue**:
  - Certificates hardcoded the score heading as `TOEFL Prediction Score:`, causing all non-TOEFL courses (e.g., General English) to display a TOEFL label.
  - Completion text only displayed the Course Level name (`for the completion of tes`), obscuring the parent Course Program identity.
- **Audit Findings**:
  - Found hardcoded `TOEFL Prediction Score:` in 3 certificate rendering views:
    - PDF Renderer: `resources/views/pdf/certificate.blade.php`
    - Student Web View: `resources/views/pages/student/certificate-show.blade.php`
    - Admin Preview Web View: `resources/views/pages/admin/course-management/certificates/show.blade.php`
  - Confirmed `Certificate` model reads `courseLevel` & `courseProgram` dynamically via Eloquent relationships.

---

## 2. Schema Changes
Added column `certificate_score_label` to `course_levels` table:
- Migration: `2026_07_26_100005_add_certificate_score_label_to_course_levels_table.php`
- Schema: `VARCHAR(100) NULLABLE`
- Fillable: Added to `App\Models\CourseLevel::$fillable`.

---

## 3. Business Logic & Presentation Service

Created `App\Services\CertificatePresentationService`:

### A. Course Display Name Rules (`courseDisplayName`)
1. Program & Level present and different: `{Program Name} — {Level Name}` (e.g. `General English — Basic 1`).
2. Program & Level have same name (case-insensitive after trim): `{Program Name}` (rendered once).
3. Level empty: `{Program Name}`.
4. Program empty: `{Level Name}`.
5. Both empty: `Course Completion`.

### B. Score Label Heading Rules (`scoreLabel` & `normalizeScoreLabel`)
1. `certificate_score_label` set: Render `{Normalized Label}:`.
2. `certificate_score_label` null / empty: Fallback to `Final Score:`.
3. Server-side normalization:
   - Strips HTML tags (`strip_tags`).
   - Trims whitespace.
   - Strips trailing colons (`:`) to prevent double-colon rendering (`::`).
   - Limits length to 100 characters max via validation.

---

## 4. Admin UI Integration
- **Unified Course Builder Drawer**: Added `Certificate Score Label` text input under "Certificate Configuration" in Level drawer. Form payload automatically sends `certificate_score_label`.
- **Legacy Level Forms**: Added `Certificate Score Label` field in `resources/views/partials/admin/course-management/levels/form.blade.php`.
- **Controllers**: Updated `CourseLevelController@store` and `CourseLevelController@update` with validation rules and normalization.

---

## 5. Certificate Immutability Audit
- **Current Architecture**: The `certificates` table references `course_level_id`. Certificate views render the course display name and score label dynamically from the associated master `CourseLevel` & `CourseProgram`.
- **Immutability Note**: If an Admin alters the `name` or `certificate_score_label` of a `CourseLevel` after certificates have been issued, existing certificates will reflect the updated master label when re-rendered or downloaded as PDF.
- **Recommendation**: In a future task, if strict historical immutability is required for issued certificates, snapshot columns (`course_display_name_snapshot`, `score_label_snapshot`) can be added to the `certificates` table.

---

## 6. Verification & Test Results

### A. Automated Feature Tests (`queens_english_test`)
Ran full test suite including `DynamicCertificateCourseIdentityTest`:
```text
  Tests:    77 passed (268 assertions)
  Duration: 13.15s
```
**Seluruh 77 test cases LULUS 100%.**

### B. Development DB Safety (`queens_english_db`)
Verified zero data corruption or data loss on development DB:
- `users`: 5
- `course_programs`: 3
- `course_levels`: 5
- `modules`: 6
- `module_materials`: 16
- `module_practices`: 5
- `final_exams`: 7
- `migrations`: 62 (Includes new migration `2026_07_26_100005`)

---

## TASK-015 Status
**COMPLETED — IMPLEMENTED & TESTED 100%**
