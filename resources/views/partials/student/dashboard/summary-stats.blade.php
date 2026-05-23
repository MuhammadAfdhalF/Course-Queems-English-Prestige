<div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
    <div class="reveal">
        <x-student.summary-stat-card
            title="Active Courses"
            :value="str_pad((string) $activeCourseCount, 2, '0', STR_PAD_LEFT)"
            description="Approved access"
            accent="blue"
            icon="book" />
    </div>

    <div class="reveal reveal-delay-1">
        <x-student.summary-stat-card
            title="Pending Orders"
            :value="str_pad((string) $pendingOrderCount, 2, '0', STR_PAD_LEFT)"
            description="Waiting approval"
            accent="orange"
            icon="cart" />
    </div>

    <div class="reveal reveal-delay-2">
        <x-student.summary-stat-card
            title="Completed Courses"
            :value="str_pad((string) $completedCourseCount, 2, '0', STR_PAD_LEFT)"
            description="After final exam"
            accent="green"
            icon="shield" />
    </div>

    <div class="reveal reveal-delay-3">
        <x-student.summary-stat-card
            title="Final Exams"
            :value="str_pad((string) $finalExamAvailableCount, 2, '0', STR_PAD_LEFT)"
            description="In active courses"
            accent="gold"
            icon="clock" />
    </div>
</div>