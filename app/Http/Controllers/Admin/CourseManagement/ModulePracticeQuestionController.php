<?php

namespace App\Http\Controllers\Admin\CourseManagement;

use App\Http\Controllers\Controller;
use App\Models\ModulePractice;
use App\Models\ModulePracticeQuestion;
use Illuminate\Http\Request;

class ModulePracticeQuestionController extends Controller
{
    public function index(ModulePractice $modulePractice)
    {
        $modulePractice->load('module.courseLevel.courseProgram');

        $questions = $modulePractice->questions()
            ->with('options')
            ->orderBy('sort_order')
            ->latest()
            ->get();

        return view('pages.admin.course-management.practice-questions.index', compact(
            'modulePractice',
            'questions'
        ));
    }

    public function create(ModulePractice $modulePractice)
    {
        $modulePractice->load('module.courseLevel.courseProgram');

        $nextSortOrder = ((int) $modulePractice->questions()->max('sort_order')) + 1;

        return view('pages.admin.course-management.practice-questions.create', compact(
            'modulePractice',
            'nextSortOrder'
        ));
    }

    public function store(Request $request, ModulePractice $modulePractice)
    {
        $validated = $this->validateQuestion($request);

        $question = $modulePractice->questions()->create([
            'question_type' => $validated['question_type'],
            'question' => $validated['question'],
            'explanation' => $validated['explanation'] ?? null,
            'score' => $validated['score'] ?? 0,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        if ($validated['question_type'] === 'multiple_choice') {
            $this->saveOptions($question, $validated['options'], $validated['correct_option']);
        }

        return redirect()
            ->route('admin.course-management.practices.questions.index', $modulePractice)
            ->with('success', 'Practice question has been created successfully.');
    }

    public function edit(ModulePracticeQuestion $modulePracticeQuestion)
    {
        $modulePracticeQuestion->load('practice.module.courseLevel.courseProgram', 'options');

        return view('pages.admin.course-management.practice-questions.edit', compact('modulePracticeQuestion'));
    }

    public function update(Request $request, ModulePracticeQuestion $modulePracticeQuestion)
    {
        $validated = $this->validateQuestion($request);

        $modulePracticeQuestion->update([
            'question_type' => $validated['question_type'],
            'question' => $validated['question'],
            'explanation' => $validated['explanation'] ?? null,
            'score' => $validated['score'] ?? 0,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        if ($validated['question_type'] === 'multiple_choice') {
            $this->saveOptions($modulePracticeQuestion, $validated['options'], $validated['correct_option']);
        } else {
            $modulePracticeQuestion->options()->delete();
        }

        return redirect()
            ->route('admin.course-management.practices.questions.index', $modulePracticeQuestion->practice)
            ->with('success', 'Practice question has been updated successfully.');
    }

    public function destroy(ModulePracticeQuestion $modulePracticeQuestion)
    {
        $practice = $modulePracticeQuestion->practice;

        $modulePracticeQuestion->delete();

        return redirect()
            ->route('admin.course-management.practices.questions.index', $practice)
            ->with('success', 'Practice question has been deleted successfully.');
    }

    private function validateQuestion(Request $request): array
    {
        $validated = $request->validate([
            'question_type' => ['required', 'in:multiple_choice,short_answer,essay,upload'],
            'question' => ['required', 'string'],
            'explanation' => ['nullable', 'string'],
            'score' => ['required', 'numeric', 'min:0'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],

            'options' => ['nullable', 'array'],
            'options.A' => ['required_if:question_type,multiple_choice', 'nullable', 'string'],
            'options.B' => ['required_if:question_type,multiple_choice', 'nullable', 'string'],
            'options.C' => ['required_if:question_type,multiple_choice', 'nullable', 'string'],
            'options.D' => ['required_if:question_type,multiple_choice', 'nullable', 'string'],
            'correct_option' => ['required_if:question_type,multiple_choice', 'nullable', 'in:A,B,C,D'],
        ]);

        return $validated;
    }

    private function saveOptions(ModulePracticeQuestion $question, array $options, string $correctOption): void
    {
        foreach (['A', 'B', 'C', 'D'] as $index => $label) {
            $question->options()->updateOrCreate(
                [
                    'option_label' => $label,
                ],
                [
                    'option_text' => $options[$label] ?? '',
                    'is_correct' => $correctOption === $label,
                    'sort_order' => $index + 1,
                ]
            );
        }
    }
}
