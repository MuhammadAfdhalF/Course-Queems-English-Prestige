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
$currentLearningMode = old('learning_mode', $courseLevel?->learning_mode ?? 'online');
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
                Upload an image or video with poster image for this course level.
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

        <!-- IMAGE THUMBNAIL INPUT -->
        <div x-show="thumbnailType === 'image'" class="space-y-6">
            @if ($isEdit && $courseLevel?->thumbnail_type === 'image' && $courseLevel?->thumbnail_file)
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                <p class="text-sm font-bold text-slate-700">
                    Current Image
                </p>

                <div class="mt-3">
                    <img
                        src="{{ asset('storage/' . $courseLevel->thumbnail_file) }}"
                        alt="{{ $courseLevel->name }}"
                        class="h-40 w-64 rounded-xl object-cover">
                </div>
            </div>
            @endif

            <x-admin.form.file
                label="{{ ($isEdit && $courseLevel?->thumbnail_type === 'image' && $courseLevel?->thumbnail_file) ? 'Replace Course Image' : 'Course Image' }}"
                name="thumbnail_file"
                id="thumbnail_file_image"
                accept="image/jpeg,image/png,image/webp"
                hint="Image max 4MB (JPG, PNG, WEBP). Recommended aspect ratio 16:9." />
        </div>

        <!-- VIDEO THUMBNAIL & POSTER INPUTS -->
        <div x-show="thumbnailType === 'video'" class="space-y-6">
            @if ($isEdit && $courseLevel?->thumbnail_type === 'video' && ($courseLevel?->thumbnail_file || $courseLevel?->video_poster_file))
            <div class="grid gap-4 sm:grid-cols-2">
                @if ($courseLevel->thumbnail_file)
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-sm font-bold text-slate-700">
                        Current Video File
                    </p>

                    <div class="mt-3">
                        <video
                            src="{{ asset('storage/' . $courseLevel->thumbnail_file) }}"
                            poster="{{ $courseLevel->video_poster_file ? asset('storage/' . $courseLevel->video_poster_file) : '' }}"
                            controls
                            class="h-40 w-full rounded-xl bg-slate-900 object-cover">
                        </video>
                    </div>
                </div>
                @endif

                @if ($courseLevel->video_poster_file)
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-sm font-bold text-slate-700">
                        Current Video Poster
                    </p>

                    <div class="mt-3">
                        <img
                            src="{{ asset('storage/' . $courseLevel->video_poster_file) }}"
                            alt="Video Poster"
                            class="h-40 w-full rounded-xl object-cover">
                    </div>
                </div>
                @endif
            </div>
            @endif

            <x-admin.form.file
                label="{{ ($isEdit && $courseLevel?->thumbnail_type === 'video' && $courseLevel?->thumbnail_file) ? 'Replace Course Video' : 'Course Video File' }}"
                name="thumbnail_file"
                id="thumbnail_file_video"
                accept="video/mp4,video/webm,video/quicktime"
                hint="Video file max 20MB (MP4, WEBM, MOV). Displayed on course detail page." />

            <x-admin.form.file
                label="{{ ($isEdit && $courseLevel?->video_poster_file) ? 'Replace Video Poster Image' : 'Video Poster Image' }}"
                name="video_poster_file"
                id="video_poster_file"
                accept="image/jpeg,image/png,image/webp"
                hint="Poster image max 4MB. Used on Homepage, Course Catalog, and Student My Courses cards. Recommended aspect ratio 16:9." />
        </div>

        <div class="border-t border-slate-200 pt-8">
            <h2 class="text-xl font-bold text-slate-900">
                Pricing & Access
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Set course level price, learning mode, and access duration.
            </p>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <x-admin.form.input
                label="Price (Rp)"
                name="price"
                id="price"
                type="text"
                inputmode="numeric"
                :value="old('price') ? old('price') : number_format((float) ($courseLevel?->price ?? 0), 0, ',', '.')"
                placeholder="Example: 500.000"
                @input="$el.value = window.formatRupiah($el.value)"
                :required="true" />

            <x-admin.form.select
                label="Learning Mode"
                name="learning_mode"
                id="learning_mode"
                :value="$currentLearningMode"
                :options="[
                    'online' => 'Online',
                    'offline' => 'Offline',
                    'hybrid' => 'Hybrid',
                ]"
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