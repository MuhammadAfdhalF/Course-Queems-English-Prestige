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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'free_test_category_id' => ['nullable', 'exists:free_test_categories,id'],
            'description' => ['nullable', 'string'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'passing_grade' => ['nullable', 'integer', 'min:0', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['total_questions'] = 0;
        $validated['is_active'] = $request->boolean('is_active');
        $validated['category'] = $this->resolveCategoryName($validated['free_test_category_id'] ?? null);

        FreeTest::create($validated);

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
            'passing_grade' => ['nullable', 'integer', 'min:0', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ]);


        $validated['is_active'] = $request->boolean('is_active');
        $validated['category'] = $this->resolveCategoryName($validated['free_test_category_id'] ?? null);
        $freeTest->update($validated);

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
