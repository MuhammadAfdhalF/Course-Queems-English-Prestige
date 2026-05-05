@props([
'module' => null,
'material' => null,
'action',
'method' => 'POST',
'submitLabel' => 'Save Material',
'nextSortOrder' => null,
])

@php
$isEdit = filled($material);
$currentType = old('material_type', $material?->material_type ?? 'text');
$backModule = $module ?? $material->module;
@endphp

<x-admin.table-card>
    <form
        x-data="{
            materialType: @js($currentType)
        }"
        action="{{ $action }}"
        method="POST"
        enctype="multipart/form-data"
        class="space-y-8 p-6">
        @csrf

        @if ($method !== 'POST')
        @method($method)
        @endif

        <div>
            <h2 class="text-xl font-bold text-slate-900">
                Material Information
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Create a flexible content block for this module.
            </p>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <x-admin.form.input
                label="Title"
                name="title"
                id="title"
                :value="old('title', $material?->title)"
                placeholder="Example: Introduction Video"
                :required="true" />

            <x-admin.form.select
                label="Material Type"
                name="material_type"
                id="material_type"
                x-model="materialType"
                :options="[
                    'text' => 'Text',
                    'image' => 'Image',
                    'video' => 'Video',
                    'audio' => 'Audio / Voice Note',
                    'pdf' => 'PDF',
                    'file' => 'File',
                ]"
                :required="true" />
        </div>

        <div x-show="materialType === 'text'">
            <x-admin.form.rich-text
                label="Content"
                name="content"
                id="content"
                :value="old('content', $material?->content)"
                hint="You can add headings, lists, links, images, tables, and formatted learning content."
                :height="620" />
        </div>

        <div x-show="materialType !== 'text'">
            @if ($isEdit && $material?->file_path)
            <div class="mb-4 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                <p class="text-sm font-bold text-slate-700">
                    Current File
                </p>

                <a
                    href="{{ asset('storage/' . $material->file_path) }}"
                    target="_blank"
                    class="mt-2 inline-flex text-sm font-semibold text-blue-700 hover:underline">
                    Open current file
                </a>

                @if ($material->material_type === 'image')
                <div class="mt-4">
                    <img
                        src="{{ asset('storage/' . $material->file_path) }}"
                        alt="{{ $material->title }}"
                        class="h-40 w-64 rounded-xl object-cover">
                </div>
                @elseif ($material->material_type === 'video')
                <div class="mt-4">
                    <video
                        src="{{ asset('storage/' . $material->file_path) }}"
                        controls
                        class="h-40 w-64 rounded-xl bg-slate-900 object-cover">
                    </video>
                </div>
                @elseif ($material->material_type === 'audio')
                <div class="mt-4">
                    <audio
                        src="{{ asset('storage/' . $material->file_path) }}"
                        controls
                        class="w-full">
                    </audio>
                </div>
                @endif
            </div>
            @endif

            <x-admin.form.file
                label="{{ $isEdit ? 'Replace File' : 'Upload File' }}"
                name="file_path"
                id="file_path"
                accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.ppt,.pptx,.zip,.rar"
                hint="Required for non-text material. Leave empty on edit if you do not want to replace the current file." />
        </div>

        <div class="border-t border-slate-200 pt-8">
            <h2 class="text-xl font-bold text-slate-900">
                Settings
            </h2>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <x-admin.form.input
                label="Sort Order"
                name="sort_order"
                id="sort_order"
                type="number"
                min="0"
                :value="old('sort_order', $material?->sort_order ?? $nextSortOrder ?? 0)" />

            <div class="flex items-end">
                <x-admin.form.checkbox
                    label="Active"
                    name="is_active"
                    id="is_active"
                    :checked="old('is_active', $material?->is_active ?? true)" />
            </div>
        </div>

        <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:justify-end">
            <a
                href="{{ route('admin.course-management.modules.materials.index', $backModule) }}"
                class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                Cancel
            </a>

            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
                {{ $submitLabel }}
            </button>
        </div>
    </form>
</x-admin.table-card>