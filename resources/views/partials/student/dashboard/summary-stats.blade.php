<div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
    <div class="reveal">
        <x-student.summary-stat-card
            title="Active Courses"
            value="04"
            description="+1 this month"
            accent="blue"
            icon="book" />
    </div>

    <div class="reveal reveal-delay-1">
        <x-student.summary-stat-card
            title="Pending Orders"
            value="01"
            description="ACTION REQUIRED"
            accent="orange"
            icon="cart" />
    </div>

    <div class="reveal reveal-delay-2">
        <x-student.summary-stat-card
            title="Completed Courses"
            value="12"
            description='"Consistent learner"'
            accent="green"
            icon="shield" />
    </div>

    <div class="reveal reveal-delay-3">
        <x-student.summary-stat-card
            title="Exams Available"
            value="02"
            description="Bonus points available"
            accent="gold"
            icon="clock" />
    </div>
</div>