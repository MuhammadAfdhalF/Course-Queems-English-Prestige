@props([
'model' => 'imagePreviewModalOpen',
'title' => 'Image Preview',
'subtitle' => 'Preview gambar.',
])

<x-admin.modal
    :model="$model"
    :title="$title"
    :subtitle="$subtitle"
    size="xl">
    <div class="space-y-4">
        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
            <p class="text-sm font-semibold text-slate-500">
                Preview
            </p>

            <h3
                class="mt-1 text-xl font-bold text-slate-900"
                x-text="previewImage.title">
            </h3>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-100">
            <img
                :src="previewImage.url"
                :alt="previewImage.title"
                class="max-h-[70vh] w-full object-contain">
        </div>
    </div>

    <x-slot:footer>
        <button
            type="button"
            @click="{{ $model }} = false"
            class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
            Close
        </button>
    </x-slot:footer>
</x-admin.modal>