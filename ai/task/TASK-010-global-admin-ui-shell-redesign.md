# TASK-010 — Global Admin UI Shell Redesign

**Status**: IN PROGRESS  
**Date Created**: 2026-07-26  
**Priority**: Medium-High  
**Author**: AI Pair Programmer (Antigravity)  

---

## 1. Background & Goals

Tampilan Admin Queens English Prestige saat ini secara fungsional lengkap, namun membutuhkan pembaruan arsitektur visual shell agar terasa lebih modern, premium, bersih, responsif, dan konsisten.

### Main Objectives:
1. **Identitas Visual Premium**: Mengadopsi kombinasi warna utama:
   - Dominan Putih (`#FFFFFF`) untuk surface & cards.
   - Deep Navy (`#080D4D` / `#0A1128` / `#0F172A`) sebagai warna brand utama & header/navigation highlight.
   - Akses Gold (`#D4AF37` / `#C59B27` / `#AD6B10` / `amber-500`) untuk indikator aktif, badge, dan aksen premium.
   - Soft Blue-Gray (`#F8FAFC` / `#F1F5F9`) sebagai background halaman global.
2. **Desktop Collapsible Sidebar**: Menyediakan fitur toggle Sidebar (Expanded ~270px vs Collapsed ~80px) dengan `localStorage` state (`admin.sidebar.collapsed`) tanpa content jump.
3. **Mobile Off-Canvas Drawer**: Mobile/tablet sidebar menjadi off-canvas drawer dengan backdrop overlay, body scroll lock, tombol close, dan handler ESC.
4. **Header Modern & Sticky**: Sticky topbar dengan breadcrumb/page title, modern search bar, unread notification indicator badge, dan compact profile dropdown.
5. **Backward Compatibility**: Mempertahankan seluruh 19 menu navigation Admin, route existing, authorization, dan nol perubahan pada Student UI & business logic.

---

## 2. Design Tokens

| Token Name | Value / Tailwind Mapping | Description |
| :--- | :--- | :--- |
| `admin-bg` | `#F8FAFC` (`bg-slate-50`) | Page background global |
| `admin-surface` | `#FFFFFF` (`bg-white`) | Card & Header background |
| `admin-navy` | `#080D4D` (`text-[#080D4D]`, `bg-[#080D4D]`) | Brand primary color |
| `admin-navy-soft` | `#EEF2FF` (`bg-indigo-50/70`) | Active menu item soft background |
| `admin-gold` | `#D4AF37` / `#C59B27` / `amber-500` | Accent bar, active badge, highlight |
| `admin-border` | `#E2E8F0` (`border-slate-200`) | Subtle card/header borders |
| `admin-muted` | `#64748B` (`text-slate-500`) | Secondary label & muted text |
| `sidebar-expanded` | `17rem` (`272px`) | Desktop sidebar expanded width |
| `sidebar-collapsed` | `5rem` (`80px`) | Desktop sidebar collapsed width |
| `header-height` | `4.25rem` (`68px`) | Sticky topbar height |

---

## 3. Implementation Phases

### Phase A — Audit & Design Foundation (IMPLEMENTED & TESTED 2026-07-26)
- Audit master layout `layouts/admin.blade.php`, `sidebar.blade.php`, `topbar.blade.php`, `sidebar-item.blade.php`, `profile-menu.blade.php`, `topbar-search.blade.php`.
- Definisi token warna brand (Navy, White, Gold, Soft Blue-Gray).
- Pembuatan komponen reusable icon dan styling helper.

### Phase B — Global Admin Shell (IMPLEMENTED & TESTED 2026-07-26)
- Redesign Master Admin Layout (`layouts/admin.blade.php`) dengan Alpine JS global shell state (desktop collapsed + mobile drawer).
- Persist `admin.sidebar.collapsed` di `localStorage`.
- Redesign Desktop Collapsible Sidebar & Mobile Drawer di `partials/admin/sidebar.blade.php` & `components/admin/sidebar-item.blade.php`.
- Visual Correction Branding: Struktur brand sidebar 3 baris presisi (`Queens English` / `Prestige` / `ADMIN`), penguncian warna brand gold ke `#AD6B10`, collapsed tooltip `Queens English Prestige — Admin`.
- Redesign Sticky Header di `partials/admin/topbar.blade.php`, `topbar-search.blade.php`, & `profile-menu.blade.php`.
- Custom tooltip untuk collapsed mode sidebar & focus ring accessibility.
- Integrasi aman dengan Course Builder drawers & Admin modals.

### Phase C — Dashboard Polish (IMPLEMENTED & TESTED 2026-07-26 — Round 2 Compact Revision)
- Visual refinement & compact redesign:
  - Intro Banner: White-first compact hero (`border-t-2 border-t-[#AD6B10] bg-white`), eliminasi duplikasi quick stats.
  - Metric Cards: Kartu ringkas 130–150px dengan icon 36px & typographical focus pada nilai data.
  - Revenue Analytics: Single panel compact (`h-[210px]` SVG chart), eliminasi 2 side summary card duplikat.
  - Action Center: Berubah dari card besar bertumpuk menjadi compact list rows dengan divider halus.
  - Waiting Reviews & Activity: Empty state ringkas (160–190px), activity feed tunggal dengan divider antar-row.
  - Recent Transactions: Tabel padat data (`py-3.5`), tabular-nums price, dan compact detail buttons.
- Penerapan tema visual white-first (`bg-white`), border neutral (`border-slate-200/90`), Deep Navy (`#080D4D`), dan Brand Gold (`#AD6B10`).
- Nol perubahan pada Controller, Model, DB Schema, Query, atau Business Logic.

### Phase D — Shared Admin Components (REMAINING)
- Standardization pada DataTables, Action Badges, Action Buttons, Filter Cards, dan Modal dialogs.

### Phase E — Page Refinement & Regression (REMAINING)
- Fine-tuning halaman detail Admin dan pengujian regresi penuh.

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
