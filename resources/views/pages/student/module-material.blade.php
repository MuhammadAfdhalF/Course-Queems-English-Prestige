@extends('layouts.learning')

@section('content')
<section class="mx-auto max-w-5xl space-y-6">
    <div class="reveal">
        <a href="{{ route('student.learning-path', $enrollment) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-[var(--color-brand-blue)]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Learning Path
        </a>
    </div>

    <article class="reveal reveal-delay-1 overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-sm">
        {{-- MODULE HEADER --}}
        <div class="border-b border-slate-200 px-6 py-7 lg:px-9 lg:py-8">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-[var(--color-brand-gold)]">
                {{ $courseLevel->name }}
            </p>

            <h1 class="mt-3 text-4xl font-extrabold leading-tight text-slate-900 lg:text-5xl">
                {{ $module->title }}
            </h1>

            @if ($module->short_description)
            <p class="mt-4 max-w-3xl text-base leading-8 text-slate-600 lg:text-lg">
                {{ $module->short_description }}
            </p>
            @endif
        </div>


        {{-- LESSON CONTENT --}}
        <div class="px-6 py-7 lg:px-9 lg:py-8">
            @forelse ($materials as $material)
            @php
            $type = strtolower($material->material_type ?? 'text');
            $title = trim($material->title ?? '');

            $isText = in_array($type, ['text', 'rich_text', 'content'], true);
            $isImage = in_array($type, ['image', 'thumbnail', 'photo', 'picture'], true);
            $isVideo = in_array($type, ['video'], true);
            $isAudio = in_array($type, ['audio', 'sound'], true);
            $isFile = ! $isText && ! $isImage && ! $isVideo && ! $isAudio;

            $genericTitles = [
            'image',
            'thumbnail',
            'photo',
            'picture',
            'text',
            'content',
            'rich text',
            'rich_text',
            'audio',
            'sound',
            'video',
            'file',
            'document',
            'pdf',
            ];

            $shouldShowTitle = $title && ! in_array(strtolower($title), $genericTitles, true);
            @endphp

            <section class="{{ $loop->first ? '' : 'mt-10 border-t border-slate-200 pt-10' }}">
                @if ($shouldShowTitle)
                <h2 class="mb-4 text-2xl font-extrabold leading-tight text-slate-900">
                    {{ $title }}
                </h2>
                @endif

                @if ($isImage && $material->file_path)
                <img
                    src="{{ asset('storage/' . $material->file_path) }}"
                    alt="{{ $title ?: $module->title }}"
                    class="w-full rounded-2xl border border-slate-200 object-cover shadow-sm">

                @if ($material->content)
                <div class="rich-text-content mt-5">
                    {!! $material->content !!}
                </div>
                @endif

                @elseif ($isVideo && $material->file_path)
                <video
                    src="{{ asset('storage/' . $material->file_path) }}"
                    controls
                    class="w-full rounded-2xl border border-slate-200 bg-slate-900 shadow-sm">
                </video>

                @if ($material->content)
                <div class="rich-text-content mt-5">
                    {!! $material->content !!}
                </div>
                @endif

                @elseif ($isAudio && $material->file_path)
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                    <audio
                        src="{{ asset('storage/' . $material->file_path) }}"
                        controls
                        class="w-full">
                    </audio>
                </div>

                @if ($material->content)
                <div class="rich-text-content mt-5">
                    {!! $material->content !!}
                </div>
                @endif

                @elseif ($isText)
                <div class="rich-text-content">
                    {!! $material->content ?: '<p>Material content will be available soon.</p>' !!}
                </div>

                @elseif ($material->file_path)
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                    <p class="text-sm font-semibold leading-6 text-slate-600">
                        This material is available as a downloadable or viewable file.
                    </p>

                    <a
                        href="{{ asset('storage/' . $material->file_path) }}"
                        target="_blank"
                        class="motion-button mt-4 inline-flex h-11 items-center justify-center rounded-xl border border-[var(--color-brand-blue)] bg-white px-5 text-sm font-bold text-[var(--color-brand-blue)] transition hover:bg-blue-50">
                        Open File
                    </a>
                </div>

                @if ($material->content)
                <div class="rich-text-content mt-5">
                    {!! $material->content !!}
                </div>
                @endif

                @else
                <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-5 py-6 text-sm font-medium text-slate-500">
                    This material does not have content yet.
                </div>
                @endif
            </section>
            @empty
            <div class="rounded-[22px] border border-dashed border-slate-300 bg-slate-50 px-6 py-12 text-center">
                <h3 class="text-2xl font-extrabold text-slate-900">
                    No Materials Yet
                </h3>

                <p class="mx-auto mt-3 max-w-md text-sm leading-6 text-slate-500">
                    Materials for this module will be available soon.
                </p>
            </div>
            @endforelse
        </div>

        {{-- PRACTICE INFO --}}
        @if ($practices->count() > 0)
        @php
        $practice = $practices->first();
        @endphp

        <div class="border-t border-blue-100 bg-blue-50 px-6 py-6 lg:px-9">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-900">
                        Practice Available
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Complete this practice to evaluate your understanding of the module material.
                    </p>
                </div>

                <a
                    href="{{ route('student.module-practice', ['enrollment' => $enrollment, 'module' => $module, 'practice' => $practice]) }}"
                    class="inline-flex h-11 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 text-sm font-bold text-white shadow-sm transition hover:opacity-95">
                    Start Practice
                </a>
            </div>
        </div>
        @endif
    </article>
</section>
@endsection