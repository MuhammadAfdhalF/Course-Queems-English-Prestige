# TASK-009 — Course Builder Unified Admin UX Redesign

**Status**: IN_PROGRESS (Phase A & B Implemented & Tested)  
**Date Created**: 2026-07-25  
**Priority**: High  
**Author**: AI Pair Programmer (Antigravity)  

---

## 1. Background & Audit Summary

Saat ini, pengelolaan kursus pada Admin Panel (`Course Management`) berjalan secara bertingkat-tingkat melalui navigasi berbasis halaman terpisah (*full page reloads*):
`Course Programs` → `Course Levels` → `Course Modules` → `Module Materials` / `Module Practice` → `Practice Questions`, serta `Course Level` → `Final Exam Sections` → `Final Exam Questions`.

Berdasarkan audit arsitektur *read-only*, struktur hirarki ini menyebabkan penurunan produktivitas Admin secara drastis karena banyaknya perpindahan halaman, redundansi header konteks, fragmentasi posisi tombol aksi, serta hilangnya konteks scroll setelah operasi simpan/edit.

Untuk mengatasi permasalahan ini, disepakati perancangan ulang (redesign) UX Admin menjadi **Unified Course Builder** — satu antarmuka terpadu berbasis **Hybrid Architecture** (Server-rendered shell + Alpine.js/Fetch workspace) tanpa merusak rute lama, tanpa mengubah Student UI, dan **tanpa melakukan migration/perubahan skema database**.

---

## 2. Key Pain Points Existing System

1. **Kedalaman Navigasi Ekstrem (6-7 Level Deep)**: Admin harus berpindah hingga 6-7 halaman terpisah hanya untuk menambah 1 soal kuis pada modul tertentu.
2. **Redundansi Header Konteks**: Setiap halaman baru memuat ulang card header yang sama berisi judul Program/Level/Modul, menyita space vertikal hingga 35%.
3. **Scatter & Fragmentasi Action**: Tombol *Create*, *Edit*, *Manage Questions*, *Preview*, dan *Reviews* tersebar acak di tabel, dropdown, dan header halaman terpisah.
4. **Kehilangan Konteks Setelah Submit**: Setelah menyimpan materi/soal baru, sistem melakukan redirect yang membuat Admin kehilangan posisi scroll dan harus mencari ulang item target.
5. **Pengaturan `sort_order` Manual**: Admin harus mengetik angka urutan secara manual pada input text.
6. **Separasi Final Exam dari Modul Level**: Final Exam mengambang di tab terpisah, sehingga alur pembelajaran dari Modul 1..N hingga Final Exam tidak terlihat secara komprehensif.

---

## 3. Approved Architecture & Core Decisions

1. **Pendekatan Arsitektur HYBRID (Server-Rendered + Alpine.js/Fetch Workspace)**:
   - Initial Builder page dirender di server menggunakan Laravel Blade (`/admin/course-management/programs/{courseProgram}/builder`).
   - Tree Navigation (panel kiri) dan Workspace (panel kanan) dikontrol oleh `Alpine.js` + `fetch()` partial HTML dari server.
   - BUKAN Single Page Application (SPA) penuh; Controller, Service, Validation, Authorization, dan Route existing digunakan kembali.
   - Route Admin lama tetap **aktif 100%** sebagai fallback.
   - Student UI, completion, attempts, certificate, payment, dan order **TIDAK DIUBAH**.
2. **Deep-Linking & Browser History**:
   - Item terpilih disimpan di URL query parameter agar *page refresh* dan *browser back/forward* tetap aman:
     - `?level=1`
     - `?level=1&module=3&tab=materials`
     - `?level=1&exam=353&tab=questions`
3. **Penyimpanan State Expanded/Collapsed Tree**:
   - Node tree yang dibuka/ditutup disimpan pada `localStorage` browser (`course_builder_expanded_nodes`).

---

## 4. Tree Navigation Final Specification

