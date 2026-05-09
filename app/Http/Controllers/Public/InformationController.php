<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\InformationPost;
use Illuminate\Http\Request;

class InformationController extends Controller
{
    public function index(Request $request)
    {
        $selectedType = $request->query('type');

        $types = InformationPost::query()
            ->where('is_published', true)
            ->whereNotNull('type')
            ->where(function ($query) {
                $query
                    ->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->select('type')
            ->distinct()
            ->orderBy('type')
            ->pluck('type')
            ->mapWithKeys(function ($type) {
                return [$type => $this->formatTypeLabel($type)];
            });

        $posts = InformationPost::query()
            ->where('is_published', true)
            ->where(function ($query) {
                $query
                    ->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->when($selectedType, function ($query) use ($selectedType) {
                $query->where('type', $selectedType);
            })
            ->orderByRaw('published_at IS NULL')
            ->orderByDesc('published_at')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('pages.public.news', compact(
            'posts',
            'types',
            'selectedType'
        ));
    }

    public function show(InformationPost $informationPost)
    {
        abort_unless($this->isPublicPost($informationPost), 404);

        if (filled($informationPost->external_url)) {
            return redirect()->away($informationPost->external_url);
        }

        $informationPost->load([
            'images' => function ($query) {
                $query
                    ->orderBy('sort_order')
                    ->orderBy('id');
            },
        ]);

        return view('pages.public.news-detail', [
            'post' => $informationPost,
            'typeLabel' => $this->formatTypeLabel($informationPost->type),
            'displayDate' => $this->formatDisplayDate($informationPost),
        ]);
    }

    private function isPublicPost(InformationPost $post): bool
    {
        if (! $post->is_published) {
            return false;
        }

        if ($post->published_at && $post->published_at->isFuture()) {
            return false;
        }

        return true;
    }

    private function formatTypeLabel(?string $type): string
    {
        if (! filled($type)) {
            return 'Information';
        }

        return str($type)
            ->replace(['-', '_'], ' ')
            ->title()
            ->toString();
    }

    private function formatDisplayDate(InformationPost $post): string
    {
        $date = $post->published_at
            ?? $post->event_date
            ?? $post->created_at;

        return $date
            ? $date->format('M d, Y')
            : 'Date not available';
    }
}
