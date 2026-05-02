<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\ProfileVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileVideoController extends Controller
{
    public function index()
    {
        $profileVideos = ProfileVideo::query()
            ->orderBy('sort_order')
            ->latest()
            ->get();

        $nextSortOrder = (ProfileVideo::max('sort_order') ?? 0) + 1;

        return view('pages.admin.cms.about.profile-videos.index', compact(
            'profileVideos',
            'nextSortOrder'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'video_file' => ['nullable', 'file', 'mimes:mp4,mov,avi,webm', 'max:51200'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('video_file')) {
            $validated['video_file'] = $request->file('video_file')->store('profile-videos/videos', 'public');
        }

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('profile-videos/thumbnails', 'public');
        }

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active');

        ProfileVideo::create($validated);

        return redirect()
            ->route('admin.cms.profile-videos.index')
            ->with('success', 'Profile video has been created successfully.');
    }

    public function update(Request $request, ProfileVideo $profileVideo)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'video_file' => ['nullable', 'file', 'mimes:mp4,mov,avi,webm', 'max:51200'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('video_file')) {
            if ($profileVideo->video_file) {
                Storage::disk('public')->delete($profileVideo->video_file);
            }

            $validated['video_file'] = $request->file('video_file')->store('profile-videos/videos', 'public');
        }

        if ($request->hasFile('thumbnail')) {
            if ($profileVideo->thumbnail) {
                Storage::disk('public')->delete($profileVideo->thumbnail);
            }

            $validated['thumbnail'] = $request->file('thumbnail')->store('profile-videos/thumbnails', 'public');
        }

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active');

        $profileVideo->update($validated);

        return redirect()
            ->route('admin.cms.profile-videos.index')
            ->with('success', 'Profile video has been updated successfully.');
    }

    public function destroy(ProfileVideo $profileVideo)
    {
        if ($profileVideo->video_file) {
            Storage::disk('public')->delete($profileVideo->video_file);
        }

        if ($profileVideo->thumbnail) {
            Storage::disk('public')->delete($profileVideo->thumbnail);
        }

        $profileVideo->delete();

        return redirect()
            ->route('admin.cms.profile-videos.index')
            ->with('success', 'Profile video has been deleted successfully.');
    }
}