Hierarki Tree Navigation pada Panel Kiri:
```text
Course Program
└── Course Level
    ├── Module
    │   ├── Materials (jumlah count)
    │   └── Practice (jumlah questions)
    └── Final Exam
        └── Final Exam Sections (bisa lebih dari satu section)
```

**Aturan Ketat Tree Navigation**:
- **Bisa Memiliki Banyak Final Exam Sections**: Sesuai skema database existing, satu Level dapat memiliki beberapa Final Exam Sections.
- **Questions TIDAK Ditampilkan di Tree**: Soal (*Questions*) **TIDAK** boleh di-render satu per satu di tree kiri untuk mencegah *tree bloat* dan query slow-down saat ada 100+ soal. Soal hanya ditampilkan di Workspace Panel kanan.
- **Payload Tree Ringan**: Tree hanya memuat metadata/count (`withCount`), BUKAN body question, rich text, atau file data.
- **Anti N+1 Eager Loading**: Menggunakan eager loading minimal terstruktur (`withCount(['materials', 'practices', 'questions'])`).

---

## 5. Workspace Panel & Form Placement Strategy

### A. Workspace Tab Design per Selected Node

| Node Terpilih | Tab Workspace yang Tersedia | Konten Utama Workspace |
|---|---|---|
| **Level Selected** | `Overview`, `Modules`, `Final Exam` | Overview Level, Ringkasan Modul & Exam, Tombol Add Module / Add Exam |
| **Module Selected** | `Overview`, `Materials`, `Practice` | Overview Modul, Daftar Materials, Ringkasan Practice |
| **Practice Selected** | `Overview`, `Questions`, `Attempts` | Setting Kuis, Daftar Questions (paginated), Attempt Reviews Link |
| **Final Exam Selected** | `Overview`, `Sections` | Setting Exam, Daftar Final Exam Sections |
| **Final Exam Section Selected** | `Overview`, `Questions`, `Attempts` | Setting Section, Daftar Questions (paginated), Attempt Reviews Link |

### B. Form UX Placement (Modal vs Side Drawer vs Full Page)

- **Course Level Create/Edit**: Wide Side Drawer.
- **Module Create/Edit**: Medium Drawer atau Modal.
- **Material Create/Edit**: Wide Side Drawer (karena memuat Rich-Text Editor TinyMCE/CKEditor & File Uploader).
- **Practice Configuration**: Inline Workspace atau Medium Drawer.
- **Practice Question Create/Edit**: Wide Side Drawer.
- **Final Exam Section Create/Edit**: Medium/Wide Drawer.
- **Final Exam Question Create/Edit**: Wide Side Drawer.
- **Attempt Reviews & Manual Grading**: Dedicated Workspace Tab atau Full Page (karena membutuhkan analisis lembar jawaban siswa).

*Catatan*: Semua form tetap memanfaatkan validation backend, CSRF, Route-Model Binding, Authorization, dan Upload Validation existing. Jika pengguna mencoba menutup drawer yang memiliki perubahan belum disimpan, sistem menampilkan konfirmasi *Unsaved Changes*. Form lama tetap dapat diakses sebagai fallback.

---

## 6. Reorder Engine Strategy

Entity yang mendukung Drag-and-Drop Reorder:
1. `Course Program` (Global)
2. `Course Level` (Scoped per Program)
3. `Module` (Scoped per Level)
4. `Material` (Scoped per Module)
5. `Practice Question` (Scoped per Practice)
6. `Final Exam Section` (Scoped per Level)
7. `Final Exam Question` (Scoped per Final Exam Section)

