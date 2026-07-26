<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\CourseLevel;
use App\Models\CourseProgram;
use App\Models\Order;
use App\Models\StudentCourseEnrollment;
use Illuminate\Http\Request;

use App\Models\Module;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $selectedMode = $request->query('mode');
        $selectedProgram = $request->query('program');

        $programs = CourseProgram::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $courseLevels = CourseLevel::query()
            ->with('courseProgram')
            ->where('is_active', true)
            ->whereHas('courseProgram', function ($query) {
                $query->where('is_active', true);
            })
            ->when(
                in_array($selectedMode, ['online', 'offline', 'hybrid'], true),
                function ($query) use ($selectedMode) {
                    $query->where('learning_mode', $selectedMode);
                }
            )
            ->when($selectedProgram, function ($query) use ($selectedProgram) {
                $query->whereHas('courseProgram', function ($programQuery) use ($selectedProgram) {
                    $programQuery->where('slug', $selectedProgram);
                });
            })
            ->orderBy('sort_order')
            ->latest()
            ->get();

        $courseItems = $courseLevels->map(function (CourseLevel $courseLevel) {
            $thumbnail = ($courseLevel->thumbnail_type === 'image' && $courseLevel->thumbnail_file)
                ? asset('storage/' . $courseLevel->thumbnail_file)
                : 'https://placehold.co/800x500/EEF3FF/2457E6?text=' . urlencode($courseLevel->name);

            $poster = $courseLevel->video_poster_file
                ? asset('storage/' . $courseLevel->video_poster_file)
                : null;

            return [
                'title' => $courseLevel->name,
                'level' => $courseLevel->courseProgram?->name ?? 'Course Program',
                'mode' => $this->formatLearningMode($courseLevel->learning_mode),
                'price' => 'Rp ' . number_format((float) $courseLevel->price, 0, ',', '.'),
                'description' => $courseLevel->short_description ?: 'Course description will be available soon.',
                'image' => $thumbnail,
                'poster' => $poster,
                'thumbnail_type' => $courseLevel->thumbnail_type ?? 'image',
                'buttonText' => 'View Detail',
                'href' => route('courses.show', $courseLevel),
            ];
        })->all();

        return view('pages.public.courses', compact(
            'programs',
            'courseItems',
            'selectedMode',
            'selectedProgram'
        ));
    }

    public function show(CourseLevel $courseLevel)
    {
        abort_unless($courseLevel->is_active, 404);

        $courseLevel->load([
            'courseProgram',
            'modules' => function ($query) {
                $query
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('title');
            },
        ]);

        abort_if(
            ! $courseLevel->courseProgram || ! $courseLevel->courseProgram->is_active,
            404
        );

        $hasPendingOrder = false;
        $hasActiveEnrollment = false;
        $activeEnrollment = null;

        if (auth()->check() && auth()->user()->isStudent()) {
            $studentId = auth()->id();

            $hasPendingOrder = Order::query()
                ->where('student_id', $studentId)
                ->where('course_level_id', $courseLevel->id)
                ->where('status', 'pending')
                ->exists();

            $activeEnrollment = StudentCourseEnrollment::query()
                ->where('student_id', $studentId)
                ->where('course_level_id', $courseLevel->id)
                ->where('status', 'active')
                ->first();

            $hasActiveEnrollment = $activeEnrollment !== null;
        }

        return view('pages.public.course-detail', compact(
            'courseLevel',
            'hasPendingOrder',
            'hasActiveEnrollment',
            'activeEnrollment'
        ));
    }

    public function previewModule(CourseLevel $courseLevel, Module $module)
    {
        abort_unless($courseLevel->is_active, 404);

        abort_if(
            ! $courseLevel->courseProgram || ! $courseLevel->courseProgram->is_active,
            404
        );

        abort_unless($module->course_level_id === $courseLevel->id, 404);
        abort_unless($module->is_active, 404);
        abort_unless($module->is_preview, 404);

        $module->load([
            'materials' => function ($query) {
                $query
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('title');
            },
        ]);

        $readableMaterials = $module->materials->filter(function ($material) {
            $type = strtolower($material->material_type ?? 'text');
            return in_array($type, ['text', 'rich_text', 'content', 'image', 'thumbnail', 'photo', 'picture'], true);
        });

        $hasExcludedMedia = $module->materials->contains(function ($material) {
            $type = strtolower($material->material_type ?? 'text');
            return in_array($type, ['video', 'audio', 'pdf', 'file', 'document', 'sound'], true);
        });

        $hasActiveEnrollment = false;
        $activeEnrollment = null;

        if (auth()->check() && auth()->user()->isStudent()) {
            $studentId = auth()->id();

            $activeEnrollment = StudentCourseEnrollment::query()
                ->where('student_id', $studentId)
                ->where('course_level_id', $courseLevel->id)
                ->where('status', 'active')
                ->first();

            $hasActiveEnrollment = $activeEnrollment !== null;
        }

        return view('pages.public.course-module-preview', compact(
            'courseLevel',
            'module',
            'readableMaterials',
            'hasExcludedMedia',
            'hasActiveEnrollment',
            'activeEnrollment'
        ));
    }

    private function formatLearningMode(?string $learningMode): string
    {
        return match ($learningMode) {
            'offline' => 'Offline',
            'hybrid' => 'Hybrid',
            default => 'Online',
        };
    }
}
