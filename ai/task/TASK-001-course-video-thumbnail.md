# TASK-001 — Course Thumbnail Ketika Menggunakan Video

## Status

AUDITING

## User Request

Pada fitur course terdapat thumbnail ketika menggunakan video. Implementasi tersebut sebelumnya pernah ada atau pernah dihapus. Saya ingin mengetahui kondisi kode aktual terlebih dahulu sebelum menentukan perubahan.

## Objective

Memastikan thumbnail course berjenis video (`thumbnail_type = 'video'`) dapat tampil dan ter-render dengan benar sebagai elemen video pada Public Website (Catalog Card Grid & Home Page) dan Student Portal (My Course Card), tanpa merusak thumbnail berjenis gambar (`thumbnail_type = 'image'`) serta tanpa melakukan perubahan skema database atau admin controller existing.

## Current Behavior

1. **Database & Model**: Tabel `course_levels` dan model `CourseLevel` sudah memiliki kolom `thumbnail_type` (`enum('image', 'video')`) dan `thumbnail_file` (`string`).
2. **Admin Panel**:
   * Admin Controller (`CourseLevelController`) sudah mendukung validasi upload file gambar (`jpg,jpeg,png,webp`, max 4MB) dan video (`mp4,webm,mov`, max 20MB).
   * Form Admin (`partials/admin/course-management/levels/form.blade.php`) menyediakan opsi dropdown `Thumbnail Type` (`image` / `video`) dan preview thumbnail (menggunakan `<img ...>` untuk gambar dan `<video ...>` untuk video).
   * Tabel Admin (`partials/admin/course-management/levels/table.blade.php`) sudah menampilkan indikator icon play untuk tipe video.
3. **Public Detail Course** (`partials/public/course-detail/sidebar-card.blade.php`): Sudah mendukung pengecekan `thumbnail_type === 'video'` dan me-render elemen `<video controls>`.
4. **Public Catalog Card Grid & Home Page** (`components/public/course-grid.blade.php` & `partials/public/home/programs.blade.php`):
   * **Bermasalah**: Mengabaikan `thumbnail_type` dan selalu memaksakan URL file video ke dalam atribut `src` elemen `<img src="...">`.
   * Akibatnya, browser gagal memuat file `.mp4` sebagai gambar, memicu handler `onerror`, dan mengganti tampilan thumbnail dengan gambar placeholder fallback (`https://placehold.co/...`).
5. **Student Portal** (`MyCourseController.php` & `components/student/my-course-card.blade.php`):
   * **Bermasalah**: `MyCourseController` hanya meneruskan atribut `image` tanpa menyertakan `thumbnail_type`.
   * Component `my-course-card.blade.php` selalu me-render elemen `<img>` sehingga thumbnail video tidak dapat terputar atau tampil sebagai broken image/fallback.

## Expected Behavior

1. Jika `thumbnail_type === 'image'`, komponen card di Public Website dan Student Portal me-render elemen `<img>` dengan gambar dari `storage`.
2. Jika `thumbnail_type === 'video'`, komponen card di Public Website dan Student Portal me-render elemen `<video>` (dengan atribut pendukung seperti `autoplay loop muted playsinline` atau `controls` sesuai desain UI) agar video thumbnail dapat terputar dengan lancar.
3. Jika file thumbnail kosong, sistem menampilkan fallback placeholder image secara aman.

## Audit Findings

1. **Skema Database**:
   * Migration `2026_05_01_141648_create_course_levels_table.php`:
     * `$table->enum('thumbnail_type', ['image', 'video'])->nullable();`
     * `$table->string('thumbnail_file')->nullable();`
   * **Kesimpulan**: Struktur database **SUDAH LENGKAP** dan **TIDAK MEMERLUKAN MIGRASI BARU**.

2. **Model & Controller Admin**:
   * Model `CourseLevel.php` sudah mendaftarkan `thumbnail_type` dan `thumbnail_file` di `$fillable`.
   * `CourseLevelController.php` pada method `store()` dan `update()` sudah memiliki aturan validasi terpisah untuk file image dan video.
   * **Kesimpulan**: Controller Admin dan Model **SUDAH 100% BENAR** dan tidak perlu diubah.

3. **View Component Publik & Student**:
   * `components/public/course-grid.blade.php`: Menerima array/object course tetapi tidak membaca `thumbnail_type`.
   * `partials/public/home/programs.blade.php`: Membuat array course item tanpa memasukkan data `thumbnail_type`.
   * `app/Http/Controllers/Student/MyCourseController.php`: Mapper data course enrollment & order tidak memasukkan `thumbnail_type`.
   * `components/student/my-course-card.blade.php`: Hanya memiliki markup `<img>` tanpa pengkondisian `@if ($thumbnailType === 'video')`.

