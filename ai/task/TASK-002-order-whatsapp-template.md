# TASK-002 — Order WhatsApp Message Template

## Status

TESTING

## User Request

Pada halaman Admin Order Detail (`/admin/orders/{order}`) terdapat tombol WhatsApp untuk menghubungi student terkait order course yang masih pending.
Dibutuhkan template pesan otomatis yang sudah terisi saat admin mengeklik tombol tersebut, dengan flow:
Admin klik `Follow Up via WhatsApp` ➔ WhatsApp Web/App terbuka ➔ Nomor student terisi ➔ Pesan follow-up terisi ➔ Admin meninjau/mengedit ➔ Admin menekan Send secara manual.

## Objective

Menyediakan URL WhatsApp (`https://wa.me/{number}?text={encoded_message}`) dengan template pesan follow-up pembayaran yang dinamis pada halaman Admin Order Detail (`/admin/orders/{order}`) untuk order berstatus `pending`, serta memastikan normalisasi nomor telepon (`08...` ➔ `628...`) dan URL encoding aman.

## Current Behavior

- Pada `resources/views/pages/admin/orders/show.blade.php`, tombol WhatsApp telah diperbarui menjadi `Follow Up via WhatsApp` untuk order berstatus `pending` dan `Open WhatsApp` untuk order non-pending.
- Nomor WhatsApp kini dibersihkan dan dinormalisasi secara otomatis (`08...`, `+628...`, `8...` ➔ `628...`).
- Jika nomor WhatsApp kosong, tombol menampilkan status disabled `WhatsApp Not Available`.

## Expected Behavior

1. Admin membuka Admin Order Detail (`/admin/orders/{order}`) untuk order berstatus `pending`.
2. Nomor WhatsApp student dinormalisasi secara aman (misal `0812...` atau `+62812...` menjadi `62812...`).
3. Label tombol menjadi `Follow Up via WhatsApp`.
4. Saat diklik, WhatsApp Web/App terbuka pada tab baru dengan pesan yang sudah terisi secara otomatis.
5. Template pesan berisi data dinamis:
   - Nama student (`$student->name`)
   - Kode order (`$order->order_code`)
   - Nama course (`$courseLevel->name`)
   - Biaya course terformat (`Rp ...`)
   - Tanggal order terformat (`d-m-Y H:i`)
6. Pesan TIDAK langsung terkirim (admin meninjau & menekan Send manual di WhatsApp).
7. Jika nomor WhatsApp student kosong, tombol menampilkan state disabled `WhatsApp Not Available`.
8. Alur bisnis payment, approval, rejection, dan enrollment tidak berubah.

## Audit Findings

- **Route & Controller**: `Route::get('/admin/orders/{order}', [CourseOrderController::class, 'show'])` di `app/Http/Controllers/Admin/Order/CourseOrderController.php`.
- **View Primary**: `resources/views/pages/admin/orders/show.blade.php`.
- **Data Model yang Tersedia di View**:
  - `$student->name`
  - `$profile->whatsapp`
  - `$order->order_code`
  - `$order->price`
  - `$order->order_date` / `$order->created_at`
  - `$order->status`
  - `$courseLevel->name`
- **Pattern Normalisasi WhatsApp Project Existing**: Ditemukan pada `ContactPageController.php`:
  ```php
  $cleanNumber = preg_replace('/[^0-9]/', '', $number);
  if (str_starts_with($cleanNumber, '0')) {
      $cleanNumber = '62' . substr($cleanNumber, 1);
  }
  ```
- **Pattern URL Encoding Project Existing**: Ditemukan pada `faq-contact.blade.php` dan `free-test-results`:
  `'https://wa.me/' . $cleanNumber . '?text=' . rawurlencode($message)`

## Files Involved

- `resources/views/pages/admin/orders/show.blade.php`
- `ai/task/TASK-002-order-whatsapp-template.md`

## Root Cause

Sebelumnya tombol WhatsApp di `show.blade.php` hanya membuat link statis `wa.me/{number}` tanpa menyertakan query parameter `?text=` yang di-encode dari template pesan follow-up.

## Scope

- Memperbarui `show.blade.php` untuk melakukan normalisasi nomor WhatsApp student (`08...` ➔ `628...`).
- Menyusun template pesan WhatsApp dinamis untuk order berstatus `pending`.
- Melakukan URL encoding aman dengan `rawurlencode()` agar baris baru, emoji, dan karakter khusus ter-encode sempurna.
- Memperbarui label tombol menjadi `Follow Up via WhatsApp` dan menangani state jika nomor WhatsApp kosong.

## Out of Scope

- Membuat WhatsApp gateway / pengiriman pesan otomatis server-side.
- Mengubah alur bisnis order, payment, approval, rejection, atau enrollment.
- Mengubah database atau membuat migration.
- Mengirim nomor rekening bank di dalam pesan.
- Memperbarui Email Notification atau Certificate Layout.

