@extends('layouts.student')

@section('content')
@php
$questions = [
[
'id' => 1,
'number' => 'Question 1',
'type' => 'multiple_choice',
'typeLabel' => 'Multiple Choice',
'question' => 'Which of the following elements is most critical in a TOEFL structure sentence completion task?',
'options' => [
'Subject-verb agreement',
'Vocabulary complexity',
'Total word count',
],
],
[
'id' => 2,
'number' => 'Question 2',
'type' => 'multiple_choice',
'typeLabel' => 'Multiple Choice',
'question' => 'Identify the error: "The committee has decided to postponed the meeting until next week."',
'options' => [
'has decided',
'to postponed',
],
],
[
'id' => 3,
'number' => 'Question 3',
'type' => 'multiple_choice',
'typeLabel' => 'Multiple Choice',
'question' => 'Choose the correct connector for: "... he was tired, he finished the assignment."',
'options' => [
'Although',
'Because',
],
],
[
'id' => 7,
'number' => 'Question 7',
'type' => 'fill_blank',
'typeLabel' => 'Fill in the Blank',
'question' => 'Complete the following sentence with the correct preposition: "She is very interested _____ learning more about structural linguistics."',
'placeholder' => 'Type your answer here...',
],
[
'id' => 8,
'number' => 'Question 8',
'type' => 'fill_blank',
'typeLabel' => 'Fill in the Blank',
'question' => 'Convert the verb to the appropriate passive form: "The architect (design) the building last year."',
'placeholder' => 'Type your answer here...',
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
        submitUrl: '{{ route('student.module-completed') }}',
        get unansweredCount() {
            return Object.values(this.answers).filter(value => !String(value).trim()).length;
        },
        submitPractice() {
            if (this.unansweredCount > 0) {
                alert(`You still have ${this.unansweredCount} unanswered question(s).`);
                return;
            }

            window.location.href = this.submitUrl;
        }
    }"
    class="mx-auto max-w-4xl space-y-6">
    @include('partials.student.module-practice.instructions')
    @include('partials.student.module-practice.questions')
    @include('partials.student.module-practice.footer-bar')
</section>
@endsection