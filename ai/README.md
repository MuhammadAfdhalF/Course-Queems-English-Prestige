# Queens English Prestige — AI Project Guide

## Project Overview

Queens English Prestige adalah aplikasi Learning Management System (LMS) dan e-course Bahasa Inggris yang dirancang untuk memberikan pengalaman belajar interaktif dan pengelolaan kursus secara terpadu. Aplikasi ini memiliki tiga area utama:

- **Public Website**: Menampilkan informasi lembaga, katalog program/level kursus, placement test (Free Test) interaktif, berita/galeri, formulir kontak, ulasan alumni, serta portal publik untuk verifikasi keabsahan sertifikat.
- **Student Portal**: Area khusus siswa terdaftar untuk mengakses materi modul, mengerjakan latihan (*practice*), mengambil ujian akhir (*final exam*), mengirim ulasan untuk membuka kunci sertifikat, mengunduh sertifikat PDF, dan melihat notifikasi internal.
- **Admin Panel**: Dashboard manajemen lengkap untuk pengelolaan CMS web publik, katalog program & modul, ujian & latihan, pencatatan order & pembayaran, hak akses siswa (*enrollment*), moderasi ulasan, penerbitan sertifikat, serta laporan pendapatan (*revenue*).

## Tech Stack

Stack yang terverifikasi digunakan di dalam repository:

- **Framework Backend**: Laravel 12
- **Bahasa Pemrograman**: PHP 8.2+
- **Database**: MySQL
- **Templating Engine**: Blade
- **Styling / CSS Framework**: Tailwind CSS v4
- **Interactive JS**: Alpine.js
- **Asset Bundler**: Vite 6
- **Rich Text Editor**: TinyMCE
- **PDF Generator**: DomPDF (`barryvdh/laravel-dompdf`)
- **QR Code Generator**: Simple QR Code (`simplesoftwareio/simple-qrcode`)

## Application Areas

- **Public Website**: Berfungsi sebagai landing page utama untuk calon siswa. Menyediakan akses ke katalog kursus, fitur placement test (*Free Test*) dengan kalkulasi skor & rekomendasi program instan, serta verifikasi keabsahan sertifikat berbasis token/QR Code.
- **Student Portal**: Memungkinkan siswa mengelola profil, melihat progres belajar secara berurutan, mengakses materi modul, mengerjakan latihan dan ujian akhir, mengirimkan testimoni ulasan kursus, serta mengunduh sertifikat PDF.
- **Admin Panel**: Pusat kendali bagi admin untuk mengelola konten CMS, struktur program/level/modul/latihan/ujian, melakukan review & scoring manual untuk soal essay/upload file, memverifikasi pembayaran transfer/cash, memberikan akses manual enrollment, serta memantau notifikasi dan laporan keuangan.

## Main Business Flow

Alur bisnis utama dalam aplikasi:

Student register/login  
→ Student order course  
→ Order pending  
→ Admin record payment  
→ Payment paid  
→ Order approved  
→ Enrollment/course access dibuat  
→ Student belajar module  
→ Student mengerjakan practice  
→ Student mengerjakan final exam  
→ Certificate dibuat locked  
→ Student submit course testimonial  
→ Certificate menjadi issued  
→ Certificate dapat didownload dan diverifikasi

## Important Business Rules

1. User memiliki role `admin` atau `student`.
2. User `inactive` (`is_active = false`) tidak boleh login.
3. Student tidak mencatat pembayaran sendiri.
4. Admin yang mencatat pembayaran dan approve order.
5. Student tidak boleh membuat pending order ganda untuk course yang sama.
6. Student yang sudah memiliki active enrollment tidak boleh order course yang sama.
7. Payment `paid`, order `approved`, dan enrollment dibuat dalam satu flow *record payment*.
8. Module dibuka secara berurutan (*sequential locking*).
9. Final exam hanya terbuka setelah seluruh module dalam level tersebut selesai.
10. Certificate pertama kali dibuat dengan status `locked` saat lulus final exam.
11. Course testimonial digunakan oleh student untuk membuka status certificate dari `locked` menjadi `issued`.
12. Revenue berasal dari payment dengan status `paid`.
13. Notification internal admin dan student sudah tersedia via sistem notifikasi database.

## Important Project Files

File dan folder penting berdasarkan hasil audit repository:

- `routes/web.php` — Seluruh definisi route aplikasi (Public, Auth, Student, Admin).
- `app/Http/Controllers/Auth/` — Autentikasi dan pendaftaran student profil.
- `app/Http/Controllers/Public/` — Controller untuk halaman publik, order, free test, dan verifikasi sertifikat.
- `app/Http/Controllers/Student/` — Controller portal siswa (dashboard, learning path, practice, final exam, testimoni, sertifikat, profil, notifikasi).
- `app/Http/Controllers/Admin/` — Controller admin panel (CMS, course management, orders, payments, revenue, students, course access, notifications).
- `app/Models/` — 41 Eloquent Model aplikasi.
- `app/Services/` — Service layer aplikasi (`StudentProgressService`, `CertificateService`, `StudentNotificationService`, `AdminNotificationService`).
- `resources/views/` — View Blade yang terbagi dalam folder `pages/public`, `pages/student`, `pages/admin`, dan `pages/auth`.
- `resources/views/pdf/certificate.blade.php` — Template layout cetak PDF sertifikat.
- `database/migrations/` — File migrasi skema database.

