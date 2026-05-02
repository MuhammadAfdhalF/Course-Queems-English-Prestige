<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\Mentor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MentorController extends Controller
{
    public function index()
    {
        $mentors = Mentor::query()
            ->orderBy('sort_order')
            ->latest()
            ->get();

        $nextSortOrder = (Mentor::max('sort_order') ?? 0) + 1;

        return view('pages.admin.cms.about.mentors.index', compact(
            'mentors',
            'nextSortOrder'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'position' => ['nullable', 'string', 'max:255'],
            'expertise' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('mentors', 'public');
        }

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active');

        Mentor::create($validated);

        return redirect()
            ->route('admin.cms.mentors.index')
            ->with('success', 'Mentor has been created successfully.');
    }

    public function update(Request $request, Mentor $mentor)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'position' => ['nullable', 'string', 'max:255'],
            'expertise' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('photo')) {
            if ($mentor->photo) {
                Storage::disk('public')->delete($mentor->photo);
            }

            $validated['photo'] = $request->file('photo')->store('mentors', 'public');
        }

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active');

        $mentor->update($validated);

        return redirect()
            ->route('admin.cms.mentors.index')
            ->with('success', 'Mentor has been updated successfully.');
    }

    public function destroy(Mentor $mentor)
    {
        if ($mentor->photo) {
            Storage::disk('public')->delete($mentor->photo);
        }

        $mentor->delete();

        return redirect()
            ->route('admin.cms.mentors.index')
            ->with('success', 'Mentor has been deleted successfully.');
    }
}
