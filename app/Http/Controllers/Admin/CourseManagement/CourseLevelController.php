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

    /**
     * Parse and normalize Indonesian Rupiah price strings or raw numeric values.
     * Returns integer raw price, null if empty, or -1 if invalid format or negative.
     */
    public static function parseRupiahPrice(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_int($value)) {
            return $value >= 0 ? $value : -1;
        }

        if (is_float($value)) {
            return ($value >= 0 && floor($value) == $value) ? (int) $value : -1;
        }

        $trimmed = trim((string) $value);

        if (preg_match('/^(?:Rp\s*)?(\d{1,3}(?:\.\d{3})+|\d+)$/i', $trimmed, $matches)) {
            $clean = str_replace('.', '', $matches[1]);
            return (int) $clean;
        }

        return -1;
    }

    public function store(Request $request, CourseProgram $courseProgram)
    {
        if ($request->has('price')) {
            $parsedPrice = static::parseRupiahPrice($request->input('price'));
            $request->merge(['price' => $parsedPrice]);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:course_levels,slug'],
            'thumbnail_type' => ['required', 'in:image,video'],
            'thumbnail_file' => ['nullable', 'file', 'max:20480'],
            'video_poster_file' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'learning_mode' => ['required', 'in:online,offline,hybrid'],
            'access_type' => ['required', 'in:lifetime,limited'],
            'access_duration_days' => ['nullable', 'integer', 'min:1'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($validated['thumbnail_type'] === 'image') {
            $request->validate([
                'thumbnail_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            ]);
            $validated['video_poster_file'] = null;
        }

        if ($validated['thumbnail_type'] === 'video') {
            $request->validate([
                'thumbnail_file' => ['required', 'file', 'mimes:mp4,webm,mov', 'max:20480'],
                'video_poster_file' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            ], [
                'thumbnail_file.required' => 'A course intro video file is required when video type is selected.',
                'video_poster_file.required' => 'A video poster image is required when video type is selected.',
            ]);
        }

        if ($validated['access_type'] === 'limited' && empty($validated['access_duration_days'])) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'The given data was invalid.',
                    'errors' => ['access_duration_days' => ['Access duration is required when access type is limited.']]
                ], 422);
            }
            return back()
                ->withErrors(['access_duration_days' => 'Access duration is required when access type is limited.'])
                ->withInput();
        }

        $validated['course_program_id'] = $courseProgram->id;
        $validated['slug'] = $this->generateUniqueSlug($validated['slug'] ?? $validated['name']);
        $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : true;

        if ($validated['access_type'] === 'lifetime') {
            $validated['access_duration_days'] = null;
        }

        if ($request->hasFile('thumbnail_file')) {
            $validated['thumbnail_file'] = $request->file('thumbnail_file')
                ->store('course-levels/thumbnails', 'public');
        }

        if ($validated['thumbnail_type'] === 'video' && $request->hasFile('video_poster_file')) {
            $validated['video_poster_file'] = $request->file('video_poster_file')
                ->store('course-levels/video-posters', 'public');
        }

        $level = \Illuminate\Support\Facades\DB::transaction(function () use ($courseProgram, $validated) {
            $lockedProgram = CourseProgram::whereKey($courseProgram->id)->lockForUpdate()->firstOrFail();
            $validated['sort_order'] = $validated['sort_order'] ?? (((int) $lockedProgram->courseLevels()->max('sort_order')) + 1);

            return CourseLevel::create($validated);
        });

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Course level has been created successfully.',
                'level_id' => $level->id,
                'redirect_node' => [
                    'level' => $level->id,
                    'module' => null,
                    'exam' => null,
                    'tab' => 'overview'
                ]
            ]);
        }

        return redirect()
            ->route('admin.course-management.programs.levels.index', $courseProgram)
            ->with('success', 'Course level has been created successfully.');
    }

    public function edit(Request $request, CourseLevel $courseLevel)
    {
        $courseLevel->load('courseProgram');

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'id' => $courseLevel->id,
                    'course_program_id' => $courseLevel->course_program_id,
                    'name' => $courseLevel->name,
                    'slug' => $courseLevel->slug,
                    'thumbnail_type' => $courseLevel->thumbnail_type ?? 'image',
                    'short_description' => $courseLevel->short_description,
                    'description' => $courseLevel->description,
                    'price' => (float) $courseLevel->price,
                    'learning_mode' => $courseLevel->learning_mode ?? 'online',
                    'access_type' => $courseLevel->access_type ?? 'lifetime',
                    'access_duration_days' => $courseLevel->access_duration_days,
                    'sort_order' => $courseLevel->sort_order,
                    'is_active' => (bool) $courseLevel->is_active,
                    'thumbnail_url' => $courseLevel->thumbnail_file ? Storage::url($courseLevel->thumbnail_file) : null,
                    'video_poster_url' => $courseLevel->video_poster_file ? Storage::url($courseLevel->video_poster_file) : null,
                    'update_url' => route('admin.course-management.levels.update', $courseLevel->id),
                ]
            ]);
        }

        return view('pages.admin.course-management.levels.edit', compact('courseLevel'));
    }

    public function update(Request $request, CourseLevel $courseLevel)
    {
        if ($request->has('price')) {
            $parsedPrice = static::parseRupiahPrice($request->input('price'));
            $request->merge(['price' => $parsedPrice]);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:course_levels,slug,' . $courseLevel->id],
            'thumbnail_type' => ['required', 'in:image,video'],
            'thumbnail_file' => ['nullable', 'file', 'max:20480'],
            'video_poster_file' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'learning_mode' => ['required', 'in:online,offline,hybrid'],
            'access_type' => ['required', 'in:lifetime,limited'],
            'access_duration_days' => ['nullable', 'integer', 'min:1'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $changingToImage = $validated['thumbnail_type'] === 'image' && $courseLevel->thumbnail_type === 'video';
        $changingToVideo = $validated['thumbnail_type'] === 'video' && $courseLevel->thumbnail_type === 'image';

        if ($validated['thumbnail_type'] === 'image') {
            if ($changingToImage && ! $request->hasFile('thumbnail_file')) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'message' => 'The given data was invalid.',
                        'errors' => ['thumbnail_file' => ['A course image is required when switching thumbnail type to image.']]
                    ], 422);
                }
                return back()
                    ->withErrors(['thumbnail_file' => 'A course image is required when switching thumbnail type to image.'])
                    ->withInput();
            }

            if ($request->hasFile('thumbnail_file')) {
                $request->validate([
                    'thumbnail_file' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
                ]);
            }
        }

        if ($validated['thumbnail_type'] === 'video') {
            if ($changingToVideo && ! $request->hasFile('thumbnail_file')) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'message' => 'The given data was invalid.',
                        'errors' => ['thumbnail_file' => ['A course intro video file is required when switching thumbnail type to video.']]
                    ], 422);
                }
                return back()
                    ->withErrors(['thumbnail_file' => 'A course intro video file is required when switching thumbnail type to video.'])
                    ->withInput();
            }

            if ($changingToVideo && ! $request->hasFile('video_poster_file')) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'message' => 'The given data was invalid.',
                        'errors' => ['video_poster_file' => ['A video poster image is required when switching thumbnail type to video.']]
                    ], 422);
                }
                return back()
                    ->withErrors(['video_poster_file' => 'A video poster image is required when switching thumbnail type to video.'])
                    ->withInput();
            }

            if ($request->hasFile('thumbnail_file')) {
                $request->validate([
                    'thumbnail_file' => ['mimes:mp4,webm,mov', 'max:20480'],
                ]);
            }

            if ($request->hasFile('video_poster_file')) {
                $request->validate([
                    'video_poster_file' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
                ]);
            }
        }

        if ($validated['access_type'] === 'limited' && empty($validated['access_duration_days'])) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'The given data was invalid.',
                    'errors' => ['access_duration_days' => ['Access duration is required when access type is limited.']]
                ], 422);
            }
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

        $oldThumbnailFile = $courseLevel->thumbnail_file;
        $oldVideoPosterFile = $courseLevel->video_poster_file;

        if ($request->hasFile('thumbnail_file')) {
            $validated['thumbnail_file'] = $request->file('thumbnail_file')
                ->store('course-levels/thumbnails', 'public');
        }

        if ($validated['thumbnail_type'] === 'video') {
            if ($request->hasFile('video_poster_file')) {
                $validated['video_poster_file'] = $request->file('video_poster_file')
                    ->store('course-levels/video-posters', 'public');
            }
        } else {
            $validated['video_poster_file'] = null;
        }

        $courseLevel->update($validated);

        // Safe cleanup of old files after update succeeds
        if ($request->hasFile('thumbnail_file') && $oldThumbnailFile && Storage::disk('public')->exists($oldThumbnailFile)) {
            Storage::disk('public')->delete($oldThumbnailFile);
        }

        if (($request->hasFile('video_poster_file') || $validated['thumbnail_type'] === 'image') && $oldVideoPosterFile && Storage::disk('public')->exists($oldVideoPosterFile)) {
            Storage::disk('public')->delete($oldVideoPosterFile);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Course level has been updated successfully.',
                'level_id' => $courseLevel->id,
                'redirect_node' => [
                    'level' => $courseLevel->id,
                    'module' => null,
                    'exam' => null,
                    'tab' => 'overview'
                ]
            ]);
        }

        return redirect()
            ->route('admin.course-management.programs.levels.index', $courseLevel->courseProgram)
            ->with('success', 'Course level has been updated successfully.');
    }

    public function destroy(Request $request, CourseLevel $courseLevel)
    {
        $courseProgram = $courseLevel->courseProgram;

        // Delete Guard: Prevent forced cascade delete if level contains modules or final exams
        if ($courseLevel->modules()->exists() || $courseLevel->finalExams()->exists()) {
            $msg = 'Cannot delete level "' . $courseLevel->name . '" because it contains modules or exam sections. Please remove or reassign them first.';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $msg
                ], 422);
            }
            return back()->withErrors(['delete' => $msg]);
        }

        if ($courseLevel->thumbnail_file && Storage::disk('public')->exists($courseLevel->thumbnail_file)) {
            Storage::disk('public')->delete($courseLevel->thumbnail_file);
        }

        if ($courseLevel->video_poster_file && Storage::disk('public')->exists($courseLevel->video_poster_file)) {
            Storage::disk('public')->delete($courseLevel->video_poster_file);
        }

        $courseLevel->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Course level has been deleted successfully.',
                'redirect_node' => [
                    'level' => null,
                    'module' => null,
                    'exam' => null,
                    'tab' => 'overview'
                ]
            ]);
        }

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

    public function builderReorder(Request $request, CourseProgram $courseProgram)
    {
        $validated = $request->validate([
            'ordered_ids' => ['required', 'array'],
            'ordered_ids.*' => ['required', 'integer'],
            'original_ordered_ids' => ['nullable', 'array'],
            'original_ordered_ids.*' => ['integer'],
        ]);

        $orderedIds = array_map('intval', $validated['ordered_ids']);
        $originalOrderedIds = isset($validated['original_ordered_ids']) ? array_map('intval', $validated['original_ordered_ids']) : null;

        try {
            return \Illuminate\Support\Facades\DB::transaction(function () use ($courseProgram, $orderedIds, $originalOrderedIds) {
                // 1. Explicitly execute parent SQL query lock
                $lockedProgram = CourseProgram::whereKey($courseProgram->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                // 2. Lock & read current server siblings
                $siblings = $lockedProgram->courseLevels()
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
                    CourseLevel::where('id', $id)->update(['sort_order' => $index + 1]);
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'Course levels order updated successfully.'
                ]);
            });
        } catch (\Illuminate\Database\QueryException $e) {
            if ($this->isConcurrencyException($e)) {
                return response()->json([
                    'status' => 'conflict',
                    'message' => 'A database concurrency conflict occurred. Reload the latest order and try again.'
                ], 409);
            }
            \Illuminate\Support\Facades\Log::error('CourseLevel reorder query error: ' . $e->getMessage(), ['exception' => $e]);
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