4. **Historis Kode**:
   * Terdapat migrasi `2026_05_12_162226_drop_opening_media_columns_from_modules_table.php` yang dulu menghapus kolom media pada tabel `modules` (bukan `course_levels`).
   * Pada level kursus (`course_levels`), fitur thumbnail video sebetulnya sudah setengah terimplementasi (tersimpan di DB, form admin, dan detail sidebar), namun lupa/belum diselesaikan pada komponen card grid publik dan student portal.

## Files Involved

- `app/Http/Controllers/Student/MyCourseController.php` (Read & add `thumbnail_type` to mapped payload)
- `resources/views/partials/public/home/programs.blade.php` (Pass `thumbnail_type` to course grid)
- `resources/views/components/public/course-grid.blade.php` (Render `<video>` vs `<img>` conditionally)
- `resources/views/components/student/my-course-card.blade.php` (Render `<video>` vs `<img>` conditionally)

## Root Cause

Akar masalah berasal dari **kelalaian penanganan tipe media pada Blade View Components** (`course-grid.blade.php` dan `my-course-card.blade.php`) serta tidak diteruskannya atribut `thumbnail_type` dari controller/view presenter ke komponen tersebut. Database, model, dan admin panel sebenarnya sudah siap dan berfungsi dengan baik.

## Scope

- Memperbarui komponen `components/public/course-grid.blade.php` agar mendukung rendering `<video>` saat `thumbnail_type === 'video'`.
- Memperbarui `partials/public/home/programs.blade.php` agar meneruskan atribut `thumbnail_type`.
- Memperbarui `app/Http/Controllers/Student/MyCourseController.php` agar menyertakan `thumbnail_type` pada data array yang dikirim ke view.
- Memperbarui `components/student/my-course-card.blade.php` agar mendukung rendering `<video>` saat `thumbnail_type === 'video'`.

## Out of Scope

- Menambah atau mengedit kolom migrasi database (`course_levels` atau tabel lainnya).
- Mengubah logika simpan/update di `CourseLevelController.php`.
- Mengubah fungsionalitas admin panel.
- Pengerjaan task lain (WhatsApp template, Email notification, Certificate layout).

## Proposed Solution

### Opsi 1 (Rekomendasi Minimal & Paling Aman)
* **Deskripsi**: Tanpa mengubah database atau controller admin. Cukup sesuaikan Data Transfer pada `home/programs.blade.php` & `MyCourseController.php` untuk menyertakan `thumbnail_type`, lalu tambahkan pengkondisian `@if ($thumbnailType === 'video') <video ...> @else <img ...> @endif` di dalam komponen `course-grid.blade.php` dan `my-course-card.blade.php`.
* **Kelebihan**: Solusi sangat terarah, perubahan kode minimal (< 20 baris total), 0 risiko pada database/migration, dan langsung menyelesaikan masalah di tampilan publik & student.

### Opsi 2 (Perubahan Struktur Media / Terpisah Image & Video)
* **Deskripsi**: Menambah kolom baru `video_url` / `poster_image` via migrasi baru agar video memiliki gambar poster khusus ketika belum diputar.
* **Kekurangan**: Memerlukan migrasi database, merubah form admin, dan menambah kompleksitas yang tidak diminta.

**Rekomendasi**: Pilih **Opsi 1** karena struktur database existing sudah memiliki `thumbnail_type` (`image`/`video`) dan `thumbnail_file` yang bekerja sempurna di admin.

## Risks

- **Risiko Performa/Autoplay Video**: Video file ukuran besar (hingga 20MB) pada card grid jika diputar secara bersamaan dapat membebani bandwidth client.
- **Mitigasi**: Gunakan atribut HTML5 `<video muted loop playsinline preload="metadata">` atau berikan controls ringkas / preview hover.

## Implementation Plan