**Aturan Reorder Engine**:
- **Auto-Position Item Baru**: Item baru otomatis mendapatkan `sort_order = MAX(sort_order) + 1`. Input angka `sort_order` disembunyikan dari form utama.
- **Parent Scope Constraint**: Drag-and-drop hanya diperbolehkan di dalam parent yang sama (misal: antar-Modul di Level yang sama). Drag antar-Level/Modul dilarang.
- **Sticky Unsaved Changes Bar**: Drag tidak langsung melakukan auto-save per gerakan. Setelah drag, muncul sticky bar di bawah dengan tombol **Save Order** dan **Discard**.
- **Backend Validation & Transaction**: Endpoint reorder menerima array ID ter-scope (`order[]`), memverifikasi kepemilikan parent, dan meng-update `sort_order` ter-normalisasi ($1, 2, 3, \dots$) di dalam `DB::transaction`.
- **Mobile Touch Fallback**: Menyediakan tombol **Move Up** dan **Move Down** di samping item pada perangkat mobile.
- **Unsaved Reorder Guard**: Mencegah perpindahan node jika ada perubahan urutan yang belum disimpan tanpa konfirmasi Admin.

---

## 7. Active & Readiness Constraints

- **TIDAK Boleh Mengubah Lifecycle Business Existing**: Phase awal **TIDAK BOLEH** mengubah status database menjadi Draft/Ready/Incomplete, tidak menambah auto-deactivation baru, dan tidak mengubah `StudentCourseEnrollment` / `CertificateService`.
- **Informational Readiness Badge**: Badge `Ready` / `Incomplete` (jika ditampilkan di UI) hanya bersifat kalkulasi runtime (misal: Modul tanpa materi diberi label *Incomplete*), bukan pengubah kolom database.
- **Isolasi Fitur Score TASK-008**: Logika scoring / passing grade dari `TASK-008` bersifat terpisah dan tidak boleh diterapkan di Course Builder sebelum `TASK-008` disetujui.

---

## 8. Action UX Matrix

- **Course Programs**: Primary: *Open Builder*. Secondary (`...` menu): *Edit*, *Activate/Deactivate*, *Delete*, *Drag Reorder*.
- **Level**: Primary: *Add Module*, *Add Final Exam Section*, *Preview*. Secondary: *Edit*, *Activate/Deactivate*, *Delete*.
- **Module**: Primary: *Add Material*, *Configure Practice*, *Preview*. Secondary: *Edit*, *Activate/Deactivate*, *Delete*.
- **Practice**: Primary: *Add Question*, *Preview*, *Review Attempts*. Secondary: *Edit Config*.
- **Final Exam Section**: Primary: *Add Question*, *Preview*, *Review Attempts*. Secondary: *Edit/Activate/Delete*.
- *Out of Scope*: Fitur Duplicate / Clone item.

---

## 9. Backward Compatibility & Security

### A. Backward Compatibility
- Rute Admin lama (`/admin/course-management/...`) tetap aktif 100%.
- Tidak ada redirect paksa pada phase awal. Halaman lama dapat diberi banner tautan ke Builder.
- Student routes, completion, attempts, dan certificate tetap bekerja tanpa perubahan.

### B. Security & Validation Checklist
- **Parent-Child Ownership Verification**: Memastikan `course_level_id` / `module_id` pada request reorder atau update benar-benar milik parent target.
- **Cross-Program ID Injection Protection**: Mencegah pengiriman ID Modul dari Program B ke Builder Program A.
- **Delete Guard Protection**: Mencegah penghapusan `ModulePractice` / `FinalExam` yang sudah memiliki `StudentAttempt` untuk menjaga integritas data siswa.
- **CSRF & Upload Validation**: Memastikan validasi file MIME type & size tetap berjalan pada Side Drawer upload.

---

## 10. Scope & Out of Scope

### Scope
- Pembuatan shell Course Builder utama dan API partial rendering workspace.
- Pengintegrasian Side Drawer untuk form Level, Module, Material, Practice Question, dan Final Exam Question.
- Pengimplementasian Unified Reorder Engine dengan Sticky Bar & Drag-and-Drop.
- Penyediaan Mobile Responsive Drawer untuk Tree Navigation.

### Out of Scope
- Perubahan skema database atau migrasi baru.
- Perubahan pada Student UI atau alur pengerjaan kuis siswa.
- Fitur Duplicate / Copy Module & Question.
- Penerapan engine scoring baru dari `TASK-008` (sebelum TASK-008 dirilis).

