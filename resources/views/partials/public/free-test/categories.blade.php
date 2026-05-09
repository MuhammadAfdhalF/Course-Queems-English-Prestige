@php
$availableTests = $freeTests ?? collect();
@endphp

<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-16 lg:px-8">
        <div class="flex flex-col gap-3 border-b border-slate-200 pb-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <x-public.section-title class="reveal text-3xl md:text-4xl">
                    Available Free [gold]Tests[/gold]
                </x-public.section-title>

                <p class="reveal reveal-delay-1 mt-2 text-sm text-slate-500">
                    Pick a test to begin your assessment.
                </p>
            </div>
        </div>

        @if ($availableTests->isNotEmpty())
        <div class="mt-8 grid max-w-5xl items-stretch gap-6 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($availableTests as $index => $freeTest)
            @php
            $delayClass = match ($index % 4) {
            1 => 'reveal-delay-1',
            2 => 'reveal-delay-2',
            3 => 'reveal-delay-3',
            default => '',
            };

            $duration = $freeTest->duration_minutes
            ? $freeTest->duration_minutes . ' mins'
            : 'Flexible';

            $questionCount = $freeTest->questions_count ?: $freeTest->total_questions;

            $category = $freeTest->categoryRelation?->name;
            @endphp

            <div class="reveal {{ $delayClass }}">
                <x-public.test-card
                    :title="$freeTest->title"
                    :description="$freeTest->description ?: 'Take this free assessment and discover your current English level.'"
                    :duration="$duration"
                    :questions="$questionCount"
                    :category="$category"
                    :href="route('free-test.show', $freeTest)">
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
        @else
        <div class="reveal mt-8 rounded-[24px] border border-slate-200 bg-slate-50 px-6 py-12 text-center">
            <h3 class="text-2xl font-bold text-slate-900">
                No active free tests yet.
            </h3>

            <p class="mx-auto mt-3 max-w-xl text-sm leading-7 text-slate-500">
                Please check back later. Our team is preparing free assessments for you.
            </p>
        </div>
        @endif
    </div>
</section>