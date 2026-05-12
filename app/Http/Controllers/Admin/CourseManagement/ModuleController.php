<?php

namespace App\Http\Controllers\Admin\CourseManagement;

use App\Http\Controllers\Controller;
use App\Models\CourseLevel;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ModuleController extends Controller
{
    public function index(CourseLevel $courseLevel)
    {
        $courseLevel->load('courseProgram');

        $modules = $courseLevel->modules()
            ->withCount(['materials', 'practices'])
            ->orderBy('sort_order')
            ->latest()
            ->get();

        $nextSortOrder = ((int) $courseLevel->modules()->max('sort_order')) + 1;

        return view('pages.admin.course-management.modules.index', compact(
            'courseLevel',
            'modules',
            'nextSortOrder'
        ));
    }

    public function store(Request $request, CourseLevel $courseLevel)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:modules,slug'],
            'short_description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_preview' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['course_level_id'] = $courseLevel->id;
        $validated['slug'] = $this->generateUniqueSlug($validated['slug'] ?? $validated['title']);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_preview'] = $request->boolean('is_preview');
        $validated['is_active'] = $request->boolean('is_active');

        Module::create($validated);

        return redirect()
            ->route('admin.course-management.levels.modules.index', $courseLevel)
            ->with('success', 'Module has been created successfully.');
    }

    public function update(Request $request, Module $module)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:modules,slug,' . $module->id],
            'short_description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_preview' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['slug'] = $this->generateUniqueSlug(
            $validated['slug'] ?? $validated['title'],
            $module->id
        );

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_preview'] = $request->boolean('is_preview');
        $validated['is_active'] = $request->boolean('is_active');

        $module->update($validated);

        return redirect()
            ->route('admin.course-management.levels.modules.index', $module->courseLevel)
            ->with('success', 'Module has been updated successfully.');
    }

    public function destroy(Module $module)
    {
        $courseLevel = $module->courseLevel;

        $module->delete();

        return redirect()
            ->route('admin.course-management.levels.modules.index', $courseLevel)
            ->with('success', 'Module has been deleted successfully.');
    }

    private function generateUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($value);
        $slug = $baseSlug;
        $counter = 1;

        while (
            Module::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn($query) => $query->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
