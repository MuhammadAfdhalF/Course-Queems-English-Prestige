<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\HeroSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroSectionController extends Controller
{
    public function index()
    {
        $heroSections = HeroSection::query()
            ->orderBy('sort_order')
            ->latest()
            ->get();

        $nextSortOrder = (HeroSection::max('sort_order') ?? 0) + 1;

        return view('pages.admin.cms.home.hero-sections.index', compact(
            'heroSections',
            'nextSortOrder'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:2048'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('hero-sections', 'public');
        }

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active');

        HeroSection::create($validated);

        return redirect()
            ->route('admin.cms.hero-sections.index')
            ->with('success', 'Hero section berhasil ditambahkan.');
    }

    public function update(Request $request, HeroSection $heroSection)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:2048'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            if ($heroSection->image) {
                Storage::disk('public')->delete($heroSection->image);
            }

            $validated['image'] = $request->file('image')->store('hero-sections', 'public');
        }

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active');

        $heroSection->update($validated);

        return redirect()
            ->route('admin.cms.hero-sections.index')
            ->with('success', 'Hero section berhasil diperbarui.');
    }

    public function destroy(HeroSection $heroSection)
    {
        if ($heroSection->image) {
            Storage::disk('public')->delete($heroSection->image);
        }

        $heroSection->delete();

        return redirect()
            ->route('admin.cms.hero-sections.index')
            ->with('success', 'Hero section berhasil dihapus.');
    }
}
