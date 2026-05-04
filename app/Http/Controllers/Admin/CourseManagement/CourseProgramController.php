<?php

namespace App\Http\Controllers\Admin\CourseManagement;

use App\Http\Controllers\Controller;
use App\Models\CourseProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseProgramController extends Controller
{
    public function index()
    {
        $coursePrograms = CourseProgram::query()
            ->withCount('courseLevels')
            ->orderBy('sort_order')
            ->latest()
            ->get();

        $nextSortOrder = (CourseProgram::max('sort_order') ?? 0) + 1;

        return view('pages.admin.course-management.programs.index', compact(
            'coursePrograms',
            'nextSortOrder'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:course_programs,slug'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['slug'] = $this->generateUniqueSlug($validated['slug'] ?? $validated['name']);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active');

        CourseProgram::create($validated);

        return redirect()
            ->route('admin.course-management.programs.index')
            ->with('success', 'Course program has been created successfully.');
    }

    public function update(Request $request, CourseProgram $courseProgram)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:course_programs,slug,' . $courseProgram->id],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['slug'] = $this->generateUniqueSlug(
            $validated['slug'] ?? $validated['name'],
            $courseProgram->id
        );

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active');

        $courseProgram->update($validated);

        return redirect()
            ->route('admin.course-management.programs.index')
            ->with('success', 'Course program has been updated successfully.');
    }

    public function destroy(CourseProgram $courseProgram)
    {
        $courseProgram->delete();

        return redirect()
            ->route('admin.course-management.programs.index')
            ->with('success', 'Course program has been deleted successfully.');
    }

    private function generateUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($value);
        $slug = $baseSlug;
        $counter = 1;

        while (
            CourseProgram::query()
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
