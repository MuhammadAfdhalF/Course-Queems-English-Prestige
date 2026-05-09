@extends('layouts.public')

@section('content')
@php
$totalQuestions = $questions->count();

$test = [
'title' => $freeTest->title,
'subtitle' => $freeTest->description ?: 'Complete the assessment and get your result instantly.',
'total_questions' => $totalQuestions,
'duration' => $freeTest->duration_minutes ? $freeTest->duration_minutes . ' Minutes' : 'Flexible',
'requires_login' => false,
'current_question' => $totalQuestions,
'progress' => 100,
];

$options = [
'A' => 'option_a',
'B' => 'option_b',
'C' => 'option_c',
'D' => 'option_d',
];
@endphp

<div class="reveal">
    @include('partials.public.free-test.test-header', ['test' => $test])
</div>

@include('partials.public.free-test.test-progress', ['progress' => 100])

<section class="relative overflow-hidden bg-[#f7f6f2]">
    <div class="absolute bottom-14 right-10 hidden opacity-10 lg:block">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-44 w-44 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M4 5.5A2.5 2.5 0 016.5 3H11v16H6.5A2.5 2.5 0 014 16.5v-11zM20 5.5A2.5 2.5 0 0017.5 3H13v16h4.5a2.5 2.5 0 002.5-2.5v-11z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M11 5s1-.75 2-.75S15 5 15 5" />
        </svg>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-12 lg:px-8 lg:py-16">
        <div class="mx-auto max-w-[820px]">
            <div class="reveal rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm lg:p-8">
                <h2 class="text-2xl font-bold text-slate-900">
                    Participant Information
                </h2>

                <p class="mt-2 text-sm leading-7 text-slate-500">
                    Fill in your information before submitting the test. Your result will be shown instantly after submission.
                </p>

                @if ($errors->any())
                <div class="mt-5 rounded-2xl border border-rose-200 bg-rose-50 p-4">
                    <p class="text-sm font-bold text-rose-700">
                        Please check your answers.
                    </p>

                    <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-rose-600">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('free-test.submit', $freeTest) }}" method="POST" class="mt-6 space-y-8">
                    @csrf

                    <div class="grid gap-5 md:grid-cols-3">
                        <div>
                            <label for="participant_name" class="mb-2 block text-sm font-bold text-slate-700">
                                Name <span class="text-rose-500">*</span>
                            </label>
                            <input
                                id="participant_name"
                                name="participant_name"
                                type="text"
                                value="{{ old('participant_name') }}"
                                required
                                placeholder="Your name"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[var(--color-brand-blue)] focus:ring-4 focus:ring-blue-100">
                        </div>

                        <div>
                            <label for="participant_email" class="mb-2 block text-sm font-bold text-slate-700">
                                Email <span class="text-rose-500">*</span>
                            </label>
                            <input
                                id="participant_email"
                                name="participant_email"
                                type="email"
                                value="{{ old('participant_email') }}"
                                required
                                placeholder="you@email.com"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[var(--color-brand-blue)] focus:ring-4 focus:ring-blue-100">
                        </div>

                        <div>
                            <label for="participant_whatsapp" class="mb-2 block text-sm font-bold text-slate-700">
                                WhatsApp <span class="text-rose-500">*</span>
                            </label>
                            <input
                                id="participant_whatsapp"
                                name="participant_whatsapp"
                                type="text"
                                value="{{ old('participant_whatsapp') }}"
                                required
                                placeholder="08xxxxxxxxxx"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[var(--color-brand-blue)] focus:ring-4 focus:ring-blue-100">
                        </div>
                    </div>

                    <div class="space-y-8">
                        @foreach ($questions as $question)
                        @php
                        $number = str_pad($loop->iteration, 2, '0', STR_PAD_LEFT);
                        @endphp

                        <div class="reveal">
                            <x-public.test-question-card
                                :number="$number"
                                :question="$question->question">
                                <div class="space-y-3">
                                    @foreach ($options as $answerKey => $optionField)
                                    @php
                                    $inputId = 'question_' . $question->id . '_' . $answerKey;
                                    $isChecked = old('answers.' . $question->id) === $answerKey;
                                    @endphp

                                    <label
                                        for="{{ $inputId }}"
                                        class="motion-button flex cursor-pointer items-start gap-3 rounded-2xl border px-4 py-4 text-left transition-all duration-200 {{ $isChecked ? 'border-[var(--color-brand-blue)] bg-blue-50 text-[var(--color-brand-blue)] shadow-sm' : 'border-slate-200 bg-white text-slate-700 hover:border-slate-300 hover:bg-slate-50' }}">
                                        <input
                                            id="{{ $inputId }}"
                                            type="radio"
                                            name="answers[{{ $question->id }}]"
                                            value="{{ $answerKey }}"
                                            required
                                            @checked($isChecked)
                                            class="mt-1 h-4 w-4 border-slate-300 text-[var(--color-brand-blue)] focus:ring-[var(--color-brand-blue)]">

                                        <span class="flex-1">
                                            <span class="block text-xs font-extrabold uppercase tracking-[0.16em] text-slate-400">
                                                Option {{ $answerKey }}
                                            </span>
                                            <span class="mt-1 block text-sm font-medium leading-7">
                                                {{ $question->{$optionField} }}
                                            </span>
                                        </span>
                                    </label>
                                    @endforeach
                                </div>
                            </x-public.test-question-card>
                        </div>
                        @endforeach
                    </div>

                    <div class="flex flex-col gap-4 border-t border-slate-200 pt-6 sm:flex-row sm:items-center sm:justify-between">
                        <a
                            href="{{ route('free-test') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-900 bg-white px-6 py-3 text-sm font-semibold text-slate-900 transition hover:bg-slate-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19l-7-7 7-7" />
                            </svg>
                            <span>Back to Tests</span>
                        </a>

                        <button
                            type="submit"
                            class="motion-button inline-flex w-full items-center justify-center rounded-xl bg-[#F4BE1A] px-6 py-3 text-sm font-bold text-slate-900 transition hover:opacity-90 sm:w-auto">
                            <span class="inline-flex items-center gap-2">
                                <span>Submit Test</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 12h14m-6-6l6 6-6 6" />
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection