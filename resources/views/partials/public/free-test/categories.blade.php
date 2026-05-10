@php
$availableTests = $freeTests ?? collect();
$availableCategories = $freeTestCategories ?? collect();
$currentCategory = $selectedCategory ?? null;
@endphp

<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-16 lg:px-8">
        <div class="flex flex-col gap-5 border-b border-slate-200 pb-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <x-public.section-title class="reveal text-3xl md:text-4xl">
                    Available Free [gold]Tests[/gold]
                </x-public.section-title>

                <p class="reveal reveal-delay-1 mt-2 text-sm text-slate-500">
                    Pick a test to begin your assessment.
                </p>
            </div>

            @if ($availableTests->isNotEmpty())
            <div class="reveal reveal-delay-1 text-sm font-semibold text-slate-500">
                {{ $availableTests->count() }}
                {{ Str::plural('test', $availableTests->count()) }}
                available
            </div>
            @endif
        </div>

        @if ($availableCategories->isNotEmpty())
        <div class="reveal reveal-delay-2 mt-6 overflow-x-auto">
            <div class="flex min-w-max items-center gap-3 pb-1">
                <a
                    href="{{ route('free-test') }}"
                    class="{{ blank($currentCategory)
                            ? 'inline-flex items-center justify-center rounded-full bg-[var(--color-brand-blue)] px-5 py-2.5 text-sm font-bold text-white shadow-sm'
                            : 'inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-slate-500 transition hover:border-blue-100 hover:bg-blue-50 hover:text-[var(--color-brand-blue)]' }}">
                    All Tests
                </a>

                @foreach ($availableCategories as $category)
                @php
                $isActive = $currentCategory === $category->slug;
                @endphp

                <a
                    href="{{ route('free-test', ['category' => $category->slug]) }}"
                    class="{{ $isActive
                                ? 'inline-flex items-center justify-center gap-2 rounded-full bg-[var(--color-brand-blue)] px-5 py-2.5 text-sm font-bold text-white shadow-sm'
                                : 'inline-flex items-center justify-center gap-2 rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-slate-500 transition hover:border-blue-100 hover:bg-blue-50 hover:text-[var(--color-brand-blue)]' }}">
                    <span>{{ $category->name }}</span>

                    <span class="{{ $isActive
                                ? 'rounded-full bg-white/15 px-2 py-0.5 text-[11px] text-white'
                                : 'rounded-full bg-slate-100 px-2 py-0.5 text-[11px] text-slate-500' }}">
                        {{ $category->free_tests_count }}
                    </span>
                </a>
                @endforeach
            </div>
        </div>
        @endif

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

            <div class="reveal {{ $delayClass }} h-full">
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
                No free tests found.
            </h3>

            <p class="mx-auto mt-3 max-w-xl text-sm leading-7 text-slate-500">
                @if (filled($currentCategory))
                There are no active tests in this category yet. Please choose another category or view all tests.
                @else
                Please check back later. Our team is preparing free assessments for you.
                @endif
            </p>

            @if (filled($currentCategory))
            <div class="mt-7">
                <a href="{{ route('free-test') }}">
                    <x-ui.button class="px-6 py-3">
                        View All Tests
                    </x-ui.button>
                </a>
            </div>
            @endif
        </div>
        @endif
    </div>
</section>