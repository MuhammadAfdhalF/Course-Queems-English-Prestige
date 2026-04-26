@extends('layouts.student')

@section('content')
@php
    $questions = [
        [
            'id' => 1,
            'number' => 'Question 1 of 20',
            'category' => 'Reading Comprehension',
            'type' => 'multiple_choice',
            'question' => 'The word "ubiquitous" as used in the third paragraph is closest in meaning to which of the following?',
            'options' => [
                'Existing everywhere at once',
                'Extremely rare and unique',
                'Scientifically significant',
                'Difficult to understand',
            ],
        ],
        [
            'id' => 2,
            'number' => 'Question 2 of 20',
            'category' => 'Vocabulary & Context',
            'type' => 'fill_blank',
            'question' => 'Complete the following sentence with the correct academic vocabulary:',
            'sentence' => '"The researchers found it necessary to _____ their hypothesis after reviewing the preliminary data from the field study."',
            'placeholder' => 'type your answer here',
            'note' => 'Provide one word only. Spelling counts towards your final grade.',
        ],
        [
            'id' => 3,
            'number' => 'Question 3 of 20',
            'category' => 'Listening Inference',
            'type' => 'listening_choice',
            'question' => 'What can be inferred about the professor’s attitude toward the traditional carbon-dating method?',
            'options' => [
                'He believes it is entirely obsolete in modern archaeology.',
                'He is skeptical of its accuracy but acknowledges its historical importance.',
            ],
            'audioProgress' => 34,
            'audioTime' => '01:14 / 03:45',
        ],
    ];

    $initialAnswers = [];
    foreach ($questions as $question) {
        $initialAnswers[$question['id']] = '';
    }
@endphp

<section
    x-data="{
        answers: @js($initialAnswers),
        secondsLeft: 30 * 60,
        resultUrl: '{{ route('student.final-exam-result') }}',
        init() {
            const timer = setInterval(() => {
                if (this.secondsLeft > 0) {
                    this.secondsLeft--;
                } else {
                    clearInterval(timer);
                }
            }, 1000);
        },
        get unansweredCount() {
            return Object.values(this.answers).filter(value => !String(value).trim()).length;
        },
        get formattedTime() {
            const minutes = Math.floor(this.secondsLeft / 60);
            const seconds = this.secondsLeft % 60;
            return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        },
        submitExam() {
            if (this.unansweredCount > 0) {
                alert(`You still have ${this.unansweredCount} unanswered question(s).`);
                return;
            }

            window.location.href = this.resultUrl;
        }
    }"
    class="mx-auto max-w-6xl space-y-6"
>
    <div class="reveal">
        <a href="{{ route('student.learning-path') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-[var(--color-brand-blue)]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Module List
        </a>
    </div>

    <div class="reveal reveal-delay-1">
        <x-student.final-exam-hero-info />
    </div>

    <div class="space-y-5">
        @foreach ($questions as $index => $question)
            @php
                $delayClass = match ($index) {
                    1 => 'reveal-delay-1',
                    2 => 'reveal-delay-2',
                    default => '',
                };
            @endphp

            <div class="reveal {{ $delayClass }}">
                <x-student.final-exam-question-card
                    :question-id="$question['id']"
                    :number="$question['number']"
                    :category="$question['category']"
                    :type="$question['type']"
                    :question="$question['question']"
                    :options="$question['options'] ?? []"
                    :sentence="$question['sentence'] ?? ''"
                    :placeholder="$question['placeholder'] ?? 'Type your answer here'"
                    :note="$question['note'] ?? ''"
                    :audio-progress="$question['audioProgress'] ?? 0"
                    :audio-time="$question['audioTime'] ?? ''"
                />
            </div>
        @endforeach
    </div>

    <div class="reveal reveal-delay-2">
        <x-student.final-exam-footer-bar />
    </div>
</section>
@endsection