## Existing Core Services

- **`StudentProgressService`**: Mengelola progres modul siswa, menandai status modul (*in progress* / *completed*), menghitung persentase progres enrollment, dan mengecek status pembukaan modul secara berurutan.
- **`CertificateService`**: Menangani siklus sertifikat, mulai dari penerbitan sertifikat berstatus `locked` saat lulus final exam, pembuatan nomor sertifikat & token verifikasi 48 karakter, hingga pembukaan kunci sertifikat (`issued`) setelah ulasan testimoni dikirim.
- **`StudentNotificationService`**: Mengirim notifikasi internal ke siswa terkait approval/rejection order, akses manual, review hasil practice/exam, dan ketersediaan sertifikat.
- **`AdminNotificationService`**: Mengirim notifikasi internal ke admin terkait order baru, jawaban latihan/ujian yang membutuhkan nilai manual, dan pengiriman testimoni baru.

## Verified Existing Features

Fitur yang terverifikasi tersedia di dalam repository:

- **Authentication dan Student Profile**: Registrasi siswa lengkap dengan data profil (WhatsApp, TTL, alamat, pekerjaan), login, logout, dan manajemen profil.
- **Public Website dan CMS**: Landing page interaktif dan pengelolaan konten CMS lengkap dari Admin Panel.
- **Free Test**: Placement test publik dengan kalkulasi skor otomatis dan rekomendasi program.
- **Course Management**: Pengelolaan program, level, modul, materi, latihan, dan ujian akhir.
- **Order dan Payment**: Formulir order siswa, pencatatan pembayaran manual oleh admin (upload bukti transfer/cash), dan approval order.
- **Course Access / Enrollment**: Pembuatan hak akses otomatis dari paid order dan pembuatan akses manual oleh admin.
- **Learning Module**: Path belajar modul berurutan dan materi viewer.
- **Practice dan Final Exam**: Pengerjaan latihan dan ujian akhir dengan auto-grading (multiple choice) dan manual review (essay/upload).
- **Certificate PDF dan QR Verification**: Penerbitan sertifikat PDF, pembukaan via testimoni, dan verifikasi token/QR Code publik.
- **Testimonial**: Pengiriman testimoni kursus (buka kunci sertifikat) dan ulasan umum lembaga serta moderasi admin.
- **Admin dan Student Internal Notifications**: Pusat notifikasi in-app database untuk admin dan student.
- **Revenue Report**: Laporan ringkasan pendapatan dari pembayaran terkonfirmasi.

## Known Notes

Catatan kebersihan kode dan temuan historis (bukan task aktif dan **tidak boleh diubah tanpa pembahasan**):

- Terdapat controller student kosong (`Student\CourseController` dan `Student\ModuleController`) yang saat ini belum digunakan karena logika ditangani oleh controller lain.
- Terdapat route logout GET sementara (`Route::get('/logout', ...)`) di `routes/web.php` selain route logout POST standar.
- Terdapat migration historis dengan tujuan serupa.
- Terdapat notifikasi admin order baru pada `PublicCourseOrderController` yang masih ditangani langsung dari controller, sementara flow lain menggunakan service terpusat.

## AI Working Rules

1. Baca `README.md` dan file task aktif sebelum melakukan perubahan.
2. Jangan langsung coding sebelum memahami flow existing.
3. Saat user meminta diskusi, jangan mengubah kode.
4. Audit root cause dan file terkait terlebih dahulu.
5. Gunakan perubahan minimal dan terarah.
6. Jangan melakukan broad refactor tanpa permintaan.
7. Jangan mengubah business rule di luar scope task.
8. Jangan mengubah database tanpa pembahasan.
9. Jangan menjalankan command destruktif.
10. Jangan membuka atau menampilkan credential.
11. Setelah implementasi, laporkan file berubah, perubahan behavior, command yang dijalankan, testing, dan risiko tersisa.
12. Repository aktual adalah sumber kebenaran utama.
13. Dokumentasi lama hanya digunakan sebagai historical context.
14. Jangan mengerjakan task lain di luar task aktif.

## Current Planned Tasks

Daftar rencana task awal:

1. **Course thumbnail ketika menggunakan video** (`PLANNED`)
2. **Order WhatsApp template** (`PLANNED`)
3. **Email notification setiap order baru masuk** (`PLANNED`)
4. **Certificate layout improvement** (`PLANNED`)

**Keterangan**:
- Planned task hanya daftar awal.
- Jangan mengimplementasikan task sebelum dibuat file task resmi dan dibahas bersama user.
- Dalam satu waktu, hanya **satu task** yang boleh berstatus aktif (`IN_PROGRESS`).

## Development Commands

Command umum yang dapat digunakan sebagai referensi pengujian dan pengembangan:

```bash
composer install
npm install
npm run dev
npm run build
php artisan optimize:clear
php artisan migrate
```

*Peringatan*: Migration atau command yang mengubah database **tidak boleh dijalankan oleh AI tanpa persetujuan eksplisit dari user**.
