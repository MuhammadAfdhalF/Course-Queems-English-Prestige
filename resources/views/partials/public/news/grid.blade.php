<section class="bg-[#f8f8f6]">
    <div class="mx-auto max-w-7xl px-4 py-10 lg:px-8 lg:py-12">
        @if (($posts ?? collect())->isNotEmpty())
        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($posts as $index => $post)
            @php
            $delayClass = match ($index % 4) {
            1 => 'reveal-delay-1',
            2 => 'reveal-delay-2',
            3 => 'reveal-delay-3',
            default => '',
            };

            $categoryLabel = str($post->type ?: 'information')
            ->replace(['-', '_'], ' ')
            ->title()
            ->toString();

            $displayDate = ($post->published_at ?? $post->event_date ?? $post->created_at)
            ? ($post->published_at ?? $post->event_date ?? $post->created_at)->format('M d, Y')
            : 'Date not available';

            $image = $post->thumbnail
            ? asset('storage/' . $post->thumbnail)
            : 'https://placehold.co/800x500/e8eef8/1e293b?text=Queens+News';

            $isExternal = filled($post->external_url);

            $href = $isExternal
            ? $post->external_url
            : route('news.show', $post);

            $target = $isExternal ? '_blank' : null;
            $rel = $isExternal ? 'noopener noreferrer' : null;
            $buttonText = $isExternal ? 'Visit Link' : 'Read More';
            @endphp

            <div class="reveal {{ $delayClass }}">
                <x-public.news-card
                    :title="$post->title"
                    :category="$categoryLabel"
                    :date="$displayDate"
                    :excerpt="$post->excerpt ?: 'Read the latest information from Queens English Prestige.'"
                    :image="$image"
                    :href="$href"
                    :target="$target"
                    :rel="$rel"
                    :button-text="$buttonText"
                    :is-external="$isExternal" />
            </div>
            @endforeach
        </div>
        @else
        <div class="reveal rounded-[28px] border border-slate-200 bg-white px-6 py-16 text-center shadow-sm">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-[#EEF5FF] text-[var(--color-brand-blue)]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7h8M8 12h5m-5 5h8" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 3h12a2 2 0 012 2v14l-4-2-4 2-4-2-4 2V5a2 2 0 012-2z" />
                </svg>
            </div>

            <h2 class="mt-5 text-2xl font-bold text-slate-900">
                No news found.
            </h2>

            <p class="mx-auto mt-3 max-w-xl text-sm leading-7 text-slate-500">
                Please check back later for the latest news and information from Queens English Prestige.
            </p>

            @if (filled($selectedType ?? null))
            <div class="mt-7">
                <a href="{{ route('news') }}">
                    <x-ui.button class="px-6 py-3">
                        View All News
                    </x-ui.button>
                </a>
            </div>
            @endif
        </div>
        @endif
    </div>
</section>