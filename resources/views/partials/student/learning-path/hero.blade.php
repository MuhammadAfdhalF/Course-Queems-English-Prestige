<div class="reveal reveal-delay-1">
    <x-student.course-learning-hero
        :title="$courseLevel->name"
        :level="$courseLevel->courseProgram?->name ?? 'Course Program'"
        :progress="(int) $enrollment->progress_percentage"
        :modules-completed="'0 of ' . $modulesCount . ' modules'"
        :continue-href="$continueHref" />
</div>