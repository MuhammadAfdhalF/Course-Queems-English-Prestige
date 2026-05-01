<x-admin.modal
    model="createModalOpen"
    title="Add Hero Section"
    subtitle="Tambah banner utama untuk halaman beranda."
    size="lg">
    <form
        id="createHeroSectionForm"
        action="{{ route('admin.cms.hero-sections.store') }}"
        method="POST"
        enctype="multipart/form-data"
        class="space-y-6">
        @csrf

        <input type="hidden" name="_form_type" value="create">

        <div>
            <label for="create_title" class="mb-2 block text-sm font-bold text-slate-700">
                Title
            </label>
            <input
                id="create_title"
                name="title"
                type="text"
                value="{{ old('_form_type') === 'create' ? old('title') : '' }}"
                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[var(--color-brand-blue)] focus:ring-4 focus:ring-blue-100"
                placeholder="Example: Master English with Confidence">

            @if (old('_form_type') === 'create')
            @error('title')
            <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
            @enderror
            @endif
        </div>

        <div>
            <label for="create_subtitle" class="mb-2 block text-sm font-bold text-slate-700">
                Subtitle
            </label>
            <input
                id="create_subtitle"
                name="subtitle"
                type="text"
                value="{{ old('_form_type') === 'create' ? old('subtitle') : '' }}"
                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[var(--color-brand-blue)] focus:ring-4 focus:ring-blue-100"
                placeholder="Short headline for hero section">

            @if (old('_form_type') === 'create')
            @error('subtitle')
            <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
            @enderror
            @endif
        </div>

        <div>
            <label for="create_image" class="mb-2 block text-sm font-bold text-slate-700">
                Image
            </label>
            <input
                id="create_image"
                name="image"
                type="file"
                accept="image/*"
                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition file:mr-4 file:rounded-lg file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:text-sm file:font-bold file:text-slate-700 hover:file:bg-slate-200">

            @if (old('_form_type') === 'create')
            @error('image')
            <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
            @enderror
            @endif
        </div>

        <div>
            <label for="create_sort_order" class="mb-2 block text-sm font-bold text-slate-700">
                Sort Order
            </label>
            <input
                id="create_sort_order"
                name="sort_order"
                type="number"
                min="0"
                value="{{ old('_form_type') === 'create' ? old('sort_order', $nextSortOrder) : $nextSortOrder }}"
                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[var(--color-brand-blue)] focus:ring-4 focus:ring-blue-100">
            @if (old('_form_type') === 'create')
            @error('sort_order')
            <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
            @enderror
            @endif
        </div>

        <label class="flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
            <input type="hidden" name="is_active" value="0">

            <input
                type="checkbox"
                name="is_active"
                value="1"
                @checked(old('_form_type')==='create' ? old('is_active', true) : true)
                class="h-5 w-5 rounded border-slate-300 text-[var(--color-brand-blue)] focus:ring-[var(--color-brand-blue)]">

            <span class="text-sm font-semibold text-slate-700">
                Active
            </span>
        </label>
    </form>

    <x-slot:footer>
        <button
            type="button"
            @click="createModalOpen = false"
            class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
            Cancel
        </button>

        <button
            type="submit"
            form="createHeroSectionForm"
            class="inline-flex items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
            Save Hero Section
        </button>
    </x-slot:footer>
</x-admin.modal>