1. **[MODIFY] [MyCourseController.php](file:///d:/Kerja%20File/Freelance/E-Course%20Queens/Coding/Real/queens-english-prestige/app/Http/Controllers/Student/MyCourseController.php)**: Tambahkan `'thumbnailType' => $courseLevel?->thumbnail_type ?? 'image'` pada array item kursus.
2. **[MODIFY] [programs.blade.php](file:///d:/Kerja%20File/Freelance/E-Course%20Queens/Coding/Real/queens-english-prestige/resources/views/partials/public/home/programs.blade.php)**: Sertakan `'thumbnail_type' => $courseLevel->thumbnail_type ?? 'image'` pada array `$courseItems`.
3. **[MODIFY] [course-grid.blade.php](file:///d:/Kerja%20File/Freelance/E-Course%20Queens/Coding/Real/queens-english-prestige/resources/views/components/public/course-grid.blade.php)**: Tambahkan pengecekan `$type === 'video'` untuk me-render `<video src="..." muted loop playsinline autoplay>` atau `<img>`.
4. **[MODIFY] [my-course-card.blade.php](file:///d:/Kerja%20File/Freelance/E-Course%20Queens/Coding/Real/queens-english-prestige/resources/views/components/student/my-course-card.blade.php)**: Tambahkan prop `:thumbnail-type` dan render `<video>` jika tipe media adalah video.

## Testing Checklist

- [ ] Upload course level baru dengan thumbnail `image` di Admin Panel -> Pastikan tampil normal di Admin, Public, dan Student.
- [ ] Upload course level baru dengan thumbnail `video` (`.mp4`) di Admin Panel -> Pastikan tampil & terputar sebagai video pada Public Course Catalog (`/courses`).
- [ ] Pastikan video thumbnail tampil pada Home Page (`/`).
- [ ] Pastikan video thumbnail tampil pada Student Portal My Courses (`/student/my-courses`).
- [ ] Pastikan course tanpa thumbnail menampilkan image fallback placeholder dengan aman.
- [ ] Verifikasi tidak ada error sintaks atau breakdown layout UI pada browser.

## Requirement Clarification Audit

### 1. Temuan Git History
* **Commit Pertama (`909a00b` - 1 Mei 2026)**:
  Tabel `course_levels` sejak awal didefinisikan hanya mempunyai 2 kolom media:
  * `thumbnail_type`: `enum('image', 'video')`
  * `thumbnail_file`: `string`
* **Pemeriksaan Field Historis**:
  * **TIDAK DITEMUKAN** history commit yang pernah menambahkan kolom terpisah seperti `poster`, `video_thumbnail`, `preview_image`, atau `video_url` pada tabel `course_levels`.
  * **Ditemukan** hapus media pada tabel `modules` (`2026_05_12_162226_drop_opening_media_columns_from_modules_table.php`), yaitu penghapusan `opening_media_type` & `opening_media_file` pada modul pembelajaran (bukan pada level kursus).
  * Model `ProfileVideo` (`profile_videos`) memiliki 2 field (`video_file` & `thumbnail`), namun ini khusus untuk CMS Video Profil Lembaga di landing page.

### 2. Implementasi Eksisting pada Repository
* **Admin Form & Admin Table**: Memiliki dropdown `Thumbnail Type` (`image`/`video`) dan 1 file input `thumbnail_file`. Jika `video` dipilih, file `.mp4` disimpan ke `thumbnail_file`.
* **Detail Course Sidebar (`sidebar-card.blade.php`)**: Sudah mengimplementasikan rendering `<video src="..." controls>` jika `thumbnail_type === 'video'`.
* **Card Grid & Student Portal**: Mencoba menampilkan `thumbnail_file` di dalam tag `<img>`, sehingga file video gagal muat dan jatuh ke image placeholder.

### 3. Analisis Kebutuhan UX (A vs B)
* **Kemungkinan A (Direct `<video>` rendering)**:
  * Memanfaatkan file video pada `thumbnail_file` untuk langsung di-render sebagai HTML5 `<video>` (dengan `muted loop playsinline` atau `controls`) di komponen card grid.
  * **Kondisi**: Sangat sesuai dengan struktur database dan admin controller yang saat ini terpasang di repository.
* **Kemungkinan B (Gambar Poster Terpisah)**:
  * Admin mengunggah gambar cover/poster terpisah khusus untuk course yang tipe thumbnail-nya video.
  * **Kondisi**: Struktur 2 file terpisah ini **belum pernah ada di skema database `course_levels` repository**. Jika ingin menggunakan cara ini, diperlukan migrasi database baru dan perombakan form admin.

### 4. Informasi yang Masih NEEDS DISCUSSION
* Apakah user menginginkan **Kemungkinan A** (video diputar langsung pada card via tag `<video>`) atau **Kemungkinan B** (menambah fitur upload gambar poster cover terpisah untuk video)?
* Keputusan ini membutuhkan konfirmasi dari user sebelum status task diubah menjadi `READY` atau `IN_PROGRESS`.

