<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\FreeTest;
use App\Models\FreeTestCategory;
use App\Models\FreeTestResult;
use Illuminate\Http\Request;

class FreeTestController extends Controller
{
    public function index(Request $request)
    {
        $selectedCategory = $request->query('category');

        $freeTestCategories = FreeTestCategory::query()
            ->where('is_active', true)
            ->withCount([
                'freeTests' => function ($query) {
                    $query->where('is_active', true);
                },
            ])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        if (
            filled($selectedCategory)
            && ! $freeTestCategories->contains('slug', $selectedCategory)
        ) {
            $selectedCategory = null;
        }

        $freeTests = FreeTest::query()
            ->where('is_active', true)
            ->with('categoryRelation')
            ->withCount([
                'questions' => function ($query) {
                    $query->where('is_active', true);
                },
            ])
            ->when(filled($selectedCategory), function ($query) use ($selectedCategory) {
                $query->whereHas('categoryRelation', function ($categoryQuery) use ($selectedCategory) {
                    $categoryQuery->where('slug', $selectedCategory);
                });
            })
            ->orderBy('id')
            ->get();

        $firstFreeTest = $freeTests->first();

        return view('pages.public.free-test', compact(
            'freeTests',
            'firstFreeTest',
            'freeTestCategories',
            'selectedCategory'
        ));
    }

    public function show(FreeTest $freeTest)
    {
        abort_unless($freeTest->is_active, 404);

        $questions = $freeTest->questions()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        abort_if($questions->isEmpty(), 404);

        $freeTest->load('categoryRelation');

        return view('pages.public.free-test-runner', compact(
            'freeTest',
            'questions'
        ));
    }

    public function submit(Request $request, FreeTest $freeTest)
    {
        abort_unless($freeTest->is_active, 404);

        $questions = $freeTest->questions()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        abort_if($questions->isEmpty(), 404);

        $validated = $request->validate([
            'participant_name' => ['required', 'string', 'max:255'],
            'participant_email' => ['required', 'email', 'max:255'],
            'participant_whatsapp' => ['required', 'string', 'max:30'],
            'answers' => ['required', 'array'],
        ]);

        foreach ($questions as $question) {
            $request->validate([
                'answers.' . $question->id => ['required', 'in:A,B,C,D'],
            ], [
                'answers.' . $question->id . '.required' => 'Please answer all questions before submitting.',
            ]);
        }

        $totalScore = 0;

        foreach ($questions as $question) {
            $selectedAnswer = $validated['answers'][$question->id] ?? null;

            if ($selectedAnswer === $question->correct_answer) {
                $totalScore += (int) $question->score;
            }
        }

        $recommendation = $this->buildRecommendation($freeTest, $totalScore);

        $freeTestResult = FreeTestResult::create([
            'free_test_id' => $freeTest->id,
            'participant_name' => $validated['participant_name'],
            'participant_email' => $validated['participant_email'],
            'participant_whatsapp' => $validated['participant_whatsapp'],
            'total_score' => $totalScore,
            'recommendation' => $recommendation,
            'submitted_at' => now(),
        ]);

        return redirect()->route('free-test.result', $freeTestResult);
    }

    public function result(FreeTestResult $freeTestResult)
    {
        $freeTestResult->load('freeTest.categoryRelation');

        return view('pages.public.free-test-result', compact('freeTestResult'));
    }

    private function buildRecommendation(FreeTest $freeTest, int $totalScore): string
    {
        $passingGrade = (int) $freeTest->passing_grade;

        if ($passingGrade > 0 && $totalScore >= $passingGrade) {
            return 'Great job! You passed this free test. You already have a strong foundation, and we recommend continuing with a structured program to sharpen your fluency, accuracy, and confidence.';
        }

        return 'We recommend starting from a foundational program to strengthen your English basics before moving to more advanced materials. Our team can help you choose the most suitable course based on your result.';
    }
}
