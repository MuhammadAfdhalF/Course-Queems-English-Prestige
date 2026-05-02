<x-admin.table-card>
    <form
        action="{{ route('admin.cms.about-us.save') }}"
        method="POST"
        enctype="multipart/form-data"
        class="space-y-6 p-6">
        @csrf

        <x-admin.form.input
            label="Title"
            name="title"
            id="title"
            :value="old('title', $aboutUs?->title)"
            placeholder="Example: About Queens English Prestige"
            :required="true" />

        <x-admin.form.textarea
            label="Description"
            name="description"
            id="description"
            :value="old('description', $aboutUs?->description)"
            placeholder="Write a short profile of the institution here..."
            rows="8" />

        @if ($aboutUs?->image)
        <div>
            <label class="mb-2 block text-sm font-bold text-slate-700">
                Current Image
            </label>

            <button
                type="button"
                @click="openImagePreview({
                        title: '{{ $aboutUs->title }}',
                        url: '{{ asset('storage/' . $aboutUs->image) }}'
                    })"
                class="group relative overflow-hidden rounded-xl">
                <img
                    src="{{ asset('storage/' . $aboutUs->image) }}"
                    alt="{{ $aboutUs->title }}"
                    class="h-40 w-72 rounded-xl object-cover transition duration-200 group-hover:scale-105">

                <span class="absolute inset-0 flex items-center justify-center bg-slate-900/0 text-white transition group-hover:bg-slate-900/40">
                    <x-admin.icon name="eye" class="h-6 w-6 opacity-0 transition group-hover:opacity-100" />
                </span>
            </button>
        </div>
        @endif

        <x-admin.form.file
            label="{{ $aboutUs?->image ? 'Replace Image' : 'Image' }}"
            name="image"
            id="image"
            accept="image/*"
            hint="Recommended image size: 1200x800px. Max 2MB." />

        <x-admin.form.checkbox
            label="Active"
            name="is_active"
            id="is_active"
            :checked="old('is_active', $aboutUs?->is_active ?? true)" />

        <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:justify-end">
            <a
                href="{{ route('admin.cms.about.index') }}"
                class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                Cancel
            </a>

            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
                Save Changes
            </button>
        </div>
    </form>
</x-admin.table-card>