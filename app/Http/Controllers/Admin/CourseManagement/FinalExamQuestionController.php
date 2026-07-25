<?php

namespace App\Http\Controllers\Admin\CourseManagement;

use App\Http\Controllers\Controller;
use App\Models\FinalExam;
use App\Models\FinalExamQuestion;
use Illuminate\Http\Request;

class FinalExamQuestionController extends Controller
{
    public function index(FinalExam $finalExam)
    {
        $finalExam->load('courseLevel.courseProgram');

        $questions = $finalExam->questions()
            ->with('options')
            ->orderBy('sort_order')
            ->latest()
            ->get();

        return view('pages.admin.course-management.final-exam-questions.index', compact(
            'finalExam',
            'questions'
        ));
    }

    public function create(Request $request, FinalExam $finalExam)
    {
        $finalExam->load('courseLevel.courseProgram');

        $nextSortOrder = ((int) $finalExam->questions()->max('sort_order')) + 1;
        $activateWhenReady = $request->boolean('activate_when_ready');

        return view('pages.admin.course-management.final-exam-questions.create', compact(
            'finalExam',
            'nextSortOrder',
            'activateWhenReady'
        ));
    }

    public function store(Request $request, FinalExam $finalExam)
    {
        $validated = $this->validateQuestion($request);

        $questionIsActive = $request->boolean('is_active');

        $question = $finalExam->questions()->create([
            'question_type' => $validated['question_type'],
            'question' => $validated['question'],
            'explanation' => $validated['explanation'] ?? null,
            'score' => $validated['score'] ?? 0,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $questionIsActive,
        ]);

        if ($validated['question_type'] === 'multiple_choice') {
            $this->saveOptions($question, $validated['options'], $validated['correct_option']);
        }

        $activateWhenReady = $request->boolean('activate_when_ready');
        $sectionActivated = false;

        // Auto-activate Final Exam section only if ALL conditions are met:
        // 1. request brings activate_when_ready=1
        // 2. question being created has is_active = true
        // 3. Final Exam section is currently is_active = false
        // 4. this is the first active question for that section
        if ($activateWhenReady && $questionIsActive && ! $finalExam->is_active) {
            $activeQuestionsCount = $finalExam->questions()->where('is_active', true)->count();
            if ($activeQuestionsCount === 1) {
                $finalExam->update(['is_active' => true]);
                $sectionActivated = true;
            }
        }

        $message = $sectionActivated
            ? 'Question created and Final Exam section activated successfully.'
            : 'Final exam question has been created successfully.';

        return redirect()
            ->route('admin.course-management.final-exams.questions.index', $finalExam)
            ->with('success', $message);
    }

    public function edit(FinalExamQuestion $finalExamQuestion)
    {
        $finalExamQuestion->load('finalExam.courseLevel.courseProgram', 'options');

        return view('pages.admin.course-management.final-exam-questions.edit', compact('finalExamQuestion'));
    }

    public function update(Request $request, FinalExamQuestion $finalExamQuestion)
    {
        $validated = $this->validateQuestion($request);

        $finalExamQuestion->update([
            'question_type' => $validated['question_type'],
            'question' => $validated['question'],
            'explanation' => $validated['explanation'] ?? null,
            'score' => $validated['score'] ?? 0,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        if ($validated['question_type'] === 'multiple_choice') {
            $this->saveOptions($finalExamQuestion, $validated['options'], $validated['correct_option']);
        } else {
            $finalExamQuestion->options()->delete();
        }

        return redirect()
            ->route('admin.course-management.final-exams.questions.index', $finalExamQuestion->finalExam)
            ->with('success', 'Final exam question has been updated successfully.');
    }

    public function destroy(FinalExamQuestion $finalExamQuestion)
    {
        $finalExam = $finalExamQuestion->finalExam;

        $finalExamQuestion->delete();

        return redirect()
            ->route('admin.course-management.final-exams.questions.index', $finalExam)
            ->with('success', 'Final exam question has been deleted successfully.');
    }

    public function preview(FinalExam $finalExam)
    {
        $finalExam->load('courseLevel.courseProgram');

        $questions = $finalExam->questions()
            ->with('options')
            ->orderBy('sort_order')
            ->latest()
            ->get();

        return view('pages.admin.course-management.final-exam-questions.preview', compact(
            'finalExam',
            'questions'
        ));
    }

    private function validateQuestion(Request $request): array
    {
        return $request->validate([
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
    }

    private function saveOptions(FinalExamQuestion $question, array $options, string $correctOption): void
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
