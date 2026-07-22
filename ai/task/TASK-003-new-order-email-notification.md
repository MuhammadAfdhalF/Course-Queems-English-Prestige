# TASK-003 — New Order Email Notification

## Status

TESTING

## User Request

Setiap kali student berhasil membuat order course baru (`courses.order.store`), sistem otomatis mengirimkan email notifikasi kepada admin.
Penerima email sementara untuk tahap testing adalah `cokbadut@gmail.com` dan menggunakan mailer `log` (`MAIL_MAILER=log`) sehingga email dicatat ke file log `storage/logs/laravel.log` tanpa butuh SMTP nyata.
Jika terjadi kegagalan pengiriman email (misal exception mailer), transaksi order database harus tetap berhasil, tidak di-rollback, internal admin notification di DB tetap ada, dan student tetap mendapatkan pesan order sukses.

## Objective

Menyediakan Mailable class Laravel (`App\Mail\NewOrderAdminNotification`), Blade HTML template view email yang profesional (`resources/views/emails/admin/new-order-notification.blade.php`), serta memicu pengiriman email synchronous secara aman setelah `DB::transaction()` commit di `PublicCourseOrderController@store` menggunakan `try-catch` dan error logging.

## Current Behavior

- Pada `PublicCourseOrderController@store`, pembuatan order dan internal DB notification untuk admin dibungkus di dalam `DB::transaction()`.
- Telah dibuat Mailable `NewOrderAdminNotification` dan email view Blade HTML.
- Telah dikonfigurasi `ADMIN_ORDER_EMAIL=cokbadut@gmail.com` di `.env` dan `MAIL_MAILER=log`.

## Expected Behavior

1. Student melakukan submit order course (`POST /courses/{slug}/order`).
2. Order berhasil divalidasi dan tersimpan di database dalam `DB::transaction()`.
3. Internal DB Notification (`Notification::create`) untuk admin aktif dibuat di dalam transaction.
4. `DB::transaction()` berhasil commit 100%.
5. Setelah transaction commit, sistem membaca email admin dari `config('mail.admin_order_email')`.
6. Jika `ADMIN_ORDER_EMAIL` terisi, sistem mencoba mengirim email notifikasi ke recipient tersebut.
7. Karena `MAIL_MAILER=log`, isi email terformat lengkap beserta header dan HTML body dicatat ke `storage/logs/laravel.log`.
8. Jika email gagal terkirim ➔ Error ditangkap `try-catch`, dicatat ke `Log::error()`, order **TIDAK di-rollback**, internal DB notification **TIDAK hilang**, dan student **tetap di-redirect** ke `courses.show` dengan pesan order sukses.

## Audit Findings

- **Order Controller**: `App\Http\Controllers\Public\CourseOrderController` (method `store`).
- **Transaction Boundary**: Lines 72–95 di `CourseOrderController.php`.
- **Existing Mail Implementation**: Tidak ditemukan Mailable atau service email sebelumnya. Ini merupakan notifikasi email pertama di aplikasi.
- **Queue Status**: `QUEUE_CONNECTION` default di project adalah `database`/`sync`, namun tidak ada queue worker yang berjalan terus-menerus.
- **Rekomendasi Mail Sending**: Pengiriman email secara **Synchronous** setelah `DB::transaction()` commit di dalam blok `try-catch` adalah opsi paling stabil dan aman untuk hosting.

## Email Flow Explanation

1. **Pengirim (Mailer/SMTP)**: Pada tahap testing ini menggunakan driver `log` (`MAIL_MAILER=log`), sehingga Laravel menuliskan seluruh struktur email ke file log `storage/logs/laravel.log`.
2. **Penerima (Recipient)**: Diambil dari `config('mail.admin_order_email')` (environment variable `ADMIN_ORDER_EMAIL=cokbadut@gmail.com`).
3. **Pemicuan (Trigger)**: Dipicu dari `PublicCourseOrderController@store` segera setelah `DB::transaction()` selesai melakukan commit.
4. **Safety & Fallback**: Menggunakan `try-catch`. Jika koneksi email/SMTP error, exception ditangkap dan dicatat ke `Log::error`, sehingga pengguna/student tidak melihat error 500 dan transaksi database order tetap utuh.

## Environment Configuration Plan & Implementation

File `config/mail.php` menambahkan opsi:
```php
'admin_order_email' => env('ADMIN_ORDER_EMAIL'),
```

