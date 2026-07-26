<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\FreeTest;
use Illuminate\Http\Request;
use App\Models\FreeTestCategory;

class FreeTestController extends Controller
{
    public function index()
    {
        $freeTests = FreeTest::query()
            ->with('categoryRelation')
            ->withCount('questions')
            ->latest()
            ->get();

        $categories = FreeTestCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('pages.admin.cms.free-tests.index', compact(
            'freeTests',
            'categories'
        ));
    }

    public function __construct(
        protected \App\Services\AssessmentConfigService $configService
    ) {}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'free_test_category_id' => ['nullable', 'exists:free_test_categories,id'],
            'description' => ['nullable', 'string'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $config = $this->configService->validateAndNormalize($request, null, false);

        $data = array_merge($validated, $config);
        $data['total_questions'] = 0;
        $data['is_active'] = $request->boolean('is_active');
        $data['category'] = $this->resolveCategoryName($data['free_test_category_id'] ?? null);

        FreeTest::create($data);

        return redirect()
            ->route('admin.cms.free-tests.index')
            ->with('success', 'Free test has been created successfully.');
    }

    public function update(Request $request, FreeTest $freeTest)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'free_test_category_id' => ['nullable', 'exists:free_test_categories,id'],
            'description' => ['nullable', 'string'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $config = $this->configService->validateAndNormalize($request, $freeTest, false);

        $data = array_merge($validated, $config);
        $data['is_active'] = $request->boolean('is_active');
        $data['category'] = $this->resolveCategoryName($data['free_test_category_id'] ?? null);

        $freeTest->update($data);

        return redirect()
            ->route('admin.cms.free-tests.index')
            ->with('success', 'Free test has been updated successfully.');
    }

    public function destroy(FreeTest $freeTest)
    {
        $freeTest->delete();

        return redirect()
            ->route('admin.cms.free-tests.index')
            ->with('success', 'Free test has been deleted successfully.');
    }

    private function resolveCategoryName(?int $categoryId): ?string
    {
        if (! $categoryId) {
            return null;
        }

        return FreeTestCategory::query()
            ->whereKey($categoryId)
            ->value('name');
    }
}
