<?php

namespace App\Http\Controllers\Admin\CourseManagement;

use App\Http\Controllers\Controller;
use App\Models\CourseLevel;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'opening_media_type' => ['required', 'in:image,video'],
            'opening_media_file' => ['nullable', 'file', 'max:20480'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_preview' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($validated['opening_media_type'] === 'image') {
            $request->validate([
                'opening_media_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            ]);
        }

        if ($validated['opening_media_type'] === 'video') {
            $request->validate([
                'opening_media_file' => ['nullable', 'mimes:mp4,webm,mov', 'max:20480'],
            ]);
        }

        $validated['course_level_id'] = $courseLevel->id;
        $validated['slug'] = $this->generateUniqueSlug($validated['slug'] ?? $validated['title']);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_preview'] = $request->boolean('is_preview');
        $validated['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('opening_media_file')) {
            $validated['opening_media_file'] = $request->file('opening_media_file')
                ->store('modules/opening-media', 'public');
        }

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
            'opening_media_type' => ['required', 'in:image,video'],
            'opening_media_file' => ['nullable', 'file', 'max:20480'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_preview' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($validated['opening_media_type'] === 'image') {
            $request->validate([
                'opening_media_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            ]);
        }

        if ($validated['opening_media_type'] === 'video') {
            $request->validate([
                'opening_media_file' => ['nullable', 'mimes:mp4,webm,mov', 'max:20480'],
            ]);
        }

        $validated['slug'] = $this->generateUniqueSlug(
            $validated['slug'] ?? $validated['title'],
            $module->id
        );

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_preview'] = $request->boolean('is_preview');
        $validated['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('opening_media_file')) {
            if ($module->opening_media_file) {
                Storage::disk('public')->delete($module->opening_media_file);
            }

            $validated['opening_media_file'] = $request->file('opening_media_file')
                ->store('modules/opening-media', 'public');
        }

        $module->update($validated);

        return redirect()
            ->route('admin.course-management.levels.modules.index', $module->courseLevel)
            ->with('success', 'Module has been updated successfully.');
    }

    public function destroy(Module $module)
    {
        $courseLevel = $module->courseLevel;

        if ($module->opening_media_file) {
            Storage::disk('public')->delete($module->opening_media_file);
        }

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
 