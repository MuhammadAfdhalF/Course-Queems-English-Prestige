<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\FreeTest;
use App\Models\FreeTestQuestion;
use App\Services\AssessmentConfigService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FreeTestQuestionController extends Controller
{
    public function __construct(
        protected AssessmentConfigService $configService
    ) {}

    public function index(FreeTest $freeTest)
    {
        $questions = $freeTest->questions()
            ->orderBy('sort_order')
            ->latest()
            ->get();

        $nextSortOrder = ((int) $freeTest->questions()->max('sort_order')) + 1;
        $readiness = $this->configService->getReadinessStatus($freeTest);

        return view('pages.admin.cms.free-tests.questions.index', compact(
            'freeTest',
            'questions',
            'nextSortOrder',
            'readiness'
        ));
    }

    public function store(Request $request, FreeTest $freeTest)
    {
        $this->configService->ensureNotLocked($freeTest);

        $validated = $request->validate([
            'question' => ['required', 'string'],
            'option_a' => ['required', 'string', 'max:255'],
            'option_b' => ['required', 'string', 'max:255'],
            'option_c' => ['required', 'string', 'max:255'],
            'option_d' => ['required', 'string', 'max:255'],
            'correct_answer' => ['required', 'in:A,B,C,D'],
            'score' => ['nullable', 'numeric', 'min:0.01'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $questionScore = (float) ($validated['score'] ?? 1);
        $questionIsActive = $request->boolean('is_active');

        $this->configService->validateProspectiveScore($freeTest, $questionScore, $questionIsActive);

        DB::transaction(function () use ($freeTest, $validated, $questionScore, $questionIsActive) {
            $lockedFreeTest = FreeTest::whereKey($freeTest->id)->lockForUpdate()->firstOrFail();
            $this->configService->ensureNotLocked($lockedFreeTest);
            $this->configService->validateProspectiveScore($lockedFreeTest, $questionScore, $questionIsActive);

            $data = $validated;
            $data['free_test_id'] = $lockedFreeTest->id;
            $data['question_type'] = 'multiple_choice';
            $data['score'] = $questionScore;
            $data['sort_order'] = $data['sort_order'] ?? ((int) $lockedFreeTest->questions()->max('sort_order') + 1);
            $data['is_active'] = $questionIsActive;

            FreeTestQuestion::create($data);
            $this->syncTotalQuestions($lockedFreeTest);
        });

        $deactivated = $this->configService->handlePostMutationDeactivation($freeTest);
        $message = $deactivated
            ? 'Question created. Free Test was deactivated because active question scores no longer match total score.'
            : 'Question has been created successfully.';

        return redirect()
            ->route('admin.cms.free-tests.questions.index', $freeTest)
            ->with($deactivated ? 'warning' : 'success', $message);
    }

    public function update(Request $request, FreeTestQuestion $freeTestQuestion)
    {
        $freeTest = $freeTestQuestion->freeTest;
        $this->configService->ensureNotLocked($freeTest);

        $validated = $request->validate([
            'question' => ['required', 'string'],
            'option_a' => ['required', 'string', 'max:255'],
            'option_b' => ['required', 'string', 'max:255'],
            'option_c' => ['required', 'string', 'max:255'],
            'option_d' => ['required', 'string', 'max:255'],
            'correct_answer' => ['required', 'in:A,B,C,D'],
            'score' => ['nullable', 'numeric', 'min:0.01'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $questionScore = (float) ($validated['score'] ?? 1);
        $questionIsActive = $request->boolean('is_active');

        $this->configService->validateProspectiveScore($freeTest, $questionScore, $questionIsActive, $freeTestQuestion);

        DB::transaction(function () use ($freeTestQuestion, $freeTest, $validated, $questionScore, $questionIsActive) {
            $lockedFreeTest = FreeTest::whereKey($freeTest->id)->lockForUpdate()->firstOrFail();
            $this->configService->ensureNotLocked($lockedFreeTest);
            $this->configService->validateProspectiveScore($lockedFreeTest, $questionScore, $questionIsActive, $freeTestQuestion);

            $data = $validated;
            $data['question_type'] = 'multiple_choice';
            $data['score'] = $questionScore;
            $data['sort_order'] = $data['sort_order'] ?? 0;
            $data['is_active'] = $questionIsActive;

            $freeTestQuestion->update($data);
            $this->syncTotalQuestions($lockedFreeTest);
        });

        $deactivated = $this->configService->handlePostMutationDeactivation($freeTest);
        $message = $deactivated
            ? 'Question updated. Free Test was deactivated because active question scores no longer match total score.'
            : 'Question has been updated successfully.';

        return redirect()
            ->route('admin.cms.free-tests.questions.index', $freeTest)
            ->with($deactivated ? 'warning' : 'success', $message);
    }

    public function destroy(FreeTestQuestion $freeTestQuestion)
    {
        $freeTest = $freeTestQuestion->freeTest;
        $this->configService->ensureNotLocked($freeTest);

        DB::transaction(function () use ($freeTestQuestion, $freeTest) {
            $lockedFreeTest = FreeTest::whereKey($freeTest->id)->lockForUpdate()->firstOrFail();
            $this->configService->ensureNotLocked($lockedFreeTest);

            $freeTestQuestion->delete();
            $this->syncTotalQuestions($lockedFreeTest);
        });

        $deactivated = $this->configService->handlePostMutationDeactivation($freeTest);
        $message = $deactivated
            ? 'Question deleted. Free Test was deactivated because active question scores no longer match total score.'
            : 'Question has been deleted successfully.';

        return redirect()
            ->route('admin.cms.free-tests.questions.index', $freeTest)
            ->with($deactivated ? 'warning' : 'success', $message);
    }

    private function syncTotalQuestions(FreeTest $freeTest): void
    {
        $freeTest->update([
            'total_questions' => $freeTest->questions()->count(),
        ]);
    }
}