---

## 11. Implementation Phases & File Impact

### Phase A — Builder Shell & Read-Only Navigation
- **Scope**: Membuat rute `/programs/{courseProgram}/builder`, controller shell `CourseBuilderController`, tree navigation (Level, Module, Practice metadata, Final Exam Sections count), workspace overview read-only, dan deep-link URL query parameters. Rute lama tetap 100%.
- **Files Terdampak**:
  - `[NEW]` `app/Http/Controllers/Admin/CourseManagement/CourseBuilderController.php`
  - `[NEW]` `resources/views/pages/admin/course-management/builder/index.blade.php`
  - `[NEW]` `resources/views/partials/admin/course-management/builder/tree.blade.php`
  - `[NEW]` `resources/views/partials/admin/course-management/builder/workspace.blade.php`
  - `[MODIFY]` `routes/web.php`
  - `[MODIFY]` `resources/views/pages/admin/course-management/programs/index.blade.php` (tambah tombol *Open Builder*)
- **Risiko**: Sangat Rendah.
- **Acceptance Criteria**: Admin dapat membuka Builder dan bernavigasi antar-node via tree & URL tanpa reload penuh.
- **Rollback Plan**: Revert commit Phase A; rute lama tetap berjalan 100%.

### Phase B — Level & Module Management (Drawer Forms)
- **Scope**: Mengintegrasikan form Create/Edit Level & Module ke dalam Side Drawer / Modal di dalam Builder.
- **Files Terdampak**:
  - `[NEW]` `resources/views/partials/admin/course-management/builder/drawers/level-drawer.blade.php`
  - `[NEW]` `resources/views/partials/admin/course-management/builder/drawers/module-drawer.blade.php`
  - `[MODIFY]` `app/Http/Controllers/Admin/CourseManagement/CourseLevelController.php`
  - `[MODIFY]` `app/Http/Controllers/Admin/CourseManagement/ModuleController.php`
- **Risiko**: Rendah.
- **Acceptance Criteria**: Admin dapat membuat dan mengedit Level/Modul dari dalam Builder. Validation error tertangkap di drawer.

### Phase C — Materials Workspace & Upload Drawer
- **Scope**: Workspace tab Materials, upload video/PDF/audio via Side Drawer, preview materi, pagination materi.
- **Files Terdampak**:
  - `[NEW]` `resources/views/partials/admin/course-management/builder/drawers/material-drawer.blade.php`
  - `[MODIFY]` `app/Http/Controllers/Admin/CourseManagement/ModuleMaterialController.php`
- **Risiko**: Rendah-Sedang.
- **Acceptance Criteria**: Material dapat diunggah, diedit, dan dipreview langsung dari Builder.

### Phase D — Module Practice & Question Management
- **Scope**: Configuration workspace untuk Practice, Question Drawer (Multiple Choice/Essay), pagination soal.
- **Files Terdampak**:
  - `[NEW]` `resources/views/partials/admin/course-management/builder/drawers/practice-question-drawer.blade.php`
  - `[MODIFY]` `app/Http/Controllers/Admin/CourseManagement/ModulePracticeController.php`
  - `[MODIFY]` `app/Http/Controllers/Admin/CourseManagement/ModulePracticeQuestionController.php`
- **Risiko**: Sedang.
- **Acceptance Criteria**: Pengelolaan soal kuis modul berjalan lancar di workspace & drawer.

### Phase E — Final Exam Sections & Questions Integration
- **Scope**: Mendukung multiple Final Exam Sections per Level, workspace section, question drawer, tautan attempt reviews.
- **Files Terdampak**:
  - `[NEW]` `resources/views/partials/admin/course-management/builder/drawers/final-exam-question-drawer.blade.php`
  - `[MODIFY]` `app/Http/Controllers/Admin/CourseManagement/FinalExamController.php`
  - `[MODIFY]` `app/Http/Controllers/Admin/CourseManagement/FinalExamQuestionController.php`
