@extends('layouts.public')

@section('content')
@php
$freeTest = $freeTestResult->freeTest;

$maxScore = $freeTest
? (int) $freeTest->questions()->where('is_active', true)->sum('score')
: 100;

$maxScore = $maxScore > 0 ? $maxScore : 100;

$score = (int) $freeTestResult->total_score;
$passingGrade = (int) ($freeTest?->passing_grade ?? 0);
$isPassed = $passingGrade > 0 ? $score >= $passingGrade : false;

$levelLabel = $isPassed
? 'Passed'
: 'Need Improvement';

$summary = $freeTestResult->recommendation ?: 'Thank you for completing the free test. Our team can help you choose the right program based on your result.';

$whatsappMessage = 'Hello Queens English Prestige, I have completed the free test and would like to consult about the recommended course.';
$whatsappHref = 'https://wa.me/6285274979336?text=' . urlencode($whatsappMessage);

$scorePercentage = $maxScore > 0
? round(($score / $maxScore) * 100)
: 0;
@endphp

<section class="bg-[#f7f6f2]">
    <div class="mx-auto max-w-7xl px-4 py-14 lg:px-8 lg:py-16">
        <div class="mx-auto max-w-[760px]">
            <div>
                <h1 class="reveal text-4xl font-bold tracking-tight text-slate-900">
                    Your Test Result
                </h1>
                <p class="reveal reveal-delay-1 mt-3 text-base text-slate-500">
                    Congratulations on completing {{ $freeTest?->title ?? 'the free test' }}.
                </p>
            </div>

            <div class="mt-10 space-y-8">
                <div class="reveal rounded-[20px] border border-slate-200 bg-white px-6 py-10 lg:px-10 lg:py-12">
                    <div class="flex flex-col items-center text-center">
                        <x-public.test-score-ring
                            :score="$score"
                            :maxScore="$maxScore" />

                        <div class="mt-8">
                            <x-public.test-level-badge>
                                <x-slot:icon>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        @if ($isPassed)
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3l7 4v5c0 5-3.5 8-7 9-3.5-1-7-4-7-9V7l7-4z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4" />
                                        @else
                                        <circle cx="12" cy="12" r="9" stroke-width="1.8" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4m0 4h.01" />
                                        @endif
                                    </svg>
                                </x-slot:icon>
                                {{ $levelLabel }}
                            </x-public.test-level-badge>
                        </div>

                        <p class="mt-6 max-w-md text-sm leading-7 text-slate-500">
                            {{ $summary }}
                        </p>

                        <div class="mt-6 grid w-full max-w-md gap-3 sm:grid-cols-2">
                            <div class="rounded-2xl bg-slate-50 p-4">
                                <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                                    Passing Grade
                                </p>
                                <p class="mt-1 text-xl font-bold text-slate-900">
                                    {{ $passingGrade ?: '-' }}
                                </p>
                            </div>

                            <div class="rounded-2xl bg-slate-50 p-4">
                                <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                                    Percentage
                                </p>
                                <p class="mt-1 text-xl font-bold text-slate-900">
                                    {{ $scorePercentage }}%
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="reveal reveal-delay-1 rounded-[20px] border border-slate-200 bg-white p-6 lg:p-8">
                    <h2 class="text-2xl font-bold tracking-tight text-slate-900">
                        Participant Detail
                    </h2>

                    <div class="mt-5 grid gap-4 sm:grid-cols-2">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                                Name
                            </p>
                            <p class="mt-1 text-sm font-semibold text-slate-700">
                                {{ $freeTestResult->participant_name }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                                Email
                            </p>
                            <p class="mt-1 text-sm font-semibold text-slate-700">
                                {{ $freeTestResult->participant_email }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                                WhatsApp
                            </p>
                            <p class="mt-1 text-sm font-semibold text-slate-700">
                                {{ $freeTestResult->participant_whatsapp }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                                Submitted At
                            </p>
                            <p class="mt-1 text-sm font-semibold text-slate-700">
                                {{ $freeTestResult->submitted_at?->format('M d, Y H:i') ?? '-' }}
                            </p>
                        </div>
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
                                {{ $summary }}
                            </p>
                        </div>

                        <div class="shrink-0">
                            <a href="{{ route('courses') }}">
                                <x-ui.button variant="gold" class="px-6 py-3">
                                    View Recommended Courses
                                </x-ui.button>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="reveal reveal-delay-3 rounded-[20px] border border-[#dbe4f1] bg-[#eaf1fb] p-6 lg:p-8">
                    <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                        <div class="max-w-sm">
                            <h3 class="text-2xl font-bold text-slate-900">
                                Ready to take the next step?
                            </h3>
                            <p class="mt-3 text-base leading-7 text-slate-500">
                                Our team can help you choose the right course based on your result.
                            </p>
                        </div>

                        <div class="flex flex-col gap-3 sm:flex-row">
                            <a href="{{ route('courses') }}">
                                <x-ui.button class="w-full px-6 py-3 sm:w-auto">
                                    Explore All Courses
                                </x-ui.button>
                            </a>

                            <a
                                href="{{ $whatsappHref }}"
                                target="_blank"
                                rel="noopener noreferrer"
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

                <div class="reveal reveal-delay-4 flex justify-center">
                    <a
                        href="{{ route('free-test') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                        Take Another Test
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection