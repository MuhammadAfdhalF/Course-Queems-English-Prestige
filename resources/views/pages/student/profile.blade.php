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

    @if (session('success'))
    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-bold text-emerald-700">
        {{ session('success') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-semibold text-rose-700">
        <p class="font-extrabold">Please check the form again.</p>

        <ul class="mt-2 list-inside list-disc space-y-1">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="grid gap-8 lg:grid-cols-[360px_minmax(0,1fr)]">
        <div class="reveal reveal-delay-1 space-y-6">
            @include('partials.student.profile.sidebar')
        </div>

        <div class="space-y-8">
            <div class="reveal reveal-delay-1">
                @include('partials.student.profile.personal-information')
            </div>

            <div class="reveal reveal-delay-2">
                @include('partials.student.profile.additional-information')
            </div>

            <div class="reveal reveal-delay-3">
                @include('partials.student.profile.security-password')
            </div>
        </div>
    </div>
</section>
@endsection