# TASK-007 — Course Program UX Defaults (Auto-Active & Auto-Increment Sort Order)

**Status**: COMPLETED
**Date Created**: 2026-07-25  
**Priority**: Medium  
**Author**: AI Pair Programmer (Antigravity)  

---

## 1. Background

Saat ini Admin mengelola Course Program melalui modal form Create/Edit pada URL `/admin/course-management/programs`.
Meskipun modal create sudah mencoba menampilkan default `nextSortOrder` dan checkbox `Active` tercentang di UI, terdapat bug operasional di mana jika Admin meninggalkan input `sort_order` dalam keadaan kosong (empty string), Controller menyimpan nilai `0` bukannya `MAX(sort_order) + 1`.

---

## 2. Root Cause

1. Pada [CourseProgramController.php:L38](file:///d:/Kerja%20File/Freelance/E-Course%20Queens/Coding/Real/queens-english-prestige/app/Http/Controllers/Admin/CourseManagement/CourseProgramController.php#L38):
   ```php
   $validated['sort_order'] = $validated['sort_order'] ?? 0;
   ```
   Ketika request dikirim dari form dengan `sort_order` kosong atau `null`, operator null coalescing (`??`) pada PHP tidak menangkap string kosong `""`, sehingga `$validated['sort_order']` jatuh ke fallback `0`.
2. Checkbox `Active` pada Create Modal:
   HTML form Blade sudah diset `:checked="true"`, namun perlakuan default di controller perlu memastikan `is_active` selalu bernilai `true` jika tidak dikirim dalam request create.

---

## 3. Existing Behavior

- Admin membuat Course Program baru.
- Modal menampilkan sort_order ter-prefill (misal `3`).
- Jika Admin menghapus isi sort_order dan menekan Save, program tersimpan dengan `sort_order = 0`.
- Akibatnya, urutan tabel mengacak urutan program lain yang memiliki `sort_order > 0`.

---

## 4. Approved Business Decisions

1. **Saat Create Course Program**:
   - `is_active` default `true` (checkbox Active otomatis tercentang).
   - `sort_order` otomatis `MAX(sort_order) + 1` jika input dikosongkan oleh Admin.
   - `sort_order` tetap dapat diisi dan diedit secara manual oleh Admin.
2. **Saat Edit Course Program**:
   - Mempertahankan nilai `is_active` existing milik record.
   - Mempertahankan nilai `sort_order` existing milik record.
   - Admin tetap dapat menonaktifkan program (`is_active = false`).
   - Nilai existing tidak boleh tertimpa oleh default value create.

---

## 5. Scope

- Refactoring method `store()` dan `update()` pada `App\Http\Controllers\Admin\CourseManagement\CourseProgramController`.
- Memastikan partial modal Blade (`create-modal.blade.php` dan `edit-modal.blade.php`) mengirimkan state default yang konsisten.
- Penambahan test suite untuk memverifikasi behavior create & edit `CourseProgram`.

---

## 6. Out of Scope

- Perubahan schema database `course_programs`.
- Perubahan relasi antara `CourseProgram` dengan `CourseLevel` atau `Course`.
- Redesign tampilan UI tabel Course Program.

---

## 7. Database Impact

**ZERO DATABASE IMPACT.**
Tabel `course_programs` sudah memiliki kolom `sort_order` (`integer`) dan `is_active` (`boolean`). Tidak ada migration baru yang diperlukan.

---

## 8. File Impact

- [app/Http/Controllers/Admin/CourseManagement/CourseProgramController.php](file:///d:/Kerja%20File/Freelance/E-Course%20Queens/Coding/Real/queens-english-prestige/app/Http/Controllers/Admin/CourseManagement/CourseProgramController.php)
- [resources/views/partials/admin/course-management/programs/create-modal.blade.php](file:///d:/Kerja%20File/Freelance/E-Course%20Queens/Coding/Real/queens-english-prestige/resources/views/partials/admin/course-management/programs/create-modal.blade.php)
- [resources/views/partials/admin/course-management/programs/edit-modal.blade.php](file:///d:/Kerja%20File/Freelance/E-Course%20Queens/Coding/Real/queens-english-prestige/resources/views/partials/admin/course-management/programs/edit-modal.blade.php)

---

## 9. Minimal Patch Plan

### In `CourseProgramController.php`:
```php
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'slug' => ['nullable', 'string', 'max:255', 'unique:course_programs,slug'],
        'sort_order' => ['nullable', 'integer', 'min:0'],
        'is_active' => ['nullable', 'boolean'],
    ]);

    $validated['slug'] = $this->generateUniqueSlug($validated['slug'] ?? $validated['name']);
    
    // Auto-calculate MAX(sort_order) + 1 if sort_order is empty or not provided
    if (! filled($request->input('sort_order'))) {
        $validated['sort_order'] = ((int) CourseProgram::max('sort_order')) + 1;
    } else {
        $validated['sort_order'] = (int) $request->input('sort_order');
    }

    // Default is_active to true on create if not explicitly false
    $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : true;

    CourseProgram::create($validated);

    return redirect()
        ->route('admin.course-management.programs.index')
        ->with('success', 'Course program has been created successfully.');
}
```

---

## 10. Implementation Phases

- **Phase A**: Controller logic update (`store()` & `update()`).
- **Phase B**: Form view blade verification.
- **Phase C**: Verification & automated test execution.

---

## 11. Backward Compatibility & Risks

- **Compatibility**: 100% Backward compatible. Program existing tidak akan terpengaruh.
- **Production Risks**: Sangat rendah (Low). Hanya memperbaiki penanganan fallback input kosong.

---

## 12. Testing Checklist

- [ ] Create program baru dengan `sort_order` diisi manual (misal 5) $\rightarrow$ tersimpan `sort_order = 5`.
- [ ] Create program baru dengan `sort_order` dikosongkan $\rightarrow$ tersimpan `MAX(sort_order) + 1`.
- [ ] Create program baru tanpa menyentuh checkbox Active $\rightarrow$ tersimpan `is_active = true`.
- [ ] Edit program existing $\rightarrow$ mempertahankan `sort_order` & `is_active` milik record existing.
- [ ] Edit program existing dan matikan Active $\rightarrow$ tersimpan `is_active = false`.

---

## 13. Acceptance Criteria

1. Course Program baru yang dibuat tanpa mengisi `sort_order` terisi nilai urutan tertinggi berikutnya secara otomatis.
2. Course Program baru berstatus Active secara default.
3. Edit Course Program tidak menimpa data urutan atau status aktif secara tidak disengaja.

---

## 14. Rollback Plan

Jika terjadi masalah, kembalikan file `CourseProgramController.php` ke revisi sebelumnya menggunakan `git checkout`.
