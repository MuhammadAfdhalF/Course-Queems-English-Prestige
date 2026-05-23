@extends('layouts.student')

@section('content')
<section class="mx-auto max-w-6xl space-y-8 py-8">
    <div class="overflow-hidden rounded-[30px] border border-slate-200 bg-white shadow-sm">
        <div class="relative overflow-hidden bg-gradient-to-br from-[#080D4D] via-[#101C72] to-[#AD6B10] px-7 py-10 text-white lg:px-10">
            <div class="pointer-events-none absolute -right-20 -top-20 h-52 w-52 rounded-full bg-white/10 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-24 left-20 h-52 w-52 rounded-full bg-[#AD6B10]/30 blur-3xl"></div>

            <div class="relative max-w-3xl">
                <p class="text-xs font-black uppercase tracking-[0.22em] text-white/60">
                    Student Feedback
                </p>

                <h1 class="mt-3 text-4xl font-black leading-tight lg:text-5xl">
                    Testimoni
                </h1>

                <p class="mt-4 text-base font-semibold leading-8 text-white/80">
                    Share your learning experience, unlock eligible course certificates, and help Queens English Prestige improve.
                </p>
            </div>
        </div>
    </div>

    <x-admin.flash-message />

    @include('partials.student.testimoni.form')

    @include('partials.student.testimoni.history')

    <p class="text-center text-sm text-slate-500">
        Mengalami kendala teknis?
        <a href="#" class="font-semibold text-[var(--color-brand-blue)] hover:underline">
            Hubungi Tim IT Support kami
        </a>
    </p>
</section>
@endsection