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
            ->with([
                'courseLevels' => function ($q) {
                    $q->withCount(['modules', 'finalExams']);
                }
            ])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $nextSortOrder = (CourseProgram::max('sort_order') ?? 0) + 1;
        $totalPrograms = $coursePrograms->count();
        $activePrograms = $coursePrograms->where('is_active', true)->count();
        $totalLevels = $coursePrograms->sum('course_levels_count');

        return view('pages.admin.course-management.programs.index', compact(
            'coursePrograms',
            'nextSortOrder',
            'totalPrograms',
            'activePrograms',
            'totalLevels'
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

        if (!isset($validated['sort_order']) || $validated['sort_order'] === '' || $validated['sort_order'] === null) {
            $validated['sort_order'] = (CourseProgram::max('sort_order') ?? 0) + 1;
        }

        $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : true;

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

        if (!isset($validated['sort_order']) || $validated['sort_order'] === '' || $validated['sort_order'] === null) {
            $validated['sort_order'] = $courseProgram->sort_order;
        }

        $validated['is_active'] = $request->boolean('is_active');

        $courseProgram->update($validated);

        return redirect()
            ->route('admin.course-management.programs.index')
            ->with('success', 'Course program has been updated successfully.');
    }

    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['required', 'integer', 'exists:course_programs,id'],
        ]);

        $orderIds = $validated['order'];

        if (count($orderIds) !== count(array_unique($orderIds))) {
            return redirect()
                ->route('admin.course-management.programs.index')
                ->with('error', 'Duplicate program IDs detected in reorder request.');
        }

        $totalProgramsCount = CourseProgram::count();
        if (count($orderIds) !== $totalProgramsCount) {
            return redirect()
                ->route('admin.course-management.programs.index')
                ->with('error', 'Invalid program order payload length.');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($orderIds) {
            foreach ($orderIds as $index => $id) {
                CourseProgram::where('id', $id)->update(['sort_order' => $index + 1]);
            }
        });

        return redirect()
            ->route('admin.course-management.programs.index')
            ->with('success', 'Course program order updated successfully.');
    }

    public function destroy(CourseProgram $courseProgram)
    {
        if ($courseProgram->courseLevels()->exists()) {
            return redirect()
                ->route('admin.course-management.programs.index')
                ->with('error', 'Cannot delete program because it contains active course levels.');
        }

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
