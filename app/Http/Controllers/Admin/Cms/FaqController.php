<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::query()
            ->orderBy('sort_order')
            ->latest()
            ->get();

        $nextSortOrder = (Faq::max('sort_order') ?? 0) + 1;

        return view('pages.admin.cms.home.faqs.index', compact(
            'faqs',
            'nextSortOrder'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active');

        Faq::create($validated);

        return redirect()
            ->route('admin.cms.faqs.index')
            ->with('success', 'FAQ has been created successfully.');
    }

    public function update(Request $request, Faq $faq)
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active');

        $faq->update($validated);

        return redirect()
            ->route('admin.cms.faqs.index')
            ->with('success', 'FAQ has been updated successfully.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return redirect()
            ->route('admin.cms.faqs.index')
            ->with('success', 'FAQ has been deleted successfully.');
    }
}
