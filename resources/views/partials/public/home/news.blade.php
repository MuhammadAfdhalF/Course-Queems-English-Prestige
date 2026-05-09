@php
$fallbackNews = collect([
    [
        'title' => 'Global Language Trends: The Shift in 2024',
        'category' => 'News',
        'date' => 'Oct 24, 2025',
        'excerpt' => 'Explore practical insights and recent updates from Queens English Prestige.',
        'image' => 'https://placehold.co/800x500/f4b36d/ffffff?text=News+1',
        'href' => route('news'),
        'target' => null,
        'rel' => null,
        'buttonText' => 'Read More',
    ],
    [
        'title' => 'The Impact of Effective English in Professional Communication',
        'category' => 'News',
        'date' => 'Oct 15, 2025',
        'excerpt' => 'Discover how English communication supports academic and professional growth.',
        'image' => 'https://placehold.co/800x500/6b9c8b/ffffff?text=News+2',
        'href' => route('news'),
        'target' => null,
        'rel' => null,
        'buttonText' => 'Read More',
    ],
    [
        'title' => 'IELTS Updates: What Changed and How to Prepare',
        'category' => 'News',
        'date' => 'Oct 05, 2025',
        'excerpt' => 'A quick overview of useful preparation strategies for English learners.',
        'image' => 'https://placehold.co/800x500/f1c2a7/ffffff?text=News+3',
        'href' => route('news'),
        'target' => null,
        'rel' => null,
        'buttonText' => 'Read More',
    ],
    [
        'title' => 'A Quick Review of Key Changes and Practical Preparation Tips',
        'category' => 'News',
        'date' => 'Oct 05, 2025',
        'excerpt' => 'Learn simple and practical tips to improve your confidence in English.',
        'image' => 'https://placehold.co/800x500/6f5845/ffffff?text=News+4',
        'href' => route('news'),
        'target' => null,
        'rel' => null,
        'buttonText' => 'Read More',
    ],
]);

$newsItems = ($latestPosts ?? collect())->map(function ($post) {
    $date = $post->published_at ?? $post->event_date ?? $post->created_at;

    $isExternal = filled($post->external_url);

    return [
        'title' => $post->title,
        'category' => str($post->type ?: 'news')->replace(['-', '_'], ' ')->title()->toString(),
        'date' => $date ? $date->format('M d, Y') : 'Date not available',
        'excerpt' => $post->excerpt ?: 'Read the latest news, updates, and gallery from Queens English Prestige.',
        'image' => $post->thumbnail
            ? asset('storage/' . $post->thumbnail)
            : 'https://placehold.co/800x500/e8eef8/1e293b?text=Queens+News',
        'href' => $isExternal ? $post->external_url : route('news.show', $post),
        'target' => $isExternal ? '_blank' : null,
        'rel' => $isExternal ? 'noopener noreferrer' : null,
        'buttonText' => $isExternal ? 'Visit Link' : 'Read More',
    ];
});

if ($newsItems->isEmpty()) {
    $newsItems = $fallbackNews;
}
@endphp

<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-16 lg:px-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="reveal text-2xl font-bold text-slate-900 md:text-3xl">
                    Latest <span class="text-[var(--color-brand-gold)]">News</span>
                </h2>
                <p class="reveal reveal-delay-1 mt-3 text-base text-slate-600">
                    Explore recent updates, events, and practical English learning insights.
                </p>
            </div>

            <a href="{{ route('news') }}" class="reveal reveal-delay-1 inline-flex items-center gap-2 text-sm font-semibold text-[var(--color-brand-blue)] hover:opacity-80">
                <span>View All News</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($newsItems as $index => $item)
                @php
                    $delayClass = match ($index % 4) {
                        1 => 'reveal-delay-1',
                        2 => 'reveal-delay-2',
                        3 => 'reveal-delay-3',
                        default => '',
                    };

                    $isExternal = filled($item['target']);
                @endphp

                <article class="reveal {{ $delayClass }} group motion-card overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-md">
                    <a
                        href="{{ $item['href'] }}"
                        @if ($item['target']) target="{{ $item['target'] }}" @endif
                        @if ($item['rel']) rel="{{ $item['rel'] }}" @endif
                        class="block">
                        <div class="aspect-[4/3] overflow-hidden rounded-t-[28px] bg-slate-100">
                            <img
                                src="{{ $item['image'] }}"
                                alt="{{ $item['title'] }}"
                                class="motion-image h-full w-full object-cover">
                        </div>
                    </a>

                    <div class="p-6">
                        <div class="flex items-center justify-between gap-4">
                            <span class="inline-flex items-center rounded-lg bg-[#f8efcf] px-3 py-1.5 text-[10px] font-bold uppercase tracking-[0.14em] text-[var(--color-brand-gold)]">
                                {{ $item['category'] }}
                            </span>

                            <span class="shrink-0 text-sm font-medium text-slate-400">
                                {{ $item['date'] }}
                            </span>
                        </div>

                        <a
                            href="{{ $item['href'] }}"
                            @if ($item['target']) target="{{ $item['target'] }}" @endif
                            @if ($item['rel']) rel="{{ $item['rel'] }}" @endif>
                            <h3 class="mt-5 line-clamp-2 text-[26px] font-bold leading-tight text-[var(--color-brand-blue)] transition hover:text-[var(--color-brand-gold)]">
                                {{ $item['title'] }}
                            </h3>
                        </a>

                        <p class="mt-4 line-clamp-3 text-base leading-7 text-slate-500">
                            {{ $item['excerpt'] }}
                        </p>

                        <div class="mt-6">
                            <a
                                href="{{ $item['href'] }}"
                                @if ($item['target']) target="{{ $item['target'] }}" @endif
                                @if ($item['rel']) rel="{{ $item['rel'] }}" @endif
                                class="inline-flex items-center gap-3 text-base font-bold text-[#2457E6] transition hover:gap-4">
                                <span>{{ $item['buttonText'] }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    @if ($isExternal)
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17L17 7M17 7H9M17 7v8" />
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M13 5l7 7-7 7" />
                                    @endif
                                </svg>
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>