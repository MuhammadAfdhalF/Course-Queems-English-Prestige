@extends('layouts.student')

@section('content')
<section class="mx-auto max-w-7xl space-y-8">
    <x-ui.alert variant="warning">
        You have a pending order. Please complete your payment to access your course.
    </x-ui.alert>

    <div>
        <h1 class="text-4xl font-bold text-slate-900">Student Dashboard</h1>
        <p class="mt-2 text-slate-600">Welcome back to your academic portal.</p>
    </div>

    <x-ui.card class="overflow-hidden bg-gradient-to-r from-blue-700 to-blue-500 text-white shadow-sm">
        <div class="max-w-2xl space-y-4">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-100">
                Academic Status: Active
            </p>

            <h2 class="text-4xl font-bold">Hi, Alex Johnson!</h2>

            <p class="text-base leading-7 text-blue-50">
                Ready to continue your English journey? You’re doing great and making strong
                progress this month.
            </p>

            <div class="pt-2">
                <x-ui.button variant="secondary">Continue Learning</x-ui.button>
            </div>
        </div>
    </x-ui.card>

    <div class="space-y-4">
        <div>
            <h3 class="text-2xl font-bold text-slate-900">Continue Learning</h3>
            <p class="mt-1 text-slate-600">Pick up where you left off.</p>
        </div>

        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            <x-ui.course-card
                title="IELTS Academic Masterclass"
                level="C1 Advanced"
                mode="Online"
                price="Rp 1,500,000"
                progress="75"
                image="https://placehold.co/600x400/e2e8f0/1e293b?text=IELTS" />

            <x-ui.course-card
                title="Business Communication Pro"
                level="B2 Upper Int"
                mode="Online"
                price="Rp 1,250,000"
                progress="32"
                image="https://placehold.co/600x400/dbeafe/1e3a8a?text=Business" />

            <x-ui.course-card
                title="Advanced Grammar Workshop"
                level="B1 Intermediate"
                mode="Online"
                price="Rp 980,000"
                progress="90"
                image="https://placehold.co/600x400/d9f99d/365314?text=Grammar" />
        </div>
    </div>
</section>
@endsection