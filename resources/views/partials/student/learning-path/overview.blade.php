<div class="reveal reveal-delay-1">
    <x-student.course-overview-panel
        :description="$courseLevel->description ?: $courseLevel->short_description"
        :learning-mode="$courseLevel->learning_mode"
        :access-type="$courseLevel->access_type"
        :access-duration-days="$courseLevel->access_duration_days"
        :modules-count="$modulesCount"
        :has-final-exam="(bool) $finalExam" />
</div>