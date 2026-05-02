<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\InformationPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\InformationPostImage;

class InformationPostController extends Controller
{
    public function index()
    {
        $posts = InformationPost::query()
            ->with(['images' => function ($query) {
                $query->orderBy('sort_order')->latest();
            }])
            ->latest('published_at')
            ->latest()
            ->get();

        return view('pages.admin.cms.news-gallery.index', compact('posts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:information_posts,slug'],
            'type' => ['required', 'in:news,gallery,event,announcement'],
            'link_type' => ['required', 'in:internal,external'],
            'external_url' => ['nullable', 'url', 'max:255'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'event_date' => ['nullable', 'date'],
            'published_at' => ['nullable', 'date'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        if ($validated['link_type'] === 'external' && empty($validated['external_url'])) {
            return back()
                ->withErrors(['external_url' => 'External URL is required when link type is external.'])
                ->withInput();
        }

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('information-posts/thumbnails', 'public');
        }

        $validated['slug'] = $this->generateUniqueSlug($validated['slug'] ?? $validated['title']);

        if ($validated['link_type'] === 'internal') {
            $validated['external_url'] = null;
        }

        unset($validated['link_type']);

        $validated['is_published'] = $request->boolean('is_published');

        InformationPost::create($validated);

        return redirect()
            ->route('admin.cms.news-gallery.index')
            ->with('success', 'Post has been created successfully.');
    }

    public function update(Request $request, InformationPost $informationPost)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:information_posts,slug,' . $informationPost->id],
            'type' => ['required', 'in:news,gallery,event,announcement'],
            'link_type' => ['required', 'in:internal,external'],
            'external_url' => ['nullable', 'url', 'max:255'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'event_date' => ['nullable', 'date'],
            'published_at' => ['nullable', 'date'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        if ($validated['link_type'] === 'external' && empty($validated['external_url'])) {
            return back()
                ->withErrors(['external_url' => 'External URL is required when link type is external.'])
                ->withInput();
        }

        if ($request->hasFile('thumbnail')) {
            if ($informationPost->thumbnail) {
                Storage::disk('public')->delete($informationPost->thumbnail);
            }

            $validated['thumbnail'] = $request->file('thumbnail')->store('information-posts/thumbnails', 'public');
        }

        $validated['slug'] = $this->generateUniqueSlug(
            $validated['slug'] ?? $validated['title'],
            $informationPost->id
        );

        if ($validated['link_type'] === 'internal') {
            $validated['external_url'] = null;
        }

        unset($validated['link_type']);

        $validated['is_published'] = $request->boolean('is_published');

        $informationPost->update($validated);

        return redirect()
            ->route('admin.cms.news-gallery.index')
            ->with('success', 'Post has been updated successfully.');
    }

    public function destroy(InformationPost $informationPost)
    {
        if ($informationPost->thumbnail) {
            Storage::disk('public')->delete($informationPost->thumbnail);
        }

        foreach ($informationPost->images as $image) {
            Storage::disk('public')->delete($image->image);
        }

        $informationPost->delete();

        return redirect()
            ->route('admin.cms.news-gallery.index')
            ->with('success', 'Post has been deleted successfully.');
    }

    private function generateUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($value);
        $slug = $baseSlug;
        $counter = 1;

        while (
            InformationPost::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn($query) => $query->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function storeImage(Request $request, InformationPost $informationPost)
    {
        $validated = $request->validate([
            'image' => ['required', 'image', 'max:2048'],
            'caption' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['image'] = $request->file('image')->store('information-posts/images', 'public');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $informationPost->images()->create($validated);

        return redirect()
            ->route('admin.cms.news-gallery.index')
            ->with('success', 'Post image has been uploaded successfully.');
    }

    public function destroyImage(InformationPostImage $informationPostImage)
    {
        if ($informationPostImage->image) {
            Storage::disk('public')->delete($informationPostImage->image);
        }

        $informationPostImage->delete();

        return redirect()
            ->route('admin.cms.news-gallery.index')
            ->with('success', 'Post image has been deleted successfully.');
    }
}
