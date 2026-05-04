@props([
'courseLevel' => null,
'courseProgram' => null,
'action',
'method' => 'POST',
'submitLabel' => 'Save Course Level',
'nextSortOrder' => null,
])

@php
$isEdit = filled($courseLevel);
$currentAccessType = old('access_type', $courseLevel?->access_type ?? 'lifetime');
$currentThumbnailType = old('thumbnail_type', $courseLevel?->thumbnail_type ?? 'image');
@endphp

<x-admin.table-card>
    <form
        x-data="{
            name: @js(old('name', $courseLevel?->name ?? '')),
            slug: @js(old('slug', $courseLevel?->slug ?? '')),
            autoSlug: {{ $isEdit ? 'false' : 'true' }},
            accessType: @js($currentAccessType),
            thumbnailType: @js($currentThumbnailType),

            syncSlug() {
                if (this.autoSlug) {
                    this.slug = window.slugify(this.name);
                }
            },

            markSlugManual() {
                this.autoSlug = false;
                this.slug = window.slugify(this.slug);
            }
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
                Basic Information
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Fill the main information for this course level.
            </p>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <x-admin.form.input
                label="Level Name"
                name="name"
                id="name"
                x-model="name"
                @input="syncSlug()"
                placeholder="Example: Basic 1"
                :required="true" />

            <x-admin.form.input
                label="Slug"
                name="slug"
                id="slug"
                x-model="slug"
                @input="markSlugManual()"
                placeholder="Auto-generated from level name" />
        </div>

        <x-admin.form.textarea
            label="Short Description"
            name="short_description"
            id="short_description"
            :value="old('short_description', $courseLevel?->short_description)"
            placeholder="Write a short summary for this level..."
            rows="3" />

        <x-admin.form.rich-text
            label="Full Description"
            name="description"
            id="description"
            :value="old('description', $courseLevel?->description)"
            hint="You can add headings, bullet lists, links, images, and tables."
            :height="560" />

        <div class="border-t border-slate-200 pt-8">
            <h2 class="text-xl font-bold text-slate-900">
                Media
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Upload an image or video thumbnail for this course level.
            </p>
        </div>

        <x-admin.form.select
            label="Thumbnail Type"
            name="thumbnail_type"
            id="thumbnail_type"
            x-model="thumbnailType"
            :options="[
                'image' => 'Image',
                'video' => 'Video',
            ]"
            :required="true" />

        @if ($isEdit && $courseLevel?->thumbnail_file)
        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
            <p class="text-sm font-bold text-slate-700">
                Current Thumbnail
            </p>

            <div class="mt-3">
                @if ($courseLevel->thumbnail_type === 'image')
                <img
                    src="{{ asset('storage/' . $courseLevel->thumbnail_file) }}"
                    alt="{{ $courseLevel->name }}"
                    class="h-40 w-64 rounded-xl object-cover">
                @else
                <video
                    src="{{ asset('storage/' . $courseLevel->thumbnail_file) }}"
                    controls
                    class="h-40 w-64 rounded-xl bg-slate-900 object-cover">
                </video>
                @endif
            </div>
        </div>
        @endif

        <x-admin.form.file
            label="{{ $isEdit ? 'Replace Thumbnail File' : 'Thumbnail File' }}"
            name="thumbnail_file"
            id="thumbnail_file"
            accept="image/*,video/*"
            hint="Image max 4MB. Video max 20MB. Leave empty on edit if you do not want to replace it." />

        <div class="border-t border-slate-200 pt-8">
            <h2 class="text-xl font-bold text-slate-900">
                Pricing & Access
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Set course level price and access duration.
            </p>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <x-admin.form.input
                label="Price"
                name="price"
                id="price"
                type="number"
                min="0"
                step="1000"
                :value="old('price', $courseLevel?->price ?? 0)"
                placeholder="Example: 500000"
                :required="true" />

            <x-admin.form.select
                label="Access Type"
                name="access_type"
                id="access_type"
                x-model="accessType"
                :options="[
                    'lifetime' => 'Lifetime',
                    'limited' => 'Limited',
                ]"
                :required="true" />
        </div>

        <div x-show="accessType === 'limited'">
            <x-admin.form.input
                label="Access Duration Days"
                name="access_duration_days"
                id="access_duration_days"
                type="number"
                min="1"
                :value="old('access_duration_days', $courseLevel?->access_duration_days)"
                placeholder="Example: 90" />
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
                :value="old('sort_order', $courseLevel?->sort_order ?? $nextSortOrder ?? 0)" />

            <div class="flex items-end">
                <x-admin.form.checkbox
                    label="Active"
                    name="is_active"
                    id="is_active"
                    :checked="old('is_active', $courseLevel?->is_active ?? true)" />
            </div>
        </div>

        <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:justify-end">
            <a
                href="{{ route('admin.course-management.programs.levels.index', $courseProgram ?? $courseLevel->courseProgram) }}"
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