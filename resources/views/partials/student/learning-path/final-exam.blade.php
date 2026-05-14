@if ($finalExam)
<div class="reveal reveal-delay-2">
    <x-student.final-exam-panel
        :title="$finalExam->title"
        :description="$finalExam->description"
        :passing-grade="$finalExam->passing_grade"
        :max-attempts="$finalExam->max_attempts"
        :is-unlocked="$isFinalExamUnlocked"
        :latest-attempt="$latestFinalExamAttempt"
        :can-retake="$canRetakeFinalExam"
        :start-href="route('student.final-exam', ['enrollment' => $enrollment, 'finalExam' => $finalExam])"
        :result-href="$latestFinalExamAttempt ? route('student.final-exam-result', ['enrollment' => $enrollment, 'attempt' => $latestFinalExamAttempt]) : null" />
</div>
@endif