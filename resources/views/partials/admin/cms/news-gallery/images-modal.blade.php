<x-admin.modal
    model="imageModalOpen"
    title="Manage Post Images"
    subtitle="Upload and manage additional images for this post."
    size="xl">
    <template x-if="selectedPostImages">
        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                    Selected Post
                </p>

                <h3
                    class="mt-1 text-xl font-bold text-slate-900"
                    x-text="selectedPostImages.title">
                </h3>
            </div>

            <form
                id="addPostImageForm"
                :action="selectedPostImages.image_store_url"
                method="POST"
                enctype="multipart/form-data"
                class="grid gap-4 rounded-2xl border border-slate-200 bg-white p-4 md:grid-cols-[1fr_1fr_140px]">
                @csrf

                <x-admin.form.file
                    label="Image"
                    name="image"
                    id="post_image"
                    accept="image/*"
                    hint="Max 2MB." />

                <x-admin.form.input
                    label="Caption"
                    name="caption"
                    id="post_image_caption"
                    placeholder="Optional caption" />

                <x-admin.form.input
                    label="Order"
                    name="sort_order"
                    id="post_image_sort_order"
                    type="number"
                    min="0"
                    x-model="imageSortOrder" />
            </form>

            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-bold text-slate-700">
                        Uploaded Images
                    </h4>

                    <span
                        class="text-sm font-semibold text-slate-400"
                        x-text="`${selectedPostImages.images.length} images`">
                    </span>
                </div>

                <template x-if="selectedPostImages.images.length > 0">
                    <div class="grid gap-4 md:grid-cols-3">
                        <template x-for="image in selectedPostImages.images" :key="image.id">
                            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                                <button
                                    type="button"
                                    @click="openImagePreview({
                                        title: image.caption || selectedPostImages.title,
                                        url: image.image_url
                                    })"
                                    class="group relative block h-40 w-full overflow-hidden">
                                    <img
                                        :src="image.image_url"
                                        :alt="image.caption || selectedPostImages.title"
                                        class="h-full w-full object-cover transition duration-200 group-hover:scale-105">

                                    <span class="absolute inset-0 flex items-center justify-center bg-slate-900/0 text-white transition group-hover:bg-slate-900/40">
                                        <x-admin.icon name="eye" class="h-6 w-6 opacity-0 transition group-hover:opacity-100" />
                                    </span>
                                </button>

                                <div class="space-y-3 p-4">
                                    <div>
                                        <p class="line-clamp-2 text-sm font-semibold text-slate-700" x-text="image.caption || 'No caption'"></p>
                                        <p class="mt-1 text-xs font-medium text-slate-400">
                                            Order: <span x-text="image.sort_order"></span>
                                        </p>
                                    </div>

                                    <form
                                        :action="image.delete_url"
                                        method="POST"
                                        onsubmit="return confirm('Delete this image?')">
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-rose-50 px-4 py-2 text-sm font-bold text-rose-600 transition hover:bg-rose-100">
                                            <x-admin.icon name="trash" class="h-4 w-4" />
                                            <span>Delete Image</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>

                <template x-if="selectedPostImages.images.length === 0">
                    <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-6 py-10 text-center">
                        <p class="text-sm font-semibold text-slate-500">
                            No additional images yet.
                        </p>
                    </div>
                </template>
            </div>
        </div>
    </template>

    <x-slot:footer>
        <button
            type="button"
            @click="imageModalOpen = false"
            class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
            Close
        </button>

        <button
            type="submit"
            form="addPostImageForm"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
            <x-admin.icon name="plus" class="h-4 w-4" />
            <span>Upload Image</span>
        </button>
    </x-slot:footer>
</x-admin.modal>