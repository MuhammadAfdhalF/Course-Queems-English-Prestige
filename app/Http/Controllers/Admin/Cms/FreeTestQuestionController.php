<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\FreeTest;
use App\Models\FreeTestQuestion;
use Illuminate\Http\Request;

class FreeTestQuestionController extends Controller
{
    public function index(FreeTest $freeTest)
    {
        $questions = $freeTest->questions()
            ->orderBy('sort_order')
            ->latest()
            ->get();

        $nextSortOrder = ((int) $freeTest->questions()->max('sort_order')) + 1;

        return view('pages.admin.cms.free-tests.questions.index', compact(
            'freeTest',
            'questions',
            'nextSortOrder'
        ));
    }

    public function store(Request $request, FreeTest $freeTest)
    {
        $validated = $request->validate([
            'question' => ['required', 'string'],
            'option_a' => ['required', 'string', 'max:255'],
            'option_b' => ['required', 'string', 'max:255'],
            'option_c' => ['required', 'string', 'max:255'],
            'option_d' => ['required', 'string', 'max:255'],
            'correct_answer' => ['required', 'in:A,B,C,D'],
            'score' => ['nullable', 'integer', 'min:1'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['free_test_id'] = $freeTest->id;
        $validated['question_type'] = 'multiple_choice';
        $validated['score'] = $validated['score'] ?? 1;
        $validated['sort_order'] = $validated['sort_order']
            ?? ((int) $freeTest->questions()->max('sort_order') + 1);
        $validated['is_active'] = $request->boolean('is_active');

        FreeTestQuestion::create($validated);

        $this->syncTotalQuestions($freeTest);

        return redirect()
            ->route('admin.cms.free-tests.questions.index', $freeTest)
            ->with('success', 'Question has been created successfully.');
    }

    public function update(Request $request, FreeTestQuestion $freeTestQuestion)
    {
        $validated = $request->validate([
            'question' => ['required', 'string'],
            'option_a' => ['required', 'string', 'max:255'],
            'option_b' => ['required', 'string', 'max:255'],
            'option_c' => ['required', 'string', 'max:255'],
            'option_d' => ['required', 'string', 'max:255'],
            'correct_answer' => ['required', 'in:A,B,C,D'],
            'score' => ['nullable', 'integer', 'min:1'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['question_type'] = 'multiple_choice';
        $validated['score'] = $validated['score'] ?? 1;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active');

        $freeTestQuestion->update($validated);

        $this->syncTotalQuestions($freeTestQuestion->freeTest);

        return redirect()
            ->route('admin.cms.free-tests.questions.index', $freeTestQuestion->freeTest)
            ->with('success', 'Question has been updated successfully.');
    }

    public function destroy(FreeTestQuestion $freeTestQuestion)
    {
        $freeTest = $freeTestQuestion->freeTest;

        $freeTestQuestion->delete();

        $this->syncTotalQuestions($freeTest);

        return redirect()
            ->route('admin.cms.free-tests.questions.index', $freeTest)
            ->with('success', 'Question has been deleted successfully.');
    }

    private function syncTotalQuestions(FreeTest $freeTest): void
    {
        $freeTest->update([
            'total_questions' => $freeTest->questions()->count(),
        ]);
    }
}
