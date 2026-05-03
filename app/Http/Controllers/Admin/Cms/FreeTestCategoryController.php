<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\FreeTestCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FreeTestCategoryController extends Controller
{
    public function index()
    {
        $categories = FreeTestCategory::query()
            ->withCount('freeTests')
            ->orderBy('sort_order')
            ->latest()
            ->get();

        $nextSortOrder = (FreeTestCategory::max('sort_order') ?? 0) + 1;

        return view('pages.admin.cms.free-test-categories.index', compact(
            'categories',
            'nextSortOrder'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:free_test_categories,slug'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['slug'] = $this->generateUniqueSlug($validated['slug'] ?? $validated['name']);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active');

        FreeTestCategory::create($validated);

        return redirect()
            ->route('admin.cms.free-test-categories.index')
            ->with('success', 'Free test category has been created successfully.');
    }

    public function update(Request $request, FreeTestCategory $freeTestCategory)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:free_test_categories,slug,' . $freeTestCategory->id],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['slug'] = $this->generateUniqueSlug(
            $validated['slug'] ?? $validated['name'],
            $freeTestCategory->id
        );

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active');

        $freeTestCategory->update($validated);

        return redirect()
            ->route('admin.cms.free-test-categories.index')
            ->with('success', 'Free test category has been updated successfully.');
    }

    public function destroy(FreeTestCategory $freeTestCategory)
    {
        $freeTestCategory->delete();

        return redirect()
            ->route('admin.cms.free-test-categories.index')
            ->with('success', 'Free test category has been deleted successfully.');
    }

    private function generateUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($value);
        $slug = $baseSlug;
        $counter = 1;

        while (
            FreeTestCategory::query()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
