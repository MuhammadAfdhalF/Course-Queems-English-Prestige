@extends('layouts.public')

@section('content')
@php
$result = [
'score' => 78,
'max_score' => 100,
'level' => 'Intermediate (B2) Level',
'summary' => 'You can understand the main ideas of complex text on both concrete and abstract topics, including technical discussions in your field of specialization.',
'breakdown' => [
['label' => 'Grammar', 'value' => 85, 'icon' => 'grammar'],
['label' => 'Vocabulary', 'value' => 70, 'icon' => 'vocabulary'],
['label' => 'Reading', 'value' => 80, 'icon' => 'reading'],
],
];
@endphp

<section class="bg-[#f7f6f2]">
    <div class="mx-auto max-w-7xl px-4 py-14 lg:px-8 lg:py-16">
        <div class="mx-auto max-w-[760px]">
            <div>
                <h1 class="reveal text-4xl font-bold tracking-tight text-slate-900">
                    Your Test Result
                </h1>
                <p class="reveal reveal-delay-1 mt-3 text-base text-slate-500">
                    Congratulations on completing the test! Here is how you performed.
                </p>
            </div>

            <div class="mt-10 space-y-8">
                <div class="reveal rounded-[20px] border border-slate-200 bg-white px-6 py-10 lg:px-10 lg:py-12">
                    <div class="flex flex-col items-center text-center">
                        <x-public.test-score-ring
                            :score="$result['score']"
                            :maxScore="$result['max_score']" />

                        <div class="mt-8">
                            <x-public.test-level-badge>
                                <x-slot:icon>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3l7 4v5c0 5-3.5 8-7 9-3.5-1-7-4-7-9V7l7-4z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4" />
                                    </svg>
                                </x-slot:icon>
                                {{ $result['level'] }}
                            </x-public.test-level-badge>
                        </div>

                        <p class="mt-6 max-w-md text-sm leading-7 text-slate-500">
                            {{ $result['summary'] }}
                        </p>
                    </div>
                </div>

                <div>
                    <h2 class="reveal text-3xl font-bold tracking-tight text-slate-900">
                        Your Performance Breakdown
                    </h2>

                    <div class="mt-6 space-y-4">
                        @foreach ($result['breakdown'] as $index => $item)
                        @php
                        $delayClass = match($index) {
                        1 => 'reveal-delay-1',
                        2 => 'reveal-delay-2',
                        default => '',
                        };
                        @endphp
                        <div class="reveal {{ $delayClass }}">
                            <x-public.test-breakdown-item
                                :label="$item['label']"
                                :value="$item['value']"
                                :icon="$item['icon']" />
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="reveal reveal-delay-2 rounded-[20px] border border-[#efe4a7] bg-[#f7efb8] p-6 lg:p-8">
                    <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                        <div class="max-w-xl">
                            <div class="flex items-center gap-2 text-[var(--color-brand-gold)]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3a6 6 0 016 6c0 2.2-1.2 4.1-3 5.2V17a1 1 0 01-1 1h-4a1 1 0 01-1-1v-2.8A5.98 5.98 0 016 9a6 6 0 016-6z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 21h4" />
                                </svg>
                                <span class="text-sm font-bold uppercase tracking-[0.16em]">Next Steps for You</span>
                            </div>

                            <p class="mt-4 text-base leading-8 text-slate-700">
                                Based on your strong <span class="font-bold">Grammar</span> and <span class="font-bold">Reading</span> scores, we recommend focusing on advanced conversational structures. Enrolling in an Intensive IELTS or TOEFL preparation course would be the ideal next step to bridge the gap to C1 proficiency.
                            </p>
                        </div>

                        <div class="shrink-0">
                            <x-ui.button variant="gold" class="px-6 py-3">
                                View Recommended Courses
                            </x-ui.button>
                        </div>
                    </div>
                </div>

                <div class="reveal reveal-delay-3 rounded-[20px] border border-[#dbe4f1] bg-[#eaf1fb] p-6 lg:p-8">
                    <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                        <div class="max-w-sm">
                            <h3 class="text-2xl font-bold text-slate-900">
                                Ready to take the next step in your education?
                            </h3>
                            <p class="mt-3 text-base leading-7 text-slate-500">
                                Our experts are here to help you choose the right path.
                            </p>
                        </div>

                        <div class="flex flex-col gap-3 sm:flex-row">
                            <a href="{{ route('courses') }}">
                                <x-ui.button class="w-full px-6 py-3 sm:w-auto">
                                    Explore All Courses
                                </x-ui.button>
                            </a>

                            <a
                                href="#"
                                class="motion-button inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-900 transition hover:bg-slate-50 sm:w-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 10h8M8 14h5" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 12c0 4.97-4.477 9-10 9-1.308 0-2.56-.226-3.702-.642L3 21l1.274-3.788C3.473 15.781 3 13.95 3 12 3 7.03 7.477 3 13 3s10 4.03 10 9z" />
                                </svg>
                                <span>Consult via WhatsApp</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection