<?php

namespace App\Http\Controllers\Admin\CourseManagement;

use App\Http\Controllers\Controller;
use App\Models\CourseProgram;
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
        $validated['is_preview'] = $request->boolean('is_preview');
        $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : true;
        $module = \Illuminate\Support\Facades\DB::transaction(function () use ($courseLevel, $validated) {
            $lockedLevel = CourseLevel::whereKey($courseLevel->id)->lockForUpdate()->firstOrFail();
            $validated['sort_order'] = $validated['sort_order'] ?? (((int) $lockedLevel->modules()->max('sort_order')) + 1);

            return Module::create($validated);
        });

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Course module has been created successfully.',
                'module_id' => $module->id,
                'redirect_node' => [
                    'level' => $courseLevel->id,
                    'module' => $module->id,
                    'exam' => null,
                    'tab' => 'overview'
                ]
            ]);
        }

        return redirect()
            ->route('admin.course-management.levels.modules.index', $courseLevel)
            ->with('success', 'Course module has been created successfully.');
    }

    public function edit(Request $request, Module $module)
    {
        $module->load('courseLevel.courseProgram');

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'id' => $module->id,
                    'course_level_id' => $module->course_level_id,
                    'title' => $module->title,
                    'slug' => $module->slug,
                    'short_description' => $module->short_description,
                    'sort_order' => $module->sort_order,
                    'is_preview' => (bool) $module->is_preview,
                    'is_active' => (bool) $module->is_active,
                    'update_url' => route('admin.course-management.modules.update', $module->id),
                ]
            ]);
        }

        return view('pages.admin.course-management.modules.edit', compact('module'));
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

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Module has been updated successfully.',
                'module_id' => $module->id,
                'redirect_node' => [
                    'level' => $module->course_level_id,
                    'module' => $module->id,
                    'exam' => null,
                    'tab' => 'overview'
                ]
            ]);
        }

        return redirect()
            ->route('admin.course-management.levels.modules.index', $module->courseLevel)
            ->with('success', 'Module has been updated successfully.');
    }

    public function destroy(Request $request, Module $module)
    {
        $courseLevel = $module->courseLevel;

        // Delete Guard: Prevent forced cascade delete if module contains materials or practices
        if ($module->materials()->exists() || $module->practices()->exists()) {
            $msg = 'Cannot delete module "' . $module->title . '" because it contains learning materials or practice quizzes. Please remove them first.';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $msg
                ], 422);
            }
            return back()->withErrors(['delete' => $msg]);
        }

        $module->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Module has been deleted successfully.',
                'redirect_node' => [
                    'level' => $courseLevel->id,
                    'module' => null,
                    'exam' => null,
                    'tab' => 'overview'
                ]
            ]);
        }

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

    public function builderReorder(Request $request, CourseProgram $courseProgram, CourseLevel $courseLevel)
    {
        if ($courseLevel->course_program_id !== $courseProgram->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized operation: course level does not belong to this program.'
            ], 403);
        }

        $validated = $request->validate([
            'ordered_ids' => ['required', 'array'],
            'ordered_ids.*' => ['required', 'integer'],
            'original_ordered_ids' => ['nullable', 'array'],
            'original_ordered_ids.*' => ['integer'],
        ]);

        $orderedIds = array_map('intval', $validated['ordered_ids']);
        $originalOrderedIds = isset($validated['original_ordered_ids']) ? array_map('intval', $validated['original_ordered_ids']) : null;

        try {
            return \Illuminate\Support\Facades\DB::transaction(function () use ($courseLevel, $orderedIds, $originalOrderedIds) {
                // 1. Explicitly execute parent SQL query lock
                $lockedLevel = CourseLevel::whereKey($courseLevel->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                // 2. Lock & read current server siblings
                $siblings = $lockedLevel->modules()
                    ->orderBy('sort_order')
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get(['id', 'sort_order']);

                $currentServerOrderedIds = $siblings->pluck('id')->values()->all();

                $existingIdsCopy = $currentServerOrderedIds;
                $orderedIdsCopy = $orderedIds;
                sort($existingIdsCopy);
                sort($orderedIdsCopy);

                if (count($orderedIds) !== count($currentServerOrderedIds) || $existingIdsCopy !== $orderedIdsCopy || count(array_unique($orderedIds)) !== count($orderedIds)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'The item list has changed. Reload the latest order and try again.'
                    ], 422);
                }

                if ($originalOrderedIds !== null && $currentServerOrderedIds !== $originalOrderedIds) {
                    return response()->json([
                        'status' => 'conflict',
                        'message' => 'The order has been changed by another administrator. Reload the latest order and try again.'
                    ], 409);
                }

                foreach ($orderedIds as $index => $id) {
                    Module::where('id', $id)->update(['sort_order' => $index + 1]);
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'Modules order updated successfully.'
                ]);
            });
        } catch (\Illuminate\Database\QueryException $e) {
            if ($this->isConcurrencyException($e)) {
                return response()->json([
                    'status' => 'conflict',
                    'message' => 'A database concurrency conflict occurred. Reload the latest order and try again.'
                ], 409);
            }
            \Illuminate\Support\Facades\Log::error('Module reorder query error: ' . $e->getMessage(), ['exception' => $e]);
            throw $e;
        }
    }

    protected function isConcurrencyException(\Illuminate\Database\QueryException $e): bool
    {
        $sqlState = (string) $e->getCode();
        $driverCode = $e->errorInfo[1] ?? null;

        return $sqlState === '40001' || $driverCode === 1213 || $driverCode === 1205;
    }
}
