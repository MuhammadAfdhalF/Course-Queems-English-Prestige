@php
$fallbackTests = collect([
[
'title' => 'Listening Test',
'description' => 'Assess your listening comprehension with short English audio-based questions.',
'duration' => '15 mins',
'questions' => null,
'category' => 'Listening',
'href' => route('free-test'),
],
[
'title' => 'Reading Test',
'description' => 'Measure your reading comprehension and vocabulary understanding.',
'duration' => '20 mins',
'questions' => null,
'category' => 'Reading',
'href' => route('free-test'),
],
[
'title' => 'Grammar',
'description' => 'Evaluate your grammar accuracy, structure, and sentence understanding.',
'duration' => '10 mins',
'questions' => null,
'category' => 'Grammar',
'href' => route('free-test'),
],
[
'title' => 'Vocabulary',
'description' => 'Check your word knowledge, collocation, and academic vocabulary range.',
'duration' => '10 mins',
'questions' => null,
'category' => 'Vocabulary',
'href' => route('free-test'),
],
]);

$homeFreeTests = ($freeTests ?? collect())->isNotEmpty()
? $freeTests
: (($freeTestCategories ?? collect())->isNotEmpty()
? $freeTestCategories
: $fallbackTests);
@endphp

<section class="bg-blue-50/60">
    <div class="mx-auto max-w-7xl px-4 py-16 lg:px-8">
        <div class="mx-auto max-w-3xl text-center">
            <x-public.section-title class="reveal text-2xl md:text-3xl">
                Free English [gold]Test[/gold]
            </x-public.section-title>

            <p class="reveal reveal-delay-1 mx-auto mt-4 max-w-2xl text-base leading-8 text-slate-600">
                Take a quick placement test to find your level and get a course recommendation —
                no login required. Instant score • 10–20 minutes • Online
            </p>
        </div>

        <div class="mx-auto mt-12 grid max-w-5xl items-stretch gap-6 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($homeFreeTests as $index => $item)
            @php
            $delayClass = match ($index % 4) {
            1 => 'reveal-delay-1',
            2 => 'reveal-delay-2',
            3 => 'reveal-delay-3',
            default => '',
            };

            $isArray = is_array($item);
            $isFreeTestModel = ! $isArray && isset($item->duration_minutes);

            if ($isArray) {
            $title = $item['title'];
            $description = $item['description'];
            $duration = $item['duration'] ?? '10-20 mins';
            $questions = $item['questions'] ?? null;
            $category = $item['category'] ?? null;
            $href = $item['href'] ?? route('free-test');
            } elseif ($isFreeTestModel) {
            $title = $item->title;
            $description = $item->description ?: 'Take this free assessment and discover your current English level.';
            $duration = $item->duration_minutes ? $item->duration_minutes . ' mins' : 'Flexible';
            $questions = ($item->questions_count ?? null) ?: ($item->total_questions ?? null);
            $category = $item->categoryRelation?->name;
            $href = route('free-test.show', $item);
            } else {
            $title = $item->name;
            $description = $item->description ?: 'Take a quick test to measure your English skill in this area.';
            $duration = '10-20 mins';
            $questions = null;
            $category = null;
            $href = route('free-test');
            }
            @endphp

            <div class="reveal {{ $delayClass }}">
                <x-public.test-card
                    :title="$title"
                    :description="$description"
                    :duration="$duration"
                    :questions="$questions"
                    :category="$category"
                    :href="$href">
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