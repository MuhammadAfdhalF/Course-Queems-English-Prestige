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
'placeholder' => 'Type your answer here',
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
'audioCurrent' => '01:14',
'audioTotal' => '03:45',
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
        remainingSeconds: 30 * 60,
        submitUrl: '{{ route('student.final-exam-result') }}',
        intervalId: null,

        init() {
            this.intervalId = setInterval(() => {
                if (this.remainingSeconds > 0) {
                    this.remainingSeconds--;
                } else {
                    clearInterval(this.intervalId);
                }
            }, 1000);
        },

        formatTime() {
            const minutes = Math.floor(this.remainingSeconds / 60).toString().padStart(2, '0');
            const seconds = (this.remainingSeconds % 60).toString().padStart(2, '0');
            return `${minutes}:${seconds}`;
        },

        get unansweredCount() {
            return Object.values(this.answers).filter(value => !String(value).trim()).length;
        },

        submitExam() {
            if (this.unansweredCount > 0) {
                alert(`You still have ${this.unansweredCount} unanswered question(s).`);
                return;
            }

            window.location.href = this.submitUrl;
        }
    }"
    class="mx-auto max-w-5xl space-y-6">
    @include('partials.student.final-exam.back-link')
    @include('partials.student.final-exam.hero-info')
    @include('partials.student.final-exam.questions')
    @include('partials.student.final-exam.footer-bar')
</section>
@endsection