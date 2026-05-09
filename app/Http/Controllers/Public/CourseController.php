<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\CourseLevel;
use App\Models\CourseProgram;
use Illuminate\Http\Request;

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
            return [
                'title' => $courseLevel->name,
                'level' => $courseLevel->courseProgram?->name ?? 'Course Program',
                'mode' => $this->formatLearningMode($courseLevel->learning_mode),
                'price' => 'Rp ' . number_format((float) $courseLevel->price, 0, ',', '.'),
                'description' => $courseLevel->short_description ?: 'Course description will be available soon.',
                'image' => $courseLevel->thumbnail_file
                    ? asset('storage/' . $courseLevel->thumbnail_file)
                    : 'https://placehold.co/800x500/EEF3FF/2457E6?text=Queens+English',
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

        return view('pages.public.course-detail', compact('courseLevel'));
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