- **Risiko**: Sedang.
- **Acceptance Criteria**: Final Exam Sections dan soal-soalnya terkelola terpadu di Builder.

### Phase F — Unified Reorder Engine
- **Scope**: Endpoint reorder ter-scope untuk Program, Level, Module, Material, Practice Question, Final Exam Section, dan Final Exam Question. Sticky Unsaved Changes Bar.
- **Files Terdampak**:
  - `[MODIFY]` Controllers terkait (ditambah method `reorder`).
  - `[MODIFY]` `routes/web.php`
  - `[NEW]` `resources/views/partials/admin/course-management/builder/reorder-bar.blade.php`
- **Risiko**: Sedang.
- **Acceptance Criteria**: Reorder drag & drop serta tombol Up/Down menyimpan `sort_order` baru secara atomik.

### Phase G — Responsive, Mobile Off-Canvas Drawer & UX Polish
- **Scope**: Mobile tree off-canvas drawer, tombol Move Up/Down mobile, unsaved changes guard, keyboard navigation.
- **Files Terdampak**:
  - `[MODIFY]` View partials Builder & Alpine JS state components.
- **Risiko**: Rendah.
- **Acceptance Criteria**: Builder nyaman digunakan di perangkat mobile dan tablet.

### Phase H — Regression Testing & Optional Banner Links
- **Scope**: Testing rute lama, testing Student UI flow, banner opsional pengarah ke Builder pada halaman lama.
- **Files Terdampak**:
  - Blade views halaman lama (`programs/index`, `levels/index`, `modules/index`).
- **Risiko**: Sangat Rendah.
- **Acceptance Criteria**: Seluruh sistem terverifikasi bebas regresi.

---

## 12. Testing Checklist

- [ ] **Phase A**: Buka `/admin/course-management/programs/1/builder`. Pastikan Tree Navigation memuat Level, Modul, dan Exam Sections tanpa slow-down.
- [ ] **Phase A**: Klik node Modul 1. Pastikan URL berubah menjadi `?level=1&module=1` dan workspace memperbarui konten secara asinkron.
- [ ] **Phase B**: Buka Drawer Create Level, isi data, dan tekan Save. Pastikan Level baru muncul di Tree dan Drawer tertutup.
- [ ] **Phase C**: Unggah materi Video MP4 via Material Drawer. Pastikan file tersimpan di storage public dan preview video dapat diputar.
- [ ] **Phase D**: Tambah 3 soal pilihan ganda di Practice Question Drawer. Pastikan kunci jawaban & poin score tersimpan.
- [ ] **Phase E**: Tambah Final Exam Section baru pada Level 1, lalu tambahkan 2 soal exam. Pastikan ter-render di workspace.
- [ ] **Phase F**: Lakukan drag reorder pada daftar Modul. Pastikan Sticky Bar *Unsaved Changes* muncul. Klik *Save Order* dan pastikan urutan tersimpan di DB.
- [ ] **Phase G**: Akses Builder via HP (screen width 375px). Pastikan Tree Navigation dapat dibuka via tombol hamburger off-canvas drawer.
- [ ] **Phase H**: Akses rute lama `/admin/course-management/levels/1/modules`. Pastikan halaman lama tetap berfungsi 100% tanpa error.
- [ ] **Phase H**: Login sebagai Student, kerjakan kuis & exam. Pastikan alur siswa tidak mengalami regresi.

---

## 13. Remaining Technical Questions (Unresolved Decisions)

1. **Format Default Video Poster / Thumbnail**: Apakah upload thumbnail level di Side Drawer memerlukan cropping tool otomatis atau cukup standar image uploader?
2. **Batas Maksimal Item per Pagination Workspace**: Berapa batas default pagination untuk daftar Questions & Materials di workspace (misal: 10 atau 15 item per halaman)?

---

## 14. Implementation Result
*(Diisi setelah implementasi dilakukan)*

## 15. Files Changed
*(Diisi setelah implementasi dilakukan)*

## 16. Remaining Notes
*(Diisi setelah testing dilakukan)*
