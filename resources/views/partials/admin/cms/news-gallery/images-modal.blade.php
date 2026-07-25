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
                class="space-y-4 rounded-2xl border border-slate-200 bg-white p-4">
                @csrf

                <div class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-1.5">
                        <label for="post_images" class="block text-sm font-semibold text-slate-700">
                            Upload Images
                        </label>

                        <input
                            type="file"
                            name="images[]"
                            id="post_images"
                            multiple
                            accept="image/jpeg,image/png,image/jpg,image/webp"
                            class="block w-full text-sm text-slate-500 file:mr-4 file:rounded-xl file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-[var(--color-brand-blue)] hover:file:bg-blue-100 focus:outline-none" />

                        <p class="text-xs text-slate-400">
                            Maximum 10 images, 2 MB each (Formats: JPG, PNG, WEBP).
                        </p>
                    </div>

                    <x-admin.form.input
                        label="Caption (Optional)"
                        name="caption"
                        id="post_image_caption"
                        placeholder="Optional caption for uploaded images" />
                </div>
            </form>

            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-bold text-slate-700">
                            Uploaded Additional Images
                        </h4>
                        <p class="text-xs text-slate-400">
                            Drag handle or use arrows to reorder. Click Save Order when done.
                        </p>
                    </div>

                    <div class="flex items-center gap-2">
                        <span
                            class="text-xs font-semibold text-slate-400"
                            x-text="`${imagesList.length} images`">
                        </span>

                        <template x-if="isOrderChanged">
                            <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-amber-700">
                                Unsaved Order
                            </span>
                        </template>
                    </div>
                </div>

                <template x-if="imagesList.length > 0">
                    <div class="grid gap-3 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4">
                        <template x-for="(image, index) in imagesList" :key="image.id">
                            <div
                                draggable="true"
                                @dragstart="onDragStart(index, $event)"
                                @dragover.prevent
                                @drop="onDrop(index)"
                                class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-2 shadow-sm transition duration-200 hover:border-blue-300">

                                <div class="mb-2 flex items-center justify-between gap-1">
                                    <div class="flex cursor-grab items-center gap-1.5 text-slate-400 hover:text-slate-600 active:cursor-grabbing" title="Drag to reorder">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                                        </svg>
                                        <span class="inline-flex items-center rounded-md bg-slate-100 px-1.5 py-0.5 text-[10px] font-extrabold text-slate-600" x-text="`#${index + 1}`"></span>
                                    </div>

                                    <div class="flex items-center gap-1">
                                        <button
                                            type="button"
                                            @click="moveImage(index, index - 1)"
                                            :disabled="index === 0"
                                            class="inline-flex h-6 w-6 items-center justify-center rounded-md bg-slate-100 text-slate-600 transition hover:bg-slate-200 disabled:opacity-30"
                                            title="Move Left/Up">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                            </svg>
                                        </button>

                                        <button
                                            type="button"
                                            @click="moveImage(index, index + 1)"
                                            :disabled="index === imagesList.length - 1"
                                            class="inline-flex h-6 w-6 items-center justify-center rounded-md bg-slate-100 text-slate-600 transition hover:bg-slate-200 disabled:opacity-30"
                                            title="Move Right/Down">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </button>

                                        <form
                                            :action="image.delete_url"
                                            method="POST"
                                            onsubmit="return confirm('Delete this image?')"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="inline-flex h-6 w-6 items-center justify-center rounded-md bg-rose-50 text-rose-600 transition hover:bg-rose-100"
                                                title="Delete Image">
                                                <x-admin.icon name="trash" class="h-3.5 w-3.5" />
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <button
                                    type="button"
                                    @click="openImagePreview({
                                        title: image.caption || selectedPostImages.title,
                                        url: image.image_url
                                    })"
                                    class="group relative block h-28 w-full overflow-hidden rounded-xl bg-slate-100">
                                    <img
                                        :src="image.image_url"
                                        :alt="image.caption || selectedPostImages.title"
                                        class="h-full w-full object-cover transition duration-200 group-hover:scale-105">

                                    <span class="absolute inset-0 flex items-center justify-center bg-slate-900/0 text-white transition group-hover:bg-slate-900/40">
                                        <x-admin.icon name="eye" class="h-5 w-5 opacity-0 transition group-hover:opacity-100" />
                                    </span>
                                </button>

                                <div class="mt-1.5 px-0.5">
                                    <p class="truncate text-xs font-semibold text-slate-700" :title="image.caption || 'No caption'" x-text="image.caption || 'No caption'"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>

                <template x-if="imagesList.length === 0">
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

        <template x-if="imagesList.length > 1">
            <button
                type="button"
                @click="saveOrder()"
                :class="isOrderChanged ? 'bg-amber-600 hover:bg-amber-700 text-white' : 'bg-slate-800 hover:bg-slate-900 text-white'"
                class="inline-flex items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-bold shadow-sm transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
                <span>Save Order</span>
            </button>
        </template>

        <button
            type="submit"
            form="addPostImageForm"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
            <x-admin.icon name="plus" class="h-4 w-4" />
            <span>Upload Images</span>
        </button>
    </x-slot:footer>
</x-admin.modal>