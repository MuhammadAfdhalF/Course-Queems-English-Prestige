@extends('layouts.public')

@section('content')
@php
$test = [
'title' => 'Grammar Test',
'subtitle' => 'Check your English grammar skills',
'total_questions' => 10,
'duration' => '10 Minutes',
'requires_login' => false,
'current_question' => 10,
'progress' => 100,
];

$questionOneOptions = [
'She go to school every day.',
'She goes to school every day.',
'She going to school every day.',
'She have gone to school every day.',
];

$questionThreeDots = [
['active' => false],
['active' => false],
['active' => false],
['active' => true],
];
@endphp

<div class="reveal">
    @include('partials.public.free-test.test-header', ['test' => $test])
</div>

@include('partials.public.free-test.test-progress', ['progress' => $test['progress']])

<section class="relative overflow-hidden bg-[#f7f6f2]">
    <div class="absolute bottom-14 right-10 hidden opacity-10 lg:block">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-44 w-44 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M4 5.5A2.5 2.5 0 016.5 3H11v16H6.5A2.5 2.5 0 014 16.5v-11zM20 5.5A2.5 2.5 0 0017.5 3H13v16h4.5a2.5 2.5 0 002.5-2.5v-11z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M11 5s1-.75 2-.75S15 5 15 5" />
        </svg>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-12 lg:px-8 lg:py-16">
        <div class="mx-auto max-w-[760px] space-y-8">
            <div class="reveal">
                <x-public.test-question-card
                    number="01"
                    question="Choose the correct sentence.">
                    <div class="space-y-2.5">
                        @foreach ($questionOneOptions as $index => $option)
                        <x-public.test-option
                            :selected="$index === 1"
                            :label="$option" />
                        @endforeach
                    </div>
                </x-public.test-question-card>
            </div>

            <div class="reveal reveal-delay-1">
                <x-public.test-question-card
                    number="03"
                    question="Fill in the blank: If I ___ more time...">
                    <x-public.test-input-question
                        placeholder="Type your answer..."
                        helper="One word only" />
                </x-public.test-question-card>
            </div>

            <div class="reveal reveal-delay-2">
                @include('partials.public.free-test.test-navigation', [
                'previousHref' => route('free-test'),
                'nextHref' => route('free-test.result'),
                'nextLabel' => 'Finish Test',
                'dots' => $questionThreeDots,
                ])
            </div>
        </div>
    </div>
</section>
@endsection