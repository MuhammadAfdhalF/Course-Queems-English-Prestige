@extends('layouts.public')

@section('content')
@php
$learningModeLabel = match ($courseLevel->learning_mode) {
'offline' => 'Offline',
'hybrid' => 'Hybrid',
default => 'Online',
};

$accessLabel = $courseLevel->access_type === 'limited'
? ($courseLevel->access_duration_days . ' days access')
: 'Lifetime access';

$thumbnailUrl = $courseLevel->thumbnail_file
? asset('storage/' . $courseLevel->thumbnail_file)
: 'https://placehold.co/900x600/EEF3FF/2457E6?text=Queens+English';

$videoPosterUrl = $courseLevel->video_poster_file
? asset('storage/' . $courseLevel->video_poster_file)
: null;

$studentWhatsapp = $student->studentProfile?->whatsapp ?? '-';
@endphp

<section class="bg-slate-50">
    <div class="mx-auto max-w-7xl px-4 py-12 lg:px-8 lg:py-16">
        <div class="mb-8">
            <a
                href="{{ route('courses.show', $courseLevel) }}"
                class="motion-link-arrow inline-flex items-center gap-2 text-sm font-bold text-[var(--color-brand-blue)] hover:opacity-80">
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </span>
                Back to Course Detail
            </a>
        </div>

        <div class="grid gap-8 lg:grid-cols-[minmax(0,1.45fr)_minmax(360px,0.75fr)]">
            {{-- COURSE SUMMARY --}}
            <div class="reveal rounded-[28px] border border-slate-200 bg-white p-5 shadow-sm lg:p-7">
                <div class="overflow-hidden rounded-[22px] bg-slate-100">
                    @if ($courseLevel->thumbnail_file && $courseLevel->thumbnail_type === 'video')
                    <video
                        src="{{ $thumbnailUrl }}"
                        @if($videoPosterUrl) poster="{{ $videoPosterUrl }}" @endif
                        controls
                        preload="metadata"
                        class="h-[260px] w-full bg-slate-900 object-cover lg:h-[360px]">
                    </video>
                    @else
                    <img
                        src="{{ $thumbnailUrl }}"
                        alt="{{ $courseLevel->name }}"
                        class="h-[260px] w-full object-cover lg:h-[360px]">
                    @endif
                </div>

                <div class="mt-7">
                    <p class="text-xs font-bold uppercase tracking-[0.22em] text-[var(--color-brand-gold)]">
                        Order Confirmation
                    </p>

                    <h1 class="mt-3 text-3xl font-extrabold leading-tight text-slate-900 lg:text-5xl">
                        {{ $courseLevel->name }}
                    </h1>

                    <p class="mt-3 text-base font-semibold text-slate-500">
                        {{ $courseLevel->courseProgram?->name ?? 'Course Program' }}
                    </p>

                    @if ($courseLevel->short_description)
                    <p class="mt-5 max-w-3xl text-base leading-8 text-slate-600">
                        {{ $courseLevel->short_description }}
                    </p>
                    @endif
                </div>

                <div class="mt-8 grid gap-4 sm:grid-cols-3">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">
                            Tuition
                        </p>
                        <p class="mt-2 text-lg font-extrabold text-[#D4A017]">
                            Rp {{ number_format((float) $courseLevel->price, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">
                            Learning Mode
                        </p>
                        <p class="mt-2 text-lg font-extrabold text-slate-900">
                            {{ $learningModeLabel }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">
                            Access
                        </p>
                        <p class="mt-2 text-lg font-extrabold text-slate-900">
                            {{ $accessLabel }}
                        </p>
                    </div>
                </div>

                <div class="mt-8 rounded-[22px] border border-blue-100 bg-blue-50/60 p-5">
                    <h2 class="text-base font-extrabold text-[var(--color-brand-blue)]">
                        What happens after you submit?
                    </h2>

                    <div class="mt-4 grid gap-4 md:grid-cols-3">
                        <div class="rounded-2xl bg-white p-4 shadow-sm">
                            <p class="text-sm font-extrabold text-slate-900">
                                1. Order Received
                            </p>
                            <p class="mt-2 text-sm leading-6 text-slate-500">
                                Your order will be saved as pending.
                            </p>
                        </div>

                        <div class="rounded-2xl bg-white p-4 shadow-sm">
                            <p class="text-sm font-extrabold text-slate-900">
                                2. Admin Verification
                            </p>
                            <p class="mt-2 text-sm leading-6 text-slate-500">
                                Our admin will contact you via WhatsApp.
                            </p>
                        </div>

                        <div class="rounded-2xl bg-white p-4 shadow-sm">
                            <p class="text-sm font-extrabold text-slate-900">
                                3. Course Access
                            </p>
                            <p class="mt-2 text-sm leading-6 text-slate-500">
                                Once approved, the course will appear in My Courses.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ORDER FORM --}}
            <aside class="reveal reveal-delay-1 lg:sticky lg:top-24">
                <div class="rounded-[28px] border border-slate-200 bg-white p-5 shadow-sm lg:p-6">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.22em] text-slate-400">
                            Student Information
                        </p>

                        <h2 class="mt-2 text-2xl font-extrabold text-slate-900">
                            Confirm Your Order
                        </h2>

                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Please make sure your account information is correct before submitting this order.
                        </p>
                    </div>

                    @if ($errors->any())
                    <div class="mt-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700">
                        {{ $errors->first() }}
                    </div>
                    @endif

                    <div class="mt-6 space-y-4">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">
                                Full Name
                            </p>
                            <p class="mt-1 text-sm font-extrabold text-slate-900">
                                {{ $student->name }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">
                                Email
                            </p>
                            <p class="mt-1 break-all text-sm font-extrabold text-slate-900">
                                {{ $student->email }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">
                                WhatsApp
                            </p>
                            <p class="mt-1 text-sm font-extrabold text-slate-900">
                                {{ $studentWhatsapp }}
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('courses.order.store', $courseLevel) }}" method="POST" class="mt-6">
                        @csrf

                        <div>
                            <label for="note" class="mb-2 block text-sm font-bold text-slate-900">
                                Notes for Admin
                                <span class="font-semibold text-slate-400">(optional)</span>
                            </label>

                            <textarea
                                id="note"
                                name="note"
                                rows="4"
                                placeholder="Example: I prefer weekend class schedule."
                                class="focus-brand w-full resize-none rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400">{{ old('note') }}</textarea>
                        </div>

                        <button
                            type="submit"
                            class="motion-button mt-5 inline-flex h-13 w-full items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-extrabold text-white shadow-md transition hover:opacity-95">
                            Submit Order
                        </button>
                    </form>

                    <p class="mt-4 text-center text-xs leading-6 text-slate-500">
                        By submitting this order, you agree to be contacted by Queens English Prestige admin via WhatsApp.
                    </p>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection