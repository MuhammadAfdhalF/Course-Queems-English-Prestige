@php
$fallbackTests = collect([
[
'title' => 'Listening Test',
'description' => '15 minutes assessment of auditory comprehension and focus.',
],
[
'title' => 'Reading Test',
'description' => '20 minutes deep-dive into complex text analysis and speed reading.',
],
[
'title' => 'Grammar',
'description' => '10 minutes evaluation of structural accuracy and formal writing style.',
],
[
'title' => 'Vocabulary',
'description' => '10 minutes evaluation word knowledge, collocation, and academic terms.',
],
]);

$testItems = ($freeTestCategories ?? collect())->isNotEmpty()
? $freeTestCategories
: $fallbackTests;
@endphp

<section class="bg-blue-50/60">
    <div class="mx-auto max-w-7xl px-4 py-16 lg:px-8">
        <div class="mx-auto max-w-3xl text-center">
            <h2 class="reveal text-2xl font-bold text-slate-900 md:text-3xl">
                Free English <span class="text-[var(--color-brand-gold)]">Test</span>
            </h2>

            <p class="reveal reveal-delay-1 mt-4 text-base leading-8 text-slate-600">
                Take a quick placement test to find your level and get a course recommendation —
                no login required. Instant score • 10–20 minutes • Online
            </p>
        </div>

        <div class="mt-12 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($testItems as $index => $item)
            @php
            $delayClass = match ($index % 4) {
            1 => 'reveal-delay-1',
            2 => 'reveal-delay-2',
            3 => 'reveal-delay-3',
            default => '',
            };

            $title = is_array($item) ? $item['title'] : $item->name;
            $description = is_array($item)
            ? $item['description']
            : ($item->description ?: 'Take a quick test to measure your English skill in this area.');
            @endphp

            <div class="reveal {{ $delayClass }}">
                <x-public.test-card
                    :title="$title"
                    :description="$description"
                    :href="route('free-test')">
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7h8M8 12h5m-5 5h8" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 3h12a2 2 0 012 2v14l-4-2-4 2-4-2-4 2V5a2 2 0 012-2z" />
                        </svg>
                    </x-slot:icon>
                </x-public.test-card>
            </div>
            @endforeach
        </div>
    </div>
</section>