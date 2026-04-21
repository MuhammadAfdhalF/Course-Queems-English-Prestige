@extends('layouts.student')

@section('content')
<section class="mx-auto max-w-7xl space-y-8">
    <x-student.pending-banner />

    <div>
        <h1 class="text-4xl font-bold leading-tight text-slate-900 lg:text-5xl">Student Dashboard</h1>
        <p class="mt-2 text-lg text-slate-500 lg:text-2xl">Welcome back to your academic portal.</p>
    </div>

    <div class="grid gap-8 xl:grid-cols-[1.7fr_1fr]">
        <x-student.welcome-card />

        <x-student.certificate-panel />
    </div>

    <div class="space-y-4">
        <h2 class="text-2xl font-bold text-slate-900 lg:text-3xl">Continue Learning</h2>

        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            <x-student.course-progress-card
                title="IELTS Academic Masterclass"
                level="C1 Advanced"
                progress="75"
                image="https://placehold.co/800x500/E9ECEF/1E293B?text=IELTS" />

            <x-student.course-progress-card
                title="Business Communication Pro"
                level="B2 Upper Int"
                progress="32"
                image="https://placehold.co/800x500/D6E4F0/1E293B?text=Business" />

            <x-student.course-progress-card
                title="Advanced Grammar Workshop"
                level="B1 Intermediate"
                progress="90"
                image="https://placehold.co/800x500/DCE8C9/1E293B?text=Grammar" />

            <x-student.course-progress-card
                title="Presentation Skills Essentials"
                level="B2 Intermediate"
                progress="54"
                image="https://placehold.co/800x500/F2E3D5/1E293B?text=Presentation" />
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
        <x-student.summary-stat-card
            title="Active Courses"
            value="04"
            description="+1 this month"
            accent="blue"
            icon="book" />

        <x-student.summary-stat-card
            title="Pending Orders"
            value="01"
            description="ACTION REQUIRED"
            accent="orange"
            icon="cart" />

        <x-student.summary-stat-card
            title="Completed Courses"
            value="12"
            description='"Consistent learner"'
            accent="green"
            icon="shield" />

        <x-student.summary-stat-card
            title="Exams Available"
            value="02"
            description="Bonus points available"
            accent="gold"
            icon="clock" />
    </div>

    <div class="flex flex-col items-center justify-center gap-4 pt-2 sm:flex-row">
        <x-ui.button class="min-w-[220px] px-6 py-3">
            Go to My Courses
        </x-ui.button>

        <x-ui.button variant="outline" class="min-w-[220px] px-6 py-3">
            Browse All Courses
        </x-ui.button>
    </div>
</section>
@endsection