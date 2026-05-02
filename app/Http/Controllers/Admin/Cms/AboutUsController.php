<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutUsController extends Controller
{
    public function index()
    {
        $aboutUs = AboutUs::query()->first();

        return view('pages.admin.cms.about.about-us.index', compact('aboutUs'));
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $aboutUs = AboutUs::query()->first();

        if ($request->hasFile('image')) {
            if ($aboutUs?->image) {
                Storage::disk('public')->delete($aboutUs->image);
            }

            $validated['image'] = $request->file('image')->store('about-us', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');

        if ($aboutUs) {
            $aboutUs->update($validated);
        } else {
            AboutUs::create($validated);
        }

        return redirect()
            ->route('admin.cms.about-us.index')
            ->with('success', 'About Us has been saved successfully.');
    }
}
