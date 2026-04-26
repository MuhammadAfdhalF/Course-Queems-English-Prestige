@extends('layouts.student')

@section('content')
<section class="mx-auto max-w-7xl space-y-8 py-6">
    <div class="reveal">
        <h1 class="text-4xl font-bold leading-tight text-slate-900 lg:text-5xl">
            Student Profile
        </h1>
        <p class="mt-3 text-lg text-slate-500">
            Manage your personal information and account security.
        </p>
    </div>

    <div class="grid gap-8 lg:grid-cols-[360px_minmax(0,1fr)]">
        <div class="reveal reveal-delay-1 space-y-6">
            <x-student.profile-sidebar />
        </div>

        <div class="space-y-8">
            <div class="reveal reveal-delay-1">
                <x-student.profile-personal-form />
            </div>

            <div class="reveal reveal-delay-2">
                <x-student.profile-additional-form />
            </div>

            <div class="reveal reveal-delay-3">
                <x-student.profile-security-form />
            </div>
        </div>
    </div>
</section>
@endsection