File `.env.example`:
```env
ADMIN_ORDER_EMAIL=
```

File `.env` lokal:
```env
MAIL_MAILER=log
ADMIN_ORDER_EMAIL=cokbadut@gmail.com
```

## Proposed Email Template

### Subject Line
`Order Course Baru - {{ $order->order_code }}`

### Content Structure (Blade HTML Email)
- **Header**: Queens English Prestige — Admin Notification
- **Greeting**: "Halo Admin Queens English Prestige,"
- **Message**: "Terdapat pemesanan course baru dari student yang perlu ditindaklanjuti. Berikut detail lengkap pesanan tersebut:"
- **Order Details**:
  - Kode Order: `{{ $order->order_code }}`
  - Nama Student: `{{ $student->name }}`
  - Email Student: `{{ $student->email }}`
  - WhatsApp Student: `{{ $profile?->whatsapp ?? '-' }}`
  - Program Course: `{{ $courseProgram->name }}`
  - Nama Course: `{{ $courseLevel->name }}`
  - Biaya Course: `Rp {{ number_format((float) $order->price, 0, ',', '.') }}`
  - Tanggal Order: `{{ ($order->order_date ?? $order->created_at)->format('d-m-Y H:i') }}`
  - Status: `Menunggu Konfirmasi Pembayaran`
- **Call-to-Action Button**: `Open Order Detail` (`{{ route('admin.orders.show', $order) }}`)
- **Text Link Fallback**: Salin & buka tautan manual jika tombol tidak dapat diklik.
- **Footer**: "Email ini dikirim otomatis oleh Sistem Queens English Prestige."

## Files Involved

- `config/mail.php`
- `.env.example`
- `.env`
- `app/Mail/NewOrderAdminNotification.php` (NEW)
- `resources/views/emails/admin/new-order-notification.blade.php` (NEW)
- `app/Http/Controllers/Public/CourseOrderController.php`
- `ai/task/TASK-003-new-order-email-notification.md`

## Files Changed

1. **`app/Mail/NewOrderAdminNotification.php`** (Created)
2. **`resources/views/emails/admin/new-order-notification.blade.php`** (Created)
3. **`config/mail.php`** (Modified)
4. **`.env.example`** (Modified)
5. **`.env`** (Modified)
6. **`app/Http/Controllers/Public/CourseOrderController.php`** (Modified)

## Implementation Result

1. Mailable `NewOrderAdminNotification` dibuat tanpa queue (`ShouldQueue`).
2. Email Blade view HTML `resources/views/emails/admin/new-order-notification.blade.php` dibuat dengan styling inline CSS yang responsif.
3. Konfigurasi `admin_order_email` ditambahkan ke `config/mail.php`.
4. `CourseOrderController@store` diperbarui dengan pemicuan email synchronous menggunakan `try-catch` & logging di luar `DB::transaction()`.
5. Testing `.env` dikonfigurasi dengan `MAIL_MAILER=log` dan `ADMIN_ORDER_EMAIL=cokbadut@gmail.com`.

## Technical Test Result

- **PHP Syntax Check (`php -l`)**: Lolos 100% tanpa error pada Mailable, Controller, dan Config.
- **Cache Clearing (`php artisan optimize:clear`)**: Lolos 100% (config, cache, routes, views cleared successfully).

## Remaining Notes

- Status task saat ini adalah **`TESTING`** dan **tidak akan diubah menjadi `COMPLETED`** sebelum user melakukan manual testing dan mengonfirmasi hasil email di file log `storage/logs/laravel.log`.

## Testing Checklist (User Verification)

- [ ] Student submit order course baru di frontend/public.
- [ ] Order berhasil tersimpan di database dan pesan sukses muncul di halaman course.
- [ ] File log `storage/logs/laravel.log` dibuka setelah order dibuat.
- [ ] Log mencatat entri email dengan recipient `cokbadut@gmail.com`.
- [ ] Subject email `Order Course Baru - QEP-...` tercatat di log.
- [ ] Data Kode Order, Nama Student, Email Student, WhatsApp, Program, Course, Biaya, Tanggal Order (`d-m-Y H:i`), dan Status tercatat presisi di log.
- [ ] Link tombol `Open Order Detail` mengarah ke route `admin.orders.show`.
- [ ] Uji skenario `ADMIN_ORDER_EMAIL=` kosong ➔ Log mencatat warning skip, order tetap sukses.
