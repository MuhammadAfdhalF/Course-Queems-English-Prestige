<x-admin.modal
    model="editModalOpen"
    title="Edit Hero Section"
    subtitle="Perbarui banner utama halaman beranda."
    size="lg">
    <template x-if="selectedHero">
        <form
            id="editHeroSectionForm"
            :action="selectedHero.update_url"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')

            <input type="hidden" name="_form_type" value="edit">

            <div>
                <label for="edit_title" class="mb-2 block text-sm font-bold text-slate-700">
                    Title
                </label>
                <input
                    id="edit_title"
                    name="title"
                    type="text"
                    x-model="selectedHero.title"
                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[var(--color-brand-blue)] focus:ring-4 focus:ring-blue-100">

                @if (old('_form_type') === 'edit')
                @error('title')
                <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
                @enderror
                @endif
            </div>

            <div>
                <label for="edit_subtitle" class="mb-2 block text-sm font-bold text-slate-700">
                    Subtitle
                </label>
                <input
                    id="edit_subtitle"
                    name="subtitle"
                    type="text"
                    x-model="selectedHero.subtitle"
                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[var(--color-brand-blue)] focus:ring-4 focus:ring-blue-100">

                @if (old('_form_type') === 'edit')
                @error('subtitle')
                <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
                @enderror
                @endif
            </div>

            <div x-show="selectedHero.image_url">
                <label class="mb-2 block text-sm font-bold text-slate-700">
                    Current Image
                </label>
                <img
                    :src="selectedHero.image_url"
                    :alt="selectedHero.title"
                    class="h-28 w-48 rounded-xl object-cover">
            </div>

            <div>
                <label for="edit_image" class="mb-2 block text-sm font-bold text-slate-700">
                    Replace Image
                </label>
                <input
                    id="edit_image"
                    name="image"
                    type="file"
                    accept="image/*"
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition file:mr-4 file:rounded-lg file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:text-sm file:font-bold file:text-slate-700 hover:file:bg-slate-200">

                @if (old('_form_type') === 'edit')
                @error('image')
                <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
                @enderror
                @endif
            </div>

            <div>
                <label for="edit_sort_order" class="mb-2 block text-sm font-bold text-slate-700">
                    Sort Order
                </label>
                <input
                    id="edit_sort_order"
                    name="sort_order"
                    type="number"
                    min="0"
                    x-model="selectedHero.sort_order"
                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[var(--color-brand-blue)] focus:ring-4 focus:ring-blue-100">

                @if (old('_form_type') === 'edit')
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
                    x-model="selectedHero.is_active"
                    class="h-5 w-5 rounded border-slate-300 text-[var(--color-brand-blue)] focus:ring-[var(--color-brand-blue)]">

                <span class="text-sm font-semibold text-slate-700">
                    Active
                </span>
            </label>
        </form>
    </template>

    <x-slot:footer>
        <button
            type="button"
            @click="editModalOpen = false"
            class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
            Cancel
        </button>

        <button
            type="submit"
            form="editHeroSectionForm"
            class="inline-flex items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
            Save Changes
        </button>
    </x-slot:footer>
</x-admin.modal>