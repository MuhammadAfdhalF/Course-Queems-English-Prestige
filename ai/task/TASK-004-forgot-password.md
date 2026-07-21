# TASK-004 — Forgot Password via Email

## Status

TESTING

## User Request

Siswa (student) yang lupa password akunnya dapat melakukan reset password sendiri melalui link yang dikirimkan ke email terdaftar.
Flow utama:
Student klik "Forgot password?" di halaman login ➔ Mengisi email ➔ Sistem mengirimkan email berisi link reset password ➔ Student membuka link ➔ Mengisi password baru ➔ Password di-reset di database ➔ Student login kembali dengan password baru.
Admin tidak boleh mengetahui, melihat, atau membuatkan password baru untuk student.
Password baru TIDAK boleh dikirim via WhatsApp.

## Objective

Mengimplementasikan fitur Lupa Password (Forgot Password) dan Reset Password berbasis email menggunakan mekanisme bawaan **Laravel Password Broker** (`Password::broker()`, `password_reset_tokens` table) yang aman, responsif, dan terintegrasi dengan layout auth Queens English Prestige.

## Current Behavior

- Pada `resources/views/partials/auth/login-form.blade.php`, tombol `Forgot password?` sudah terhubung ke `route('password.request')`.
- Route, Controller, Notification HTML Email, dan Blade views untuk penanganan Lupa Password & Reset Password telah terimplementasi.
- Tabel database `password_reset_tokens` bawaan Laravel telah digunakan tanpa membuat migrasi baru.

## Expected Behavior

1. **Request Reset Link**:
   - Student mengeklik "Forgot password?" di halaman login ➔ Diarahkan ke `/forgot-password`.
   - Student menginput alamat email.
   - Sistem memvalidasi email dan memanggil `Password::sendResetLink()`.
   - Token acak dibuat dan disimpan secara ter-hash di tabel `password_reset_tokens` dengan masa berlaku 60 menit (`config/auth.php`).
   - Sistem mengirimkan email responsif bertema Queens English Prestige yang berisi tombol dan URL reset password (`/reset-password/{token}?email=...`).
   - Tampil pesan sukses umum ("Jika email tersebut terdaftar, link reset password akan dikirim.").

2. **Reset Password Execution**:
   - Student mengeklik tombol / link di email ➔ Diarahkan ke form `/reset-password/{token}`.
   - Student menginput email, password baru (min 8 karakter), dan konfirmasi password baru.
   - Sistem memvalidasi token, masa berlaku, dan kesesuaian email via `Password::reset()`.
   - Password baru di-hash (`Hash::make`) dan disimpan di tabel `users`. Token dihapus.
   - Student di-redirect ke halaman login dengan pesan sukses ("Password berhasil diperbarui. Silakan login menggunakan password baru.").
   - Jika akun berstatus `is_active = false`, password tetap ter-reset, namun saat mencoba login `AuthController` tetap menolak akses login secara aman.

## Audit Findings

- **Auth Controller & Layout**: Custom `AuthController` di `app/Http/Controllers/Auth/AuthController.php` menggunakan Blade views di `resources/views/pages/auth/` dan `resources/views/partials/auth/`. Layout terpusat di `resources/views/layouts/auth.blade.php`.
- **Laravel Password Broker Config**:
  - `config/auth.php`: Broker `'users'`, provider `'users'` (`App\Models\User::class`), tabel `'password_reset_tokens'`, expire `60` menit, throttle `60` detik.
  - Model `App\Models\User` extends `Authenticatable` dan me-override `sendPasswordResetNotification($token)` untuk menggunakan custom notification.
- **Database Table**: Migration `0001_01_01_000000_create_users_table.php` sudah membuat tabel `password_reset_tokens` (`email` primary, `token`, `created_at`). **Tidak memerlukan migrasi database baru.**

## Password Reset Flow Explanation

```
[Student] -> Klik "Forgot password?" di Login Page
   ↓
[Form /forgot-password] -> Input Email -> Submit
   ↓
[Laravel Password Broker] -> Generates hashed token in `password_reset_tokens` (Expire: 60 mins)
   ↓
[Notification / Email] -> Sends HTML Email with Reset Link: `/reset-password/{token}?email={email}`
   ↓
[Student] -> Clicks link in Email -> Opens [/reset-password/{token}]
   ↓
[Form /reset-password] -> Inputs Email + New Password + Password Confirmation -> Submit
   ↓
[Laravel Password Broker] -> Validates token, email & expiry -> Hashes & updates User password -> Deletes token
   ↓
[Redirect to Login] -> Flash success message -> Student logs in with new password
```

## Laravel Password Broker Audit

- **Apakah mendukung Model User existing?**: **Ya**, `User` class me-extends `Authenticatable` dan dapat langsung memanggil `Password::sendResetLink()` & `Password::reset()`.
- **Apakah token disimpan plaintext?**: **Tidak**, Laravel Password Broker secara otomatis meng-hash token sebelum disimpan ke tabel `password_reset_tokens`.
- **Apakah token memiliki expiration & throttling?**: **Ya**, token otomatis kadaluwarsa setelah 60 menit dan dibatasi 5 request per menit via middleware route.

## Proposed Email Template

### Subject Line
`Reset Password Akun - Queens English Prestige`

