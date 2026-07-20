# TASK-001 — Course Video Poster Thumbnail

## Status

TESTING

## User Request

Ketika Course Level menggunakan media video, admin harus dapat mengunggah:
1. File video utama (`thumbnail_file`).
2. Gambar poster/thumbnail terpisah untuk video (`video_poster_file`).

Poster digunakan pada card Homepage, Public Course Catalog, dan Student My Courses (dengan overlay ikon play). File video utama hanya ditampilkan pada halaman detail course (dengan atribut `poster`).

## Objective

Menambahkan dukungan gambar poster terpisah (`video_poster_file`) untuk course yang berjenis video (`thumbnail_type = 'video'`) melalui migrasi baru, pembaruan model `CourseLevel`, penyesuaian validasi & pengunggahan file di `CourseLevelController`, pembaruan form & table admin panel, serta pembaruan tampilan card pada Public Homepage, Public Course Catalog, dan Student Portal.

## Current Behavior

- Model `CourseLevel` dan tabel `course_levels` hanya memiliki 2 kolom media: `thumbnail_type` (`image`/`video`) dan `thumbnail_file`.
- Saat `thumbnail_type = 'video'`, `thumbnail_file` menyimpan file video `.mp4`.
- Tampilan card pada Public Catalog dan Student Portal mencoba memasukkan URL `.mp4` ke tag `<img src="...">`, menyebabkan gambar gagal muat dan jatuh ke placeholder.

## Expected Behavior

- **Image Course**:
  - `thumbnail_type` = `image`
  - `thumbnail_file` = file gambar (`.jpg`, `.png`, `.webp`)
  - `video_poster_file` = `null`
  - Card di Homepage, Catalog, dan Student Portal me-render gambar `thumbnail_file`.
- **Video Course**:
  - `thumbnail_type` = `video`
  - `thumbnail_file` = file video (`.mp4`, `.webm`, `.mov`)
  - `video_poster_file` = file gambar poster (`.jpg`, `.png`, `.webp`)
  - Card di Homepage, Catalog, dan Student Portal me-render `video_poster_file` sebagai `<img>` dengan overlay ikon play.
  - Halaman Detail Course (`sidebar-card.blade.php`) me-render elemen `<video src="..." poster="..." controls preload="metadata">`.
  - Jika poster belum tersedia pada video course lama, card me-render image placeholder fallback + overlay ikon play.

## Audit Findings

- Skema existing `course_levels` memerlukan 1 kolom tambahan `video_poster_file` (string, nullable) via migrasi baru.
- `CourseLevelController` memerlukan validasi terpisah untuk poster image saat `thumbnail_type = 'video'`.
- Form Admin (`partials/admin/course-management/levels/form.blade.php`) perlu menampilkan field upload poster secara dinamis saat `Thumbnail Type` = `video`.
- Presenter/Controller (`programs.blade.php`, `MyCourseController.php`) perlu meneruskan `thumbnail_type` dan `video_poster_file` ke komponen card.
- Component card (`course-grid.blade.php` & `my-course-card.blade.php`) perlu me-render poster image + play icon overlay saat tipe video.

## Files Involved

- `database/migrations/2026_07_20_000000_add_video_poster_file_to_course_levels_table.php` (NEW)
- `app/Models/CourseLevel.php`
- `app/Http/Controllers/Admin/CourseManagement/CourseLevelController.php`
- `resources/views/partials/admin/course-management/levels/form.blade.php`
- `resources/views/partials/admin/course-management/levels/table.blade.php`
- `resources/views/partials/public/home/programs.blade.php`
- `resources/views/components/public/course-grid.blade.php`
- `resources/views/partials/public/course-detail/sidebar-card.blade.php`
- `app/Http/Controllers/Student/MyCourseController.php`
- `resources/views/components/student/my-course-card.blade.php`

## Root Cause

Sebelumnya belum ada kolom khusus `video_poster_file` pada tabel `course_levels` untuk menyimpan gambar cover poster terpisah untuk course video, sehingga card view mencoba me-render file video langsung ke tag `<img>`.

