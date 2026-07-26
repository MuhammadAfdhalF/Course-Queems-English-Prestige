# TASK-013 — Question Score Allocation Modal Guidance

**Status**: IN PROGRESS  
**Date Created**: 2026-07-26  
**Priority**: High  
**Author**: AI Pair Programmer (Antigravity)  

---

## 1. Executive Summary

TASK-013 introduces Real-Time Score Allocation Guidance inside the Add/Edit Question drawers for both **Module Practice** and **Final Exam Section** in the Unified Course Builder.

Previously, opening the `Add Question` modal defaulted the `Score / Points` input field to `10`, regardless of the remaining score available in the parent assessment. If the parent assessment had a remaining score of `8`, entering `10` caused an over-allocation error (`HTTP 422`), forcing the Admin to guess or manually calculate remaining allocation beforehand.

This task adds:
1. **Parent Allocation Context in Drawer Payload**: Exposing `total_score`, `allocated_score`, and `remaining_score` when opening question drawers.
2. **Score Allocation Summary Panel**: Displaying `Total Score`, `Allocated Score`, and `Remaining Score` directly above the Score input field.
3. **Smart Default & `Use Remaining` Action Button**: Defaulting the score input to empty (instead of `10`), with a `type="button"` action to auto-fill the maximum available score.
4. **Edit Mode Recalculation**: Calculating maximum available score as `max_available_score = remaining_score + current_question_score`.
5. **Real-Time Prospective Preview & Save Guard**: Rendering real-time prospective allocation status (Incomplete / Ready / Over Allocation) and disabling client-side submission with clear error messaging when over-allocated.
6. **Zero Remaining Score Warning**: Guiding Admins when score allocation is complete (0 remaining).

---

## 2. Pre-Implementation Audit Findings

### Root Cause of Default Score `10`
In `resources/js/course-builder.js`:
- `openCreateQuestionDrawer()` set `this.drawerData.score = 10;`.
- `openCreateFinalExamQuestionDrawer()` set `this.drawerData.score = 10;`.

### Source Files & Allocation Context
- `app/Http/Controllers/Admin/CourseManagement/ModulePracticeQuestionController.php`:
  `builderCreate` endpoint loads `ModulePractice` and can return `total_score`, `allocated_score`, `remaining_score`.
- `app/Http/Controllers/Admin/CourseManagement/FinalExamQuestionController.php`:
  `builderCreate` endpoint loads `FinalExam` and can return `total_score`, `allocated_score`, `remaining_score`.
- `resources/js/course-builder.js`:
  Manages Alpine.js drawer state, reactive score calculation, and modal submission.
- `resources/views/partials/admin/course-management/builder/drawer.blade.php`:
  Renders question drawer templates and score allocation summary UI.

---

## 3. Allocation Formulas

### Add Question
$$\text{max\_available\_score} = \text{remaining\_score}$$
$$\text{prospective\_allocated} = \begin{cases} \text{allocated\_score} + \text{input\_score}, & \text{if question is active} \\ \text{allocated\_score}, & \text{if question is inactive} \end{cases}$$

### Edit Question
$$\text{max\_available\_score} = \text{remaining\_score} + \text{current\_question\_score\_if\_was\_active}$$
$$\text{prospective\_allocated} = \text{allocated\_score} - \text{old\_active\_score} + \text{new\_active\_score}$$

---

## 4. Implementation Checklist

- [ ] **Phase 1 — Pre-Implementation Audit & Endpoint Allocation Context Expansion**
- [ ] **Phase 2 — Generic Allocation Helper & Reactive Alpine.js Store**
- [ ] **Phase 3 — Allocation Summary Panel & `Use Remaining` Button in Drawer Template**
- [ ] **Phase 4 — Real-Time Prospective Allocation Preview & Client-Side Save Guard**
- [ ] **Phase 5 — Parity Verification for Module Practice & Final Exam**
- [ ] **Phase 6 — Testing, Build & DB Verification**