### Content Structure (Blade HTML Email)
- **Header**: Queens English Prestige — Account Security
- **Greeting**: "Halo {{ $user->name }},"
- **Message**: "Kami menerima permintaan untuk mengatur ulang password akun Queens English Prestige Anda. Silakan klik tombol di bawah ini untuk membuat password baru:"
- **CTA Button**: **Reset Password** (`{{ $resetUrl }}`)
- **Text Link Fallback**: "Link ini berlaku selama {{ $expireMinutes }} menit. Jika Anda tidak meminta perubahan password, abaikan email ini."
- **Warning Notice**: "Untuk keamanan akun Anda, jangan bagikan tautan ini kepada siapapun."
- **Footer**: "Email ini dikirim otomatis oleh Sistem Queens English Prestige."

## Security Decisions

1. **Informational Protection**: Response forgot password menggunakan pesan umum ("Jika email tersebut terdaftar, link reset password akan dikirim.") agar pengguna jahat tidak dapat melakukan *email enumeration*.
2. **No Password via WhatsApp / Admin**: Password plaintext tidak pernah dibuat atau dikirimkan oleh admin atau via WhatsApp. Pengisian password baru dilakukan 100% oleh student di form SSL/TLS yang aman.
3. **Token Invalidation**: Token reset password bersifat sekali pakai (single-use) dan otomatis dihapus begitu password berhasil diperbarui.
4. **Inactive User Isolation**: Pengguna berstatus `is_active = 0` tidak diaktifkan secara otomatis saat melakukan reset password. Guard login di `AuthController` tetap menolak login pengguna non-aktif.

## Future Enhancements

- **Admin Send Reset Password Link**: Admin dapat mengeklik tombol "Send Password Reset Link" pada halaman Admin Student Detail untuk memicu pengiriman email reset ke student.

## Files Involved

- `routes/web.php`
- `app/Models/User.php`
- `resources/views/partials/auth/login-form.blade.php`
- `app/Http/Controllers/Auth/ForgotPasswordController.php` (NEW)
- `app/Http/Controllers/Auth/ResetPasswordController.php` (NEW)
- `app/Notifications/ResetPasswordNotification.php` (NEW)
- `resources/views/emails/auth/reset-password-notification.blade.php` (NEW)
- `resources/views/pages/auth/forgot-password.blade.php` (NEW)
- `resources/views/pages/auth/reset-password.blade.php` (NEW)
- `resources/views/partials/auth/forgot-password-form.blade.php` (NEW)
- `resources/views/partials/auth/reset-password-form.blade.php` (NEW)
- `ai/task/TASK-004-forgot-password.md`

## Files Changed

1. **`app/Http/Controllers/Auth/ForgotPasswordController.php`** (Created)
2. **`app/Http/Controllers/Auth/ResetPasswordController.php`** (Created)
3. **`app/Notifications/ResetPasswordNotification.php`** (Created)
4. **`resources/views/emails/auth/reset-password-notification.blade.php`** (Created)
5. **`resources/views/pages/auth/forgot-password.blade.php`** (Created)
6. **`resources/views/pages/auth/reset-password.blade.php`** (Created)
7. **`resources/views/partials/auth/forgot-password-form.blade.php`** (Created)
8. **`resources/views/partials/auth/reset-password-form.blade.php`** (Created)
9. **`routes/web.php`** (Modified)
10. **`app/Models/User.php`** (Modified)
11. **`resources/views/partials/auth/login-form.blade.php`** (Modified)

## Implementation Result

1. `ForgotPasswordController` dan `ResetPasswordController` berhasil diimplementasikan menggunakan Laravel Password Broker bawaan.
2. Custom Notification `ResetPasswordNotification` dan Blade HTML Email Template `emails.auth.reset-password-notification` telah dibuat dengan branding Queens English Prestige.
3. Model `User` me-override `sendPasswordResetNotification()` untuk menggunakan notifikasi HTML kustom.
4. View auth `forgot-password.blade.php` & `reset-password.blade.php` dibuat mengikuti design system auth existing.
5. Route `password.request`, `password.email`, `password.reset`, dan `password.update` didaftarkan pada middleware `guest`.

## Technical Test Result

- **PHP Syntax Check (`php -l`)**: Lolos 100% tanpa error pada seluruh controller, notification, dan model.
- **Route List (`php artisan route:list`)**: Lolos 100% (semua 4 route password reset terdaftar presisi).
- **Cache Clearing (`php artisan optimize:clear`)**: Lolos 100% (config, cache, routes, views cleared successfully).

## Remaining Notes

- Status task saat ini adalah **`TESTING`** dan **tidak akan diubah menjadi `COMPLETED`** sebelum user melakukan manual testing dan mengonfirmasi alur lupa password.

## Testing Checklist (User Verification)

- [ ] Klik link "Forgot password?" pada halaman login ➔ Diarahkan ke `/forgot-password`.
- [ ] Submit form forgot password dengan email terdaftar ➔ Log / email terkirim, tampil pesan sukses.
- [ ] Submit form forgot password dengan email tidak terdaftar ➔ Tampil pesan sukses netral tanpa error leak.
- [ ] Buka link di email ➔ Diarahkan ke form `/reset-password/{token}` dengan email terisi otomatis.
- [ ] Reset password dengan password < 8 karakter / konfirmasi tidak cocok ➔ Tampil error validasi.
- [ ] Reset password dengan data valid ➔ Password terbarui, token terhapus, redirect ke login dengan pesan sukses.
- [ ] Coba gunakan token yang sama untuk kedua kali ➔ Ditolak dengan pesan token invalid/expired.
- [ ] Login menggunakan password baru ➔ Login berhasil.
- [ ] Login menggunakan password lama ➔ Login ditolak.
- [ ] User `is_active = 0` yang di-reset passwordnya ➔ Password terbarui, namun tetap ditolak saat login.
