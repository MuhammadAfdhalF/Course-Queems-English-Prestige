<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\WhyChooseUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WhyChooseUsController extends Controller
{
    public function index()
    {
        $whyChooseUsItems = WhyChooseUs::query()
            ->orderBy('sort_order')
            ->latest()
            ->get();

        $nextSortOrder = (WhyChooseUs::max('sort_order') ?? 0) + 1;

        return view('pages.admin.cms.about.why-choose-us.index', compact(
            'whyChooseUsItems',
            'nextSortOrder'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'image', 'max:2048'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('icon')) {
            $validated['icon'] = $request->file('icon')->store('why-choose-us', 'public');
        }

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active');

        WhyChooseUs::create($validated);

        return redirect()
            ->route('admin.cms.why-choose-us.index')
            ->with('success', 'Why Choose Us item has been created successfully.');
    }

    public function update(Request $request, WhyChooseUs $whyChooseUs)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'image', 'max:2048'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('icon')) {
            if ($whyChooseUs->icon) {
                Storage::disk('public')->delete($whyChooseUs->icon);
            }

            $validated['icon'] = $request->file('icon')->store('why-choose-us', 'public');
        }

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active');

        $whyChooseUs->update($validated);

        return redirect()
            ->route('admin.cms.why-choose-us.index')
            ->with('success', 'Why Choose Us item has been updated successfully.');
    }

    public function destroy(WhyChooseUs $whyChooseUs)
    {
        if ($whyChooseUs->icon) {
            Storage::disk('public')->delete($whyChooseUs->icon);
        }

        $whyChooseUs->delete();

        return redirect()
            ->route('admin.cms.why-choose-us.index')
            ->with('success', 'Why Choose Us item has been deleted successfully.');
    }
}
