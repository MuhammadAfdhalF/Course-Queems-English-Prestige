@if ($finalExam)
<div class="reveal reveal-delay-2">
    <x-student.final-exam-panel
        :title="$finalExam->title"
        :description="$finalExam->description"
        :passing-grade="$finalExam->passing_grade"
        :max-attempts="$finalExam->max_attempts" />
</div>
@endif