## Scope

- Membuat migrasi baru `add_video_poster_file_to_course_levels_table` (tanpa menjalankan migrasi).
- Menambahkan `video_poster_file` pada `$fillable` model `CourseLevel`.
- Memperbarui `CourseLevelController` untuk menangani pengunggahan, validasi, dan penggantian file `video_poster_file` (folder `course-levels/video-posters`).
- Memperbarui Form Admin (`form.blade.php`) dengan Alpine.js untuk menampilkan upload Video Poster saat tipe video dipilih.
- Memperbarui View & Component (`programs.blade.php`, `course-grid.blade.php`, `sidebar-card.blade.php`, `MyCourseController.php`, `my-course-card.blade.php`) untuk me-render poster + play icon overlay.

## Out of Scope

- Menjalankan migrasi database (`php artisan migrate`).
- Mengubah alur bisnis order, payment, enrollment, learning, exam, certificate, atau notification.
- Mengubah nama kolom existing (`thumbnail_type` dan `thumbnail_file`).
- Melakukan refactor pada bagian yang tidak berkaitan.

## Proposed Solution

Menambahkan kolom `video_poster_file` (nullable) pada `course_levels` via migrasi baru, memperbarui Controller Admin untuk memproses upload poster gambar saat `thumbnail_type = video`, serta memperbarui view card di Homepage, Catalog, Student Portal, dan Detail Course agar me-render poster gambar dengan overlay play icon.

## Risks

- Data course video lama yang belum memiliki `video_poster_file` dapat menampilkan broken image jika fallback tidak ditangani.
- **Mitigasi**: Implementasikan fallback ke image placeholder + overlay play icon jika `video_poster_file` bernilai `null` pada course berjenis video.

## Implementation Plan

1. **[NEW] Migration**: Buat file `database/migrations/2026_07_20_000000_add_video_poster_file_to_course_levels_table.php`.
2. **[MODIFY] Model**: Tambahkan `video_poster_file` ke `$fillable` di `CourseLevel.php`.
3. **[MODIFY] Controller Admin**: Update `CourseLevelController.php` (store & update) untuk menangani validasi & upload `video_poster_file` ke `course-levels/video-posters`.
4. **[MODIFY] View Admin Form**: Update `partials/admin/course-management/levels/form.blade.php` untuk menampilkan input file poster & preview poster existing saat `thumbnailType === 'video'`.
5. **[MODIFY] View Admin Table**: Update `partials/admin/course-management/levels/table.blade.php` agar preview image pada table mendukung poster jika tipe video.
6. **[MODIFY] Public Presenter & Card**: Update `programs.blade.php` dan `course-grid.blade.php` agar card me-render poster image + play icon overlay saat `thumbnail_type === 'video'`.
7. **[MODIFY] Detail Course Sidebar**: Update `sidebar-card.blade.php` agar tag `<video>` menggunakan atribut `poster="{{ $posterUrl }}"`.
8. **[MODIFY] Student Presenter & Card**: Update `MyCourseController.php` dan `my-course-card.blade.php` agar card me-render poster image + play icon overlay saat `thumbnail_type === 'video'`.

## Testing Checklist

### Admin Create
- [ ] Create course tipe image dengan file gambar.
- [ ] Create course tipe video dengan file video dan poster.
- [ ] Video tanpa poster ditolak dengan pesan validasi yang jelas.
- [ ] Poster dengan format tidak valid ditolak.
- [ ] Poster melewati batas ukuran (4MB) ditolak.

### Admin Edit
- [ ] Edit teks course image tanpa upload ulang gambar.
- [ ] Ganti gambar course image.
- [ ] Ubah image menjadi video dengan video dan poster.
- [ ] Edit course video tanpa mengganti video atau poster.
- [ ] Ganti hanya video.
- [ ] Ganti hanya poster.
- [ ] Ganti video dan poster.
- [ ] Ubah video menjadi image.
- [ ] Pastikan file media lama dibersihkan dengan aman dari storage.