## Proposed Solution

Di dalam file `resources/views/pages/admin/orders/show.blade.php`, menambahkan logika penyiapan pesan dan URL WhatsApp pada blok `@php` di awal view dan memperbarui tombol di sidebar student info.

## Risks

- **Format Nomor HP Beragam**: Siswa mengisi `08...`, `+62...`, `62...`, atau menggunakan spasi/strip.
  - *Mitigasi*: Pembersihan `preg_replace('/[^0-9]/', '', ...)` dan penggantian awalan `0` ➔ `62` atau `8` ➔ `628`.
- **Karakter Khusus / Emoji dalam Pesan**:
  - *Mitigasi*: Penggunaan `rawurlencode()` memastikan karakter emoji (`👋`, `🙏`), baris baru (`\n`), dan simbol khusus terkonversi dengan aman untuk query string URL.

## Implementation Plan

1. Audit read-only (SELESAI - status `AUDITING`).
2. Menunggu persetujuan user atas draft template dan rencana patch (SELESAI - status `READY` ➔ `IN_PROGRESS`).
3. Memperbarui `resources/views/pages/admin/orders/show.blade.php` (SELESAI).
4. Pengujian teknis (`php artisan optimize:clear`) (SELESAI - status `TESTING`).
5. Manual testing oleh user (MENUNGGU CONFIRMATION).

## Implementation Result

Patch minimal telah diterapkan pada `resources/views/pages/admin/orders/show.blade.php`:
1. Normalisasi nomor WhatsApp student (`08...`, `8...`, `+628...` ➔ `628...`).
2. Template pesan follow-up pembayaran dinamis untuk order `pending` dengan format tanggal `d-m-Y H:i` dan formatting bold WhatsApp (`*...*`).
3. URL encoding aman menggunakan `rawurlencode()`.
4. Kondisi tombol:
   - Order `pending` ➔ `Follow Up via WhatsApp` dengan template pesan.
   - Order non-pending (`approved`/`rejected`/`cancelled`) ➔ `Open WhatsApp` tanpa template pending.
   - Nomor kosong ➔ state disabled `WhatsApp Not Available`.

## Technical Test Result

- **Cache Clearing (`php artisan optimize:clear`)**: Lolos 100% (config, cache, routes, views cleared successfully).
- **Target File**: Only `resources/views/pages/admin/orders/show.blade.php` modified.

## Remaining Notes

- Status task saat ini adalah **`TESTING`** dan **tidak akan diubah menjadi `COMPLETED`** sebelum user melakukan manual testing dan memberikan persetujuan final.

## Proposed WhatsApp Template

Template final yang telah diterapkan:

```text
Halo Kak *{{ $student->name }}* 👋

Perkenalkan, kami dari *Queens English Prestige*.

Kami telah menerima pemesanan kursus Kakak dengan detail berikut:

*Kode Order:* {{ $order->order_code }}
*Kursus:* {{ $courseLevel->name }}
*Biaya Kursus:* Rp {{ number_format((float) $order->price, 0, ',', '.') }}
*Tanggal Pemesanan:* {{ ($order->order_date ?? $order->created_at)->format('d-m-Y H:i') }}

Saat ini pesanan Kakak masih menunggu konfirmasi pembayaran.

Silakan balas pesan ini agar kami dapat memberikan informasi pembayaran dan membantu proses aktivasi kursus Kakak.

Terima kasih 🙏
*Queens English Prestige*
```

## Testing Checklist (User Verification)

- [ ] Order pending dengan nomor format `+628...` ➔ Link `wa.me/628...` dengan template terisi.
- [ ] Order pending dengan nomor format `08...` ➔ Link `wa.me/628...` dengan template terisi.
- [ ] Order pending dengan nomor format `628...` ➔ Link `wa.me/628...` dengan template terisi.
- [ ] Order pending dengan nomor format `8...` ➔ Link `wa.me/628...` dengan template terisi.
- [ ] Nomor memiliki spasi atau strip ➔ Karakter non-digit dibersihkan.
- [ ] Student tidak memiliki nomor WhatsApp ➔ Tampil badge `WhatsApp Not Available` (tidak broken link).
- [ ] Nama student / course memiliki simbol khusus (seperti `&`, `#`) ➔ URL encoding aman via `rawurlencode()`.
- [ ] Tanggal menggunakan format numerik `d-m-Y H:i`.
- [ ] Line break (`\n`), bold (`*...*`), dan emoji (`👋`, `🙏`) terbaca dengan rapi di WhatsApp Web / App.
- [ ] Klik tombol membuka tab baru (`target="_blank"` & `rel="noopener noreferrer"`).
- [ ] Pesan TIDAK terkirim otomatis (membutuhkan tombol Send manual oleh admin).
- [ ] Order approved/rejected ➔ Menampilkan `Open WhatsApp` tanpa template pending.
- [ ] Alur bisnis order, payment, dan enrollment tidak mengalami perubahan.
