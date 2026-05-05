@php
$fileUrl = $material->file_path ? asset('storage/' . $material->file_path) : null;

$typeLabels = [
'text' => 'Text',
'image' => 'Image',
'video' => 'Video',
'audio' => 'Audio / Voice Note',
'pdf' => 'PDF',
'file' => 'File',
];

$typeLabel = $typeLabels[$material->material_type] ?? ucfirst($material->material_type);
@endphp

<section class="module-preview-material">
    <div class="mb-3 flex flex-wrap items-center gap-2">
        <span class="inline-flex h-7 min-w-7 items-center justify-center rounded-lg bg-slate-100 px-2 text-xs font-bold text-slate-500">
            #{{ $loopIndex }}
        </span>

        <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">
            {{ $typeLabel }}
        </span>

        @if (! $material->is_active)
        <span class="inline-flex rounded-full bg-slate-200 px-3 py-1 text-xs font-bold text-slate-500">
            Inactive
        </span>
        @endif
    </div>

    @if ($material->title)
    <h2 class="mb-4 text-xl font-extrabold text-slate-950">
        {{ $material->title }}
    </h2>
    @endif

    @if ($material->material_type === 'text')
    @if ($material->content)
    <div class="rich-text-content">
        {!! $material->content !!}
    </div>
    @else
    <p class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-500">
        No text content.
    </p>
    @endif

    @elseif ($material->material_type === 'image')
    @if ($fileUrl)
    <figure>
        <img
            src="{{ $fileUrl }}"
            alt="{{ $material->title }}"
            class="max-h-[640px] w-full rounded-2xl object-contain">
    </figure>
    @else
    <p class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-500">
        No image file uploaded.
    </p>
    @endif

    @elseif ($material->material_type === 'video')
    @if ($fileUrl)
    <div class="overflow-hidden rounded-2xl bg-slate-950">
        <video
            src="{{ $fileUrl }}"
            controls
            class="w-full">
            Your browser does not support the video tag.
        </video>
    </div>
    @else
    <p class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-500">
        No video file uploaded.
    </p>
    @endif

    @elseif ($material->material_type === 'audio')
    @if ($fileUrl)
    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
        <audio
            src="{{ $fileUrl }}"
            controls
            class="w-full">
            Your browser does not support the audio tag.
        </audio>
    </div>
    @else
    <p class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-500">
        No audio file uploaded.
    </p>
    @endif

    @elseif ($material->material_type === 'pdf')
    @if ($fileUrl)
    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div class="min-w-0">
                <p class="text-sm font-bold text-slate-900">
                    PDF Material
                </p>

                <p class="mt-1 text-sm text-slate-500">
                    Open this PDF in a new tab to preview or download it.
                </p>
            </div>

            <a
                href="{{ $fileUrl }}"
                target="_blank"
                class="inline-flex shrink-0 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
                Open PDF
            </a>
        </div>
    </div>
    @else
    <p class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-500">
        No PDF file uploaded.
    </p>
    @endif

    @elseif ($material->material_type === 'file')
    @if ($fileUrl)
    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div class="min-w-0">
                <p class="text-sm font-bold text-slate-900">
                    Downloadable File
                </p>

                <p class="mt-1 text-sm text-slate-500">
                    Open or download the attached material file.
                </p>
            </div>

            <a
                href="{{ $fileUrl }}"
                target="_blank"
                class="inline-flex shrink-0 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
                Open File
            </a>
        </div>
    </div>
    @else
    <p class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-500">
        No file uploaded.
    </p>
    @endif
    @endif
</section>