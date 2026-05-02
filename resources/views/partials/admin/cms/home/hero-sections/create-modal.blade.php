<x-admin.modal
    model="createModalOpen"
    title="Add Hero Section"
    subtitle="Create a main banner for the home page."
    size="lg">
    <form
        id="createHeroSectionForm"
        action="{{ route('admin.cms.hero-sections.store') }}"
        method="POST"
        enctype="multipart/form-data"
        class="space-y-6">
        @csrf

        <input type="hidden" name="_form_type" value="create">

        <x-admin.form.input
            label="Title"
            name="title"
            id="create_title"
            :value="old('_form_type') === 'create' ? old('title') : ''"
            placeholder="Example: Master English with Confidence"
            :required="true" />

        <x-admin.form.input
            label="Subtitle"
            name="subtitle"
            id="create_subtitle"
            :value="old('_form_type') === 'create' ? old('subtitle') : ''"
            placeholder="Short headline for hero section" />

        <x-admin.form.file
            label="Image"
            name="image"
            id="create_image"
            accept="image/*"
            hint="Recommended size: 1920x800px. Max 2MB." />

        <x-admin.form.input
            label="Sort Order"
            name="sort_order"
            id="create_sort_order"
            type="number"
            min="0"
            :value="old('_form_type') === 'create' ? old('sort_order', $nextSortOrder) : $nextSortOrder" />

        <x-admin.form.checkbox
            label="Active"
            name="is_active"
            id="create_is_active"
            :checked="old('_form_type') === 'create' ? (bool) old('is_active', true) : true" />
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