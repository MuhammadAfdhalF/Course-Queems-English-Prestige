<?php

namespace App\Http\Controllers\Admin\CourseManagement;

use App\Http\Controllers\Controller;
use App\Models\CourseLevel;
use App\Models\CourseProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseLevelController extends Controller
{
    public function index(CourseProgram $courseProgram)
    {
        $courseLevels = $courseProgram->courseLevels()
            ->withCount('modules')
            ->orderBy('sort_order')
            ->latest()
            ->get();

        return view('pages.admin.course-management.levels.index', compact(
            'courseProgram',
            'courseLevels'
        ));
    }

    public function create(CourseProgram $courseProgram)
    {
        $nextSortOrder = ((int) $courseProgram->courseLevels()->max('sort_order')) + 1;

        return view('pages.admin.course-management.levels.create', compact(
            'courseProgram',
            'nextSortOrder'
        ));
    }

    public function store(Request $request, CourseProgram $courseProgram)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:course_levels,slug'],
            'thumbnail_type' => ['required', 'in:image,video'],
            'thumbnail_file' => ['nullable', 'file', 'max:20480'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'access_type' => ['required', 'in:lifetime,limited'],
            'access_duration_days' => ['nullable', 'integer', 'min:1'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($validated['thumbnail_type'] === 'image') {
            $request->validate([
                'thumbnail_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            ]);
        }

        if ($validated['thumbnail_type'] === 'video') {
            $request->validate([
                'thumbnail_file' => ['nullable', 'mimes:mp4,webm,mov', 'max:20480'],
            ]);
        }

        if ($validated['access_type'] === 'limited' && empty($validated['access_duration_days'])) {
            return back()
                ->withErrors(['access_duration_days' => 'Access duration is required when access type is limited.'])
                ->withInput();
        }

        $validated['course_program_id'] = $courseProgram->id;
        $validated['slug'] = $this->generateUniqueSlug($validated['slug'] ?? $validated['name']);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active');

        if ($validated['access_type'] === 'lifetime') {
            $validated['access_duration_days'] = null;
        }

        if ($request->hasFile('thumbnail_file')) {
            $validated['thumbnail_file'] = $request->file('thumbnail_file')
                ->store('course-levels/thumbnails', 'public');
        }

        CourseLevel::create($validated);

        return redirect()
            ->route('admin.course-management.programs.levels.index', $courseProgram)
            ->with('success', 'Course level has been created successfully.');
    }

    public function edit(CourseLevel $courseLevel)
    {
        $courseLevel->load('courseProgram');

        return view('pages.admin.course-management.levels.edit', compact('courseLevel'));
    }

    public function update(Request $request, CourseLevel $courseLevel)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:course_levels,slug,' . $courseLevel->id],
            'thumbnail_type' => ['required', 'in:image,video'],
            'thumbnail_file' => ['nullable', 'file', 'max:20480'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'access_type' => ['required', 'in:lifetime,limited'],
            'access_duration_days' => ['nullable', 'integer', 'min:1'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($validated['thumbnail_type'] === 'image') {
            $request->validate([
                'thumbnail_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            ]);
        }

        if ($validated['thumbnail_type'] === 'video') {
            $request->validate([
                'thumbnail_file' => ['nullable', 'mimes:mp4,webm,mov', 'max:20480'],
            ]);
        }

        if ($validated['access_type'] === 'limited' && empty($validated['access_duration_days'])) {
            return back()
                ->withErrors(['access_duration_days' => 'Access duration is required when access type is limited.'])
                ->withInput();
        }

        $validated['slug'] = $this->generateUniqueSlug(
            $validated['slug'] ?? $validated['name'],
            $courseLevel->id
        );

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active');

        if ($validated['access_type'] === 'lifetime') {
            $validated['access_duration_days'] = null;
        }

        if ($request->hasFile('thumbnail_file')) {
            if ($courseLevel->thumbnail_file) {
                Storage::disk('public')->delete($courseLevel->thumbnail_file);
            }

            $validated['thumbnail_file'] = $request->file('thumbnail_file')
                ->store('course-levels/thumbnails', 'public');
        }

        $courseLevel->update($validated);

        return redirect()
            ->route('admin.course-management.programs.levels.index', $courseLevel->courseProgram)
            ->with('success', 'Course level has been updated successfully.');
    }

    public function destroy(CourseLevel $courseLevel)
    {
        $courseProgram = $courseLevel->courseProgram;

        if ($courseLevel->thumbnail_file) {
            Storage::disk('public')->delete($courseLevel->thumbnail_file);
        }

        $courseLevel->delete();

        return redirect()
            ->route('admin.course-management.programs.levels.index', $courseProgram)
            ->with('success', 'Course level has been deleted successfully.');
    }

    private function generateUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($value);
        $slug = $baseSlug;
        $counter = 1;

        while (
            CourseLevel::query()
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
