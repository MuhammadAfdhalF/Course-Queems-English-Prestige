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

<x-admin.table-card class="overflow-hidden">
    <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex h-8 min-w-8 items-center justify-center rounded-xl bg-white px-3 text-xs font-bold text-slate-500 ring-1 ring-slate-200">
                        #{{ $loopIndex }}
                    </span>

                    <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">
                        {{ $typeLabel }}
                    </span>

                    @if ($material->is_active)
                    <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">
                        Active
                    </span>
                    @else
                    <span class="inline-flex rounded-full bg-slate-200 px-3 py-1 text-xs font-bold text-slate-500">
                        Inactive
                    </span>
                    @endif
                </div>

                <h3 class="mt-3 text-lg font-bold text-slate-900">
                    {{ $material->title }}
                </h3>
            </div>

            <div class="text-sm font-semibold text-slate-400">
                Order {{ $material->sort_order }}
            </div>
        </div>
    </div>

    <div class="p-6">
        @if ($material->material_type === 'text')
        @if ($material->content)
        <div class="rich-text-content">
            {!! $material->content !!}
        </div>
        @else
        <p class="text-sm text-slate-500">
            No text content.
        </p>
        @endif

        @elseif ($material->material_type === 'image')
        @if ($fileUrl)
        <img
            src="{{ $fileUrl }}"
            alt="{{ $material->title }}"
            class="max-h-[520px] w-full rounded-2xl object-contain">
        @else
        <p class="text-sm text-slate-500">
            No image file uploaded.
        </p>
        @endif

        @elseif ($material->material_type === 'video')
        @if ($fileUrl)
        <video
            src="{{ $fileUrl }}"
            controls
            class="w-full rounded-2xl bg-slate-900">
            Your browser does not support the video tag.
        </video>
        @else
        <p class="text-sm text-slate-500">
            No video file uploaded.
        </p>
        @endif

        @elseif ($material->material_type === 'audio')
        @if ($fileUrl)
        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
            <p class="mb-3 text-sm font-bold text-slate-700">
                Audio Player
            </p>

            <audio
                src="{{ $fileUrl }}"
                controls
                class="w-full">
                Your browser does not support the audio tag.
            </audio>
        </div>
        @else
        <p class="text-sm text-slate-500">
            No audio file uploaded.
        </p>
        @endif

        @elseif ($material->material_type === 'pdf')
        @if ($fileUrl)
        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
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
                    class="inline-flex items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
                    Open PDF
                </a>
            </div>
        </div>
        @else
        <p class="text-sm text-slate-500">
            No PDF file uploaded.
        </p>
        @endif

        @elseif ($material->material_type === 'file')
        @if ($fileUrl)
        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
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
                    class="inline-flex items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
                    Open File
                </a>
            </div>
        </div>
        @else
        <p class="text-sm text-slate-500">
            No file uploaded.
        </p>
        @endif
        @endif
    </div>
</x-admin.table-card>