### Public
- [ ] Homepage menampilkan image course normal.
- [ ] Homepage menampilkan poster video + ikon play overlay.
- [ ] Course catalog menampilkan image normal.
- [ ] Course catalog menampilkan poster video + ikon play overlay.
- [ ] Video lama tanpa poster menampilkan fallback image + ikon play.

### Student
- [ ] My Courses menampilkan image normal.
- [ ] My Courses menampilkan poster video + ikon play overlay.
- [ ] Progress, status, dan tombol course tidak berubah.

### Detail Course
- [ ] Image course tetap tampil normal.
- [ ] Video course tampil dengan controls.
- [ ] Poster muncul sebelum video diputar (`poster="..."`).
- [ ] Video tanpa poster tetap dapat diputar tanpa error.

### Regression
- [ ] Tidak ada broken image dari file `.mp4`.
- [ ] Tidak ada perubahan flow course access, learning, atau payment.
- [ ] Migration belum dijalankan (file migration siap).
- [ ] Tidak ada error build atau syntax.

## Implementation Result

Semua tahap perubahan kode dan view telah selesai dilakukan secara presisi:
1. File migrasi `add_video_poster_file_to_course_levels_table` dibuat (belum dijalankan).
2. Model `CourseLevel` telah ditambahi `video_poster_file` pada `$fillable`.
3. Controller Admin `CourseLevelController` telah disesuaikan untuk memvalidasi dan menyimpan poster gambar ke `course-levels/video-posters`, serta menghapus file lama dengan aman saat diganti atau diubah tipe ke image.
4. Form Admin Level telah diperbarui menggunakan Alpine.js untuk secara dinamis menampilkan upload Video Poster dan preview poster existing.
5. Presenter `programs.blade.php`, controller `MyCourseController.php`, serta view component `course-grid.blade.php`, `my-course-card.blade.php`, dan `sidebar-card.blade.php` telah disesuaikan agar card me-render poster gambar dengan ikon play overlay, dan halaman detail me-render atribut `poster="..."` pada tag `<video>`.
6. Seluruh pengujian sintaks PHP (`php -l`) dan pengujian Vite build (`npm run build`) telah lolos 100% tanpa error.

## Files Changed

- `database/migrations/2026_07_20_000000_add_video_poster_file_to_course_levels_table.php` (NEW - Migrasi penambahan kolom `video_poster_file`)
- `app/Models/CourseLevel.php` (MODIFIED - Menambahkan `video_poster_file` ke `$fillable`)
- `app/Http/Controllers/Admin/CourseManagement/CourseLevelController.php` (MODIFIED - Logika store, update, destroy untuk video poster)
- `resources/views/partials/admin/course-management/levels/form.blade.php` (MODIFIED - Input dinamis upload & preview poster)
- `resources/views/partials/admin/course-management/levels/table.blade.php` (MODIFIED - Preview modal poster pada tabel admin)
- `resources/views/partials/public/home/programs.blade.php` (MODIFIED - Meneruskan `thumbnail_type` & `poster` URL)
- `resources/views/components/public/course-grid.blade.php` (MODIFIED - Render poster image + play icon overlay)
- `resources/views/partials/public/course-detail/sidebar-card.blade.php` (MODIFIED - Tag `<video>` dengan atribut `poster="..."`)
- `app/Http/Controllers/Student/MyCourseController.php` (MODIFIED - Mapper payload item `thumbnailType` & `poster`)
- `resources/views/components/student/my-course-card.blade.php` (MODIFIED - Render poster image + play icon overlay)

## Remaining Notes

- Migrasi database `2026_07_20_000000_add_video_poster_file_to_course_levels_table.php` sengaja **TIDAK DIJALANKAN** oleh AI. User perlu menjalankan command `php artisan migrate` secara manual ketika siap menguji di database.
- Status task saat ini adalah **`TESTING`** dan tidak boleh diubah menjadi `COMPLETED` sampai user melakukan pengujian manual pada antarmuka web dan memberikan konfirmasi final.
