@extends('layouts.student')

@section('content')
<section class="mx-auto max-w-6xl space-y-8">
    <div class="reveal">
        <a href="{{ route('student.learning-path', $enrollment) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-[var(--color-brand-blue)]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Learning Path
        </a>
    </div>

    <div class="reveal reveal-delay-1 rounded-[26px] border border-slate-200 bg-white p-6 shadow-sm lg:p-8">
        <p class="text-xs font-bold uppercase tracking-[0.2em] text-[var(--color-brand-gold)]">
            {{ $courseLevel->name }}
        </p>

        <h1 class="mt-3 text-4xl font-extrabold leading-tight text-slate-900">
            {{ $module->title }}
        </h1>

        @if ($module->short_description)
        <p class="mt-4 max-w-3xl text-lg leading-8 text-slate-600">
            {{ $module->short_description }}
        </p>
        @endif
    </div>

    @if ($module->opening_media_file)
    <div class="reveal reveal-delay-1 overflow-hidden rounded-[24px] border border-slate-200 bg-white p-4 shadow-sm">
        @if ($module->opening_media_type === 'video')
        <video src="{{ asset('storage/' . $module->opening_media_file) }}" controls class="w-full rounded-2xl bg-slate-900"></video>
        @else
        <img src="{{ asset('storage/' . $module->opening_media_file) }}" alt="{{ $module->title }}" class="w-full rounded-2xl object-cover">
        @endif
    </div>
    @endif

    <div class="reveal reveal-delay-2 space-y-5">
        <h2 class="text-2xl font-bold text-slate-900">
            Module Materials
        </h2>

        @forelse ($materials as $material)
        <article class="rounded-[22px] border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                        {{ ucfirst(str_replace('_', ' ', $material->material_type)) }}
                    </p>

                    <h3 class="mt-2 text-2xl font-extrabold text-slate-900">
                        {{ $material->title }}
                    </h3>
                </div>

                @if ($material->file_path)
                <a
                    href="{{ asset('storage/' . $material->file_path) }}"
                    target="_blank"
                    class="inline-flex h-11 items-center justify-center rounded-xl border border-[var(--color-brand-blue)] bg-white px-5 text-sm font-bold text-[var(--color-brand-blue)] transition hover:bg-blue-50">
                    Open File
                </a>
                @endif
            </div>

            @if ($material->content)
            <div class="rich-text-content mt-5">
                {!! $material->content !!}
            </div>
            @endif
        </article>
        @empty
        <div class="rounded-[22px] border border-dashed border-slate-300 bg-white px-6 py-12 text-center shadow-sm">
            <h3 class="text-2xl font-extrabold text-slate-900">
                No Materials Yet
            </h3>

            <p class="mx-auto mt-3 max-w-md text-sm leading-6 text-slate-500">
                Materials for this module will be available soon.
            </p>
        </div>
        @endforelse
    </div>

    @if ($practices->count() > 0)
    <div class="reveal reveal-delay-3 rounded-[24px] border border-blue-100 bg-blue-50 p-6">
        <h2 class="text-2xl font-extrabold text-slate-900">
            Practice Available
        </h2>

        <p class="mt-2 text-sm leading-6 text-slate-600">
            This module has {{ $practices->count() }} practice section(s). Practice submission will be enabled in the next phase.
        </p>
    </div>
    @endif
</section>
@endsection