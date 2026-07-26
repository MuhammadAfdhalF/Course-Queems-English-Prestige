# TASK-010 — Global Admin UI Shell Redesign

**Status**: COMPLETED & CLOSED (2026-07-26)
**Date Created**: 2026-07-26
**Date Completed**: 2026-07-26
**Priority**: Medium-High
**Author**: AI Pair Programmer (Antigravity)

---

## 1. Background & Goals

Tampilan Admin Queens English Prestige saat ini secara fungsional lengkap, namun membutuhkan pembaruan arsitektur visual shell agar terasa lebih modern, premium, bersih, responsif, dan konsisten.

### Main Objectives:
1. **Identitas Visual Premium**: Mengadopsi kombinasi warna utama:
   - Dominan Putih (`#FFFFFF`) untuk surface & cards.
   - Deep Navy (`#080D4D` / `#0A1128` / `#0F172A`) sebagai warna brand utama & header/navigation highlight.
   - Akses Gold (`#AD6B10`) untuk indikator aktif, badge, dan aksen premium.
   - Soft Blue-Gray (`#F8FAFC` / `#F1F5F9`) sebagai background halaman global.
2. **Desktop Collapsible Sidebar**: Menyediakan fitur toggle Sidebar (Expanded ~272px vs Collapsed ~80px) dengan `localStorage` state (`admin.sidebar.collapsed`) tanpa content jump.
3. **Mobile Off-Canvas Drawer**: Mobile/tablet sidebar menjadi off-canvas drawer dengan backdrop overlay, body scroll lock, tombol close, dan handler ESC.
4. **Header Modern & Sticky**: Sticky topbar dengan page title, unread notification indicator badge, dan compact profile dropdown.
5. **Backward Compatibility**: Mempertahankan seluruh 19 menu navigation Admin, route existing, authorization, dan nol perubahan pada Student UI & business logic.

---

## 2. Design Tokens

| Token Name | Value / Tailwind Mapping | Description |
| :--- | :--- | :--- |
| **Surface Background** | `#F8FAFC` / `bg-slate-50/70` | Background halaman global |
| **Card / Shell Surface** | `#FFFFFF` / `bg-white` | White-first card surface |
| **Brand Deep Navy** | `#080D4D` | Brand primary navy text, active state, header accents |
| **Brand Gold Accent** | `#AD6B10` | Brand gold accent, pending badges, top intro indicators |
| **Border Neutral** | `border-slate-200/90` | Subtle clean card & divider borders |

---

## 3. Implementation Phases

### Phase A — Audit & Design Architecture (IMPLEMENTED & TESTED 2026-07-26)
- Audit 19 menu Admin, Blade layout structures, dan CSS variables.

### Phase B — Global Admin Shell (IMPLEMENTED & TESTED 2026-07-26)
- Redesign Master Admin Layout (`layouts/admin.blade.php`) dengan Alpine JS global shell state (desktop collapsed + mobile drawer).
- Persist `admin.sidebar.collapsed` di `localStorage`.
- Redesign Desktop Collapsible Sidebar & Mobile Drawer di `partials/admin/sidebar.blade.php` & `components/admin/sidebar-item.blade.php`.
- Visual Correction Branding: Struktur brand sidebar 3 baris presisi (`Queens English` / `Prestige` / `ADMIN`), penguncian warna brand gold ke `#AD6B10`, collapsed tooltip `Queens English Prestige — Admin`.
- Redesign Sticky Header di `partials/admin/topbar.blade.php`, `topbar-search.blade.php`, & `profile-menu.blade.php`.

### Phase C — Dashboard Polish (IMPLEMENTED & TESTED 2026-07-26)
- Visual refinement & compact redesign: Intro Banner, Metric Cards, Revenue Analytics, Action Center, dan Recent Transactions.

### Phase D — Shared Admin Components (IMPLEMENTED & TESTED 2026-07-26)
- Standardisasi komponen dasar UI Admin Queens English Prestige:
  - `<x-admin.page-header>`: Hierarki judul compact, eyebrow dot gold (`#AD6B10`), dan slot aksi seragam.
  - `<x-admin.button>`: Komponen tombol & link seragam.
  - `<x-admin.status-badge>`: Pemetaan visual badge yang konsisten.
  - `<x-admin.empty-state>`: Mendukung mode tabel (`colspan`) dan mode standalone card ringkas.
  - `<x-admin.table-card>`: Container panel dengan `rounded-2xl border-slate-200/90 shadow-2xs`.
  - Form Controls (`<x-admin.form.input>`, `<x-admin.form.select>`, `<x-admin.form.textarea>`): Focus ring seragam (`focus:border-[#080D4D] focus:ring-2 focus:ring-[#080D4D]/10`), label typography bersih.
- Proof of Concept (PoC) Migration: Halaman `admin.notifications.index` dimigrasikan secara bersih tanpa mengubah business logic atau route.

### Task Closure & Scope Decisions (2026-07-26)
1. **Admin Topbar Search Hidden**: Form pencarian di topbar disembunyikan sementara dari `partials/admin/topbar.blade.php` agar tidak menyesatkan pengguna sebelum behavior pencarian nyata dibangun.
2. **Search Component Preserved**: Komponen `resources/views/components/admin/topbar-search.blade.php` tetap dipertahankan 100% utuh tanpa dihapus untuk kebutuhan Quick Navigation Search di task mendatang.
3. **Phase E Admin Page Refinement**: Refactoring halaman detail Admin individual di luar PoC `admin.notifications.index` tidak menjadi blocker closure TASK-010 dan akan dikerjakan pada task terpisah sesuai kebutuhan project.

---

## 4. Architectural Safety & Non-Goals

- **No Business Logic Changes**: Tidak ada perubahan pada Controller, Model, Service, atau DB.
- **No Migration / DB Changes**: Tidak menambah atau mengubah schema DB.
- **No CDN Dependency**: Menggunakan SVG inline & asset lokal.
- **No Student UI Impact**: Hanya mempengaruhi layout Admin.
- **Z-Index Layering**:
  - Main Page Content: `z-0`
  - Sticky Topbar: `z-30`
  - Mobile Backdrop Overlay: `z-40`
  - Desktop / Mobile Sidebar: `z-40` / `z-50`
  - Dropdown Menus: `z-50`
  - Course Builder Drawers / Modals: `z-50` / `z-60`

---

## 5. Verification Matrix

- [x] Desktop Expanded Sidebar View
- [x] Desktop Collapsed Sidebar View (dengan localStorage persistence)
- [x] Hover Tooltip pada Collapsed Sidebar Icon
- [x] Mobile Off-Canvas Drawer dengan Backdrop & ESC key handler
- [x] Sticky Header & Compact Profile Dropdown
- [x] Responsive layout dari 360px s/d 1536px
- [x] Clean asset build (`npm run build`)
- [x] Zero PHP syntax error & Test suite pass (`php artisan test`)
