@extends('layouts.student')

@section('content')
<section class="mx-auto max-w-7xl space-y-8">
    <x-student.pending-banner />

    <div>
        <h1 class="text-5xl font-bold text-slate-900">Student Dashboard</h1>
        <p class="mt-2 text-2xl text-slate-500">Welcome back to your academic portal.</p>
    </div>

    <div class="grid gap-8 xl:grid-cols-[2fr_1fr]">
        <x-student.welcome-card />

        <x-student.certificate-panel />
    </div>
</section>
@endsection