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

- Model `CourseLevel` dan tabel `course_levels` memiliki kolom `thumbnail_type`, `thumbnail_file`, dan `video_poster_file`.
- Patch minimal telah diterapkan pada data mapper controller (`PublicCourseController`, `AllCourseController`, `DashboardController`) serta Blade partials (`course-list`, `grid`, `course-order`).
- Card di Public Catalog (`/courses`), Homepage (`/`), Student Portal My Courses (`/student/my-courses`), All Courses (`/student/all-courses`), dan Dashboard (`/student`) kini me-render gambar poster video (`video_poster_file`) dengan overlay ikon play.

## Expected Behavior

- **Image Course**:
  - `thumbnail_type` = `image`
  - `thumbnail_file` = file gambar (`.jpg`, `.png`, `.webp`)
  - `video_poster_file` = `null`
  - Card di Homepage, Catalog, Student Portal me-render gambar `thumbnail_file`.
- **Video Course**:
  - `thumbnail_type` = `video`
  - `thumbnail_file` = file video (`.mp4`, `.webm`, `.mov`)
  - `video_poster_file` = file gambar poster (`.jpg`, `.png`, `.webp`)
  - Card di Homepage, Catalog, Student Portal me-render `video_poster_file` sebagai `<img>` dengan overlay ikon play.
  - Halaman Detail Course (`sidebar-card.blade.php`) & Course Order (`course-order.blade.php`) me-render elemen `<video src="..." poster="..." controls preload="metadata">`.
  - Jika poster belum tersedia pada video course lama, card me-render image placeholder fallback + overlay ikon play.

## Audit Findings & Root Cause Final

- **Verifikasi Database**: Kolom `video_poster_file` terkonfirmasi tersedia pada tabel `course_levels` (Record ID 4: "Foundation", `thumbnail_type = video`, `thumbnail_file` = `.mp4`, `video_poster_file` = `.jpg`).
- **Verifikasi Storage**: File poster fisik tersimpan di `storage/app/public/course-levels/video-posters/` dan terhubung via symlink `public/storage`.
- **Root Cause Final**: Sebelumnya, data poster dan `thumbnail_type` berhasil disimpan di DB/storage oleh Admin, tetapi terputus pada data mapper controller public/student (`PublicCourseController`, `AllCourseController`, `DashboardController`) serta pada parameter Blade partial (`course-list.blade.php` & `all-courses/grid.blade.php`). Akibatnya, card view selalu menerima `thumbnail_type = 'image'` dan `poster = null` secara fallback default dan mencoba me-render URL video `.mp4` ke tag `<img>`.

## Files Changed

- `database/migrations/2026_07_20_000000_add_video_poster_file_to_course_levels_table.php` (NEW - Migrasi penambahan kolom `video_poster_file`)
- `app/Models/CourseLevel.php` (MODIFIED - Menambahkan `video_poster_file` ke `$fillable`)
- `app/Http/Controllers/Admin/CourseManagement/CourseLevelController.php` (MODIFIED - Logika store, update, destroy untuk video poster)
- `resources/views/partials/admin/course-management/levels/form.blade.php` (MODIFIED - Input dinamis upload & preview poster)
- `resources/views/partials/admin/course-management/levels/table.blade.php` (MODIFIED - Preview modal poster pada tabel admin)
- `resources/views/partials/public/home/programs.blade.php` (MODIFIED - Meneruskan `thumbnail_type` & `poster` URL)
- `resources/views/components/public/course-grid.blade.php` (MODIFIED - Render poster image + play icon overlay)
- `resources/views/partials/public/course-detail/sidebar-card.blade.php` (MODIFIED - Tag `<video>` dengan atribut `poster="..."`)
- `app/Http/Controllers/Public/CourseController.php` (MODIFIED - Patch mapper `$courseItems` dengan `poster` & `thumbnail_type`)
- `app/Http/Controllers/Student/MyCourseController.php` (MODIFIED - Mapper payload item `thumbnailType` & `poster`)
- `app/Http/Controllers/Student/AllCourseController.php` (MODIFIED - Patch mapper `$courseItems` dengan `poster` & `thumbnailType`)
- `app/Http/Controllers/Student/DashboardController.php` (MODIFIED - Patch mapper `$continueLearningCourses` dengan `poster` & `thumbnailType`)
- `resources/views/partials/student/my-courses/course-list.blade.php` (MODIFIED - Forwarding `:poster` & `:thumbnail-type` ke `my-course-card`)
- `resources/views/partials/student/all-courses/grid.blade.php` (MODIFIED - Forwarding `:poster` & `:thumbnail-type` ke `course-card`)
- `resources/views/components/student/my-course-card.blade.php` (MODIFIED - Render poster image + play icon overlay)
- `resources/views/components/student/course-card.blade.php` (MODIFIED - Render poster image + play icon overlay)
- `resources/views/partials/student/dashboard/learning-list.blade.php` (MODIFIED - Render poster image + play icon overlay)
- `resources/views/pages/public/course-order.blade.php` (MODIFIED - Tag `<video>` dengan atribut `poster="..."`)

## Implementation Result

Patch minimal telah berhasil diterapkan secara komprehensif pada seluruh controller public/student dan view components:
1. `PublicCourseController.php` kini meneruskan `poster` URL dan `thumbnail_type` ke Public Course Catalog (`/courses`).
2. `AllCourseController.php` dan `grid.blade.php` kini meneruskan `poster` URL dan `thumbnailType` ke Student All Courses (`/student/all-courses`).
3. `DashboardController.php` dan `learning-list.blade.php` kini meneruskan `poster` URL dan `thumbnailType` ke Student Dashboard (`/student`).
4. `course-list.blade.php` kini meneruskan `:poster` dan `:thumbnail-type` ke Student My Courses (`/student/my-courses`).
5. `course-order.blade.php` kini mendukung atribut `poster="..."` dan `preload="metadata"` pada tag `<video>`.

## Technical Test Result

- **Initial DB Verification**: Record ID 4 ("Foundation") terverifikasi memiliki `thumbnail_type = video`, `thumbnail_file` (`.mp4`), dan `video_poster_file` (`.jpg`).
- **PHP Syntax Check (`php -l`)**: Lolos 100% tanpa error pada seluruh controller.
- **Cache Clearing (`php artisan optimize:clear`)**: Lolos 100% (config, cache, routes, views, compiled successfully cleared).
- **Vite Asset Build (`npm run build`)**: Lolos 100% dalam 1.97s.

## Remaining Notes

- Status task tetap **`TESTING`** dan **tidak akan diubah menjadi `COMPLETED`** sebelum user melakukan manual testing pada 4 URL utama dan memberikan persetujuan final.

## Manual Testing Checklist (User Verification)

Sebutkan 4 URL berikut saat melakukan manual testing:
- [ ] `http://127.0.0.1:8000/courses` (Public Catalog: Menampilkan poster gambar + overlay ikon play untuk course video)
- [ ] `http://127.0.0.1:8000/` (Homepage: Menampilkan poster gambar + overlay ikon play untuk course video)
- [ ] `http://127.0.0.1:8000/student/my-courses` (Student My Courses: Menampilkan poster gambar + overlay ikon play)
- [ ] `http://127.0.0.1:8000/student/all-courses` (Student All Courses: Menampilkan poster gambar + overlay ikon play)
- [ ] `http://127.0.0.1:8000/courses/{slug}` (Course Detail: Video player menggunakan poster sebelum diputar)
