<div
    x-show="drawerOpen"
    x-cloak
    class="relative z-50"
    role="dialog"
    aria-modal="true"
    @keydown.window.escape="closeDrawer()">

    {{-- Backdrop --}}
    <div
        x-show="drawerOpen"
        x-transition:enter="ease-in-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in-out duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="closeDrawer()"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

    <div class="fixed inset-0 overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full sm:pl-10">
                <div
                    x-show="drawerOpen"
                    x-transition:enter="transform transition ease-in-out duration-300"
                    x-transition:enter-start="translate-x-full"
                    x-transition:enter-end="translate-x-0"
                    x-transition:leave="transform transition ease-in-out duration-300"
                    x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="translate-x-full"
                    class="pointer-events-auto w-screen max-w-full sm:max-w-2xl bg-white shadow-2xl flex flex-col">

                    {{-- Drawer Header --}}
                    <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4 bg-slate-50">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400" x-text="drawerParentContext"></span>
                            <h2 class="text-lg font-bold text-slate-900" x-text="drawerTitle"></h2>
                        </div>
                        <button
                            type="button"
                            @click="closeDrawer()"
                            class="rounded-xl p-1.5 text-slate-400 hover:bg-slate-200 hover:text-slate-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Drawer Body --}}
                    <div class="flex-1 overflow-y-auto p-6 space-y-6">
                        {{-- Loading Skeleton --}}
                        <div x-show="drawerLoading" class="space-y-4 py-8">
                            <div class="h-4 bg-slate-200 rounded animate-pulse w-1/3"></div>
                            <div class="h-10 bg-slate-100 rounded-xl animate-pulse"></div>
                            <div class="h-4 bg-slate-200 rounded animate-pulse w-1/4"></div>
                            <div class="h-24 bg-slate-100 rounded-xl animate-pulse"></div>
                        </div>

                        {{-- General Error Alert --}}
                        <template x-if="drawerGeneralError">
                            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-xs font-semibold text-rose-700">
                                <span x-text="drawerGeneralError"></span>
                            </div>
                        </template>

                        {{-- Form Body --}}
                        <form
                            id="builderDrawerForm"
                            x-show="!drawerLoading"
                            @submit.prevent="submitDrawerForm()"
                            class="space-y-6">

                            {{-- LEVEL FORM FIELDS --}}
                            <template x-if="drawerType === 'create_level' || drawerType === 'edit_level'">
                                <div class="space-y-6">
                                    <div class="grid gap-4 sm:grid-cols-2">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Level Name <span class="text-rose-500">*</span></label>
                                            <input
                                                type="text"
                                                x-model="drawerData.name"
                                                @input="if (!drawerData.manualSlug) drawerData.slug = window.slugify(drawerData.name)"
                                                placeholder="e.g. Basic 1"
                                                required
                                                class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs font-semibold focus:border-blue-500 focus:outline-none" />
                                            <template x-if="drawerErrors.name">
                                                <p class="mt-1 text-[11px] font-bold text-rose-600" x-text="drawerErrors.name[0]"></p>
                                            </template>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Slug</label>
                                            <input
                                                type="text"
                                                x-model="drawerData.slug"
                                                @input="drawerData.manualSlug = true"
                                                placeholder="Auto-generated"
                                                class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs font-semibold focus:border-blue-500 focus:outline-none" />
                                            <template x-if="drawerErrors.slug">
                                                <p class="mt-1 text-[11px] font-bold text-rose-600" x-text="drawerErrors.slug[0]"></p>
                                            </template>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Short Description</label>
                                        <textarea
                                            x-model="drawerData.short_description"
                                            rows="2"
                                            placeholder="Short summary for this level..."
                                            class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs font-semibold focus:border-blue-500 focus:outline-none"></textarea>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Full Description</label>
                                        <textarea
                                            id="drawer_description"
                                            name="description"
                                            x-model="drawerData.description"
                                            rows="4"
                                            placeholder="Detailed level overview and syllabus..."
                                            class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs font-semibold focus:border-blue-500 focus:outline-none"></textarea>
                                    </div>

                                    {{-- Media Section --}}
                                    <div class="border-t border-slate-200 pt-4 space-y-4">
                                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500">Media Settings</h4>

                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Thumbnail Type</label>
                                            <select
                                                x-model="drawerData.thumbnail_type"
                                                class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs font-semibold focus:border-blue-500 focus:outline-none">
                                                <option value="image">Image</option>
                                                <option value="video">Video</option>
                                            </select>
                                        </div>

                                        {{-- Image Thumbnail --}}
                                        <div x-show="drawerData.thumbnail_type === 'image'" class="space-y-2">
                                            <template x-if="drawerData.thumbnail_url">
                                                <div class="mb-2">
                                                    <span class="text-[11px] font-bold text-slate-500">Current Image:</span>
                                                    <img :src="drawerData.thumbnail_url" class="h-24 w-40 rounded-lg object-cover mt-1 border" />
                                                </div>
                                            </template>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Upload Course Image</label>
                                            <input
                                                type="file"
                                                id="drawer_thumbnail_file_image"
                                                name="thumbnail_file"
                                                accept="image/jpeg,image/png,image/webp"
                                                class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                                            <p class="text-[10px] text-slate-400">JPG, PNG, WEBP max 4MB.</p>
                                        </div>

                                        {{-- Video Thumbnail & Poster --}}
                                        <div x-show="drawerData.thumbnail_type === 'video'" class="space-y-3">
                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 mb-1">Upload Course Video File</label>
                                                <input
                                                    type="file"
                                                    id="drawer_thumbnail_file_video"
                                                    name="thumbnail_file"
                                                    accept="video/mp4,video/webm,video/quicktime"
                                                    class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100" />
                                                <p class="text-[10px] text-slate-400">MP4, WEBM, MOV max 20MB.</p>
                                            </div>

                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 mb-1">Upload Video Poster Image</label>
                                                <input
                                                    type="file"
                                                    id="drawer_video_poster_file"
                                                    name="video_poster_file"
                                                    accept="image/jpeg,image/png,image/webp"
                                                    class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                                                <p class="text-[10px] text-slate-400">Poster image max 4MB.</p>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Pricing & Access --}}
                                    <div class="border-t border-slate-200 pt-4 space-y-4">
                                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500">Pricing & Access</h4>

                                        <div class="grid gap-4 sm:grid-cols-2">
                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 mb-1">Price (Rp) <span class="text-rose-500">*</span></label>
                                                <input
                                                    type="number"
                                                    x-model="drawerData.price"
                                                    min="0"
                                                    step="1000"
                                                    required
                                                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs font-semibold focus:border-blue-500 focus:outline-none" />
                                            </div>

                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 mb-1">Learning Mode</label>
                                                <select
                                                    x-model="drawerData.learning_mode"
                                                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs font-semibold focus:border-blue-500 focus:outline-none">
                                                    <option value="online">Online</option>
                                                    <option value="offline">Offline</option>
                                                    <option value="hybrid">Hybrid</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="grid gap-4 sm:grid-cols-2">
                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 mb-1">Access Type</label>
                                                <select
                                                    x-model="drawerData.access_type"
                                                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs font-semibold focus:border-blue-500 focus:outline-none">
                                                    <option value="lifetime">Lifetime Access</option>
                                                    <option value="limited">Limited Days</option>
                                                </select>
                                            </div>

                                            <div x-show="drawerData.access_type === 'limited'">
                                                <label class="block text-xs font-bold text-slate-700 mb-1">Access Duration (Days)</label>
                                                <input
                                                    type="number"
                                                    x-model="drawerData.access_duration_days"
                                                    min="1"
                                                    placeholder="e.g. 90"
                                                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs font-semibold focus:border-blue-500 focus:outline-none" />
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Status Checkbox --}}
                                    <div class="border-t border-slate-200 pt-4">
                                        <label class="inline-flex items-center gap-2 cursor-pointer">
                                            <input
                                                type="checkbox"
                                                x-model="drawerData.is_active"
                                                class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500" />
                                            <span class="text-xs font-bold text-slate-700">Active Level</span>
                                        </label>
                                    </div>
                                </div>
                            </template>

                            {{-- MODULE FORM FIELDS --}}
                            <template x-if="drawerType === 'create_module' || drawerType === 'edit_module'">
                                <div class="space-y-6">
                                    <div class="grid gap-4 sm:grid-cols-2">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Module Title <span class="text-rose-500">*</span></label>
                                            <input
                                                type="text"
                                                x-model="drawerData.title"
                                                @input="if (!drawerData.manualSlug) drawerData.slug = window.slugify(drawerData.title)"
                                                placeholder="e.g. Module 01 - Grammar Foundation"
                                                required
                                                class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs font-semibold focus:border-blue-500 focus:outline-none" />
                                            <template x-if="drawerErrors.title">
                                                <p class="mt-1 text-[11px] font-bold text-rose-600" x-text="drawerErrors.title[0]"></p>
                                            </template>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Slug</label>
                                            <input
                                                type="text"
                                                x-model="drawerData.slug"
                                                @input="drawerData.manualSlug = true"
                                                placeholder="Auto-generated"
                                                class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs font-semibold focus:border-blue-500 focus:outline-none" />
                                            <template x-if="drawerErrors.slug">
                                                <p class="mt-1 text-[11px] font-bold text-rose-600" x-text="drawerErrors.slug[0]"></p>
                                            </template>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Short Description</label>
                                        <textarea
                                            x-model="drawerData.short_description"
                                            rows="3"
                                            placeholder="Write a short summary for this module..."
                                            class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs font-semibold focus:border-blue-500 focus:outline-none"></textarea>
                                    </div>

                                    <div class="grid gap-4 sm:grid-cols-2">
                                        <div class="flex items-center pt-2">
                                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                                <input
                                                    type="checkbox"
                                                    x-model="drawerData.is_preview"
                                                    class="h-4 w-4 rounded border-slate-300 text-amber-600 focus:ring-amber-500" />
                                                <span class="text-xs font-bold text-slate-700">Free Preview</span>
                                            </label>
                                        </div>

                                        <div class="flex items-center pt-2">
                                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                                <input
                                                    type="checkbox"
                                                    x-model="drawerData.is_active"
                                                    class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500" />
                                                <span class="text-xs font-bold text-slate-700">Active Module</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            {{-- MATERIAL FORM FIELDS (PHASE C) --}}
                            <template x-if="drawerType === 'create_material' || drawerType === 'edit_material'">
                                <div class="space-y-6">
                                    <div class="grid gap-4 sm:grid-cols-2">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Material Title <span class="text-rose-500">*</span></label>
                                            <input
                                                type="text"
                                                x-model="drawerData.title"
                                                placeholder="e.g. Introduction & Grammar Rules"
                                                required
                                                class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs font-semibold focus:border-blue-500 focus:outline-none" />
                                            <template x-if="drawerErrors.title">
                                                <p class="mt-1 text-[11px] font-bold text-rose-600" x-text="drawerErrors.title[0]"></p>
                                            </template>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Material Type <span class="text-rose-500">*</span></label>
                                            <select
                                                x-model="drawerData.material_type"
                                                required
                                                class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs font-semibold focus:border-blue-500 focus:outline-none">
                                                <option value="text">Text / Rich Content</option>
                                                <option value="image">Image</option>
                                                <option value="video">Video</option>
                                                <option value="audio">Audio / Voice Note</option>
                                                <option value="pdf">PDF Document</option>
                                                <option value="file">File (DOC, PPT, ZIP)</option>
                                            </select>
                                            <template x-if="drawerErrors.material_type">
                                                <p class="mt-1 text-[11px] font-bold text-rose-600" x-text="drawerErrors.material_type[0]"></p>
                                            </template>
                                        </div>
                                    </div>

                                    {{-- Text Material Content --}}
                                    <div x-show="drawerData.material_type === 'text'">
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Learning Content <span class="text-rose-500">*</span></label>
                                        <textarea
                                            id="drawer_material_content"
                                            name="content"
                                            x-model="drawerData.content"
                                            rows="8"
                                            placeholder="Write formatted learning content..."
                                            class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs font-semibold focus:border-blue-500 focus:outline-none"></textarea>
                                        <template x-if="drawerErrors.content">
                                            <p class="mt-1 text-[11px] font-bold text-rose-600" x-text="drawerErrors.content[0]"></p>
                                        </template>
                                    </div>

                                    {{-- File Upload Material Content --}}
                                    <div x-show="drawerData.material_type !== 'text'" class="space-y-4">
                                        <template x-if="drawerData.file_url">
                                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                                <p class="text-xs font-bold text-slate-700">Current Attached File:</p>
                                                <a :href="drawerData.file_url" target="_blank" class="mt-1 inline-flex text-xs font-bold text-blue-600 hover:underline">
                                                    Open Current File &rarr;
                                                </a>
                                                <template x-if="drawerData.material_type === 'image'">
                                                    <img :src="drawerData.file_url" class="mt-2 h-32 w-48 rounded-xl object-cover border" />
                                                </template>
                                                <template x-if="drawerData.material_type === 'video'">
                                                    <video :src="drawerData.file_url" controls class="mt-2 h-32 w-64 rounded-xl bg-slate-900 object-cover"></video>
                                                </template>
                                                <template x-if="drawerData.material_type === 'audio'">
                                                    <audio :src="drawerData.file_url" controls class="mt-2 w-full"></audio>
                                                </template>
                                            </div>
                                        </template>

                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                                <span x-text="drawerData.file_url ? 'Replace File' : 'Upload File'"></span>
                                                <span x-show="!drawerData.file_url" class="text-rose-500">*</span>
                                            </label>
                                            <input
                                                type="file"
                                                id="drawer_material_file_path"
                                                name="file_path"
                                                accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.ppt,.pptx,.zip,.rar"
                                                class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                                            <p class="mt-1 text-[10px] text-slate-400">
                                                Max size: Image (4MB), Audio/PDF (20MB), Video/File (50MB).
                                            </p>
                                            <template x-if="drawerErrors.file_path">
                                                <p class="mt-1 text-[11px] font-bold text-rose-600" x-text="drawerErrors.file_path[0]"></p>
                                            </template>
                                        </div>
                                    </div>

                                    {{-- Status Checkbox --}}
                                    <div class="border-t border-slate-200 pt-4">
                                        <label class="inline-flex items-center gap-2 cursor-pointer">
                                            <input
                                                type="checkbox"
                                                x-model="drawerData.is_active"
                                                class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500" />
                                            <span class="text-xs font-bold text-slate-700">Active Material</span>
                                        </label>
                                    </div>
                                </div>
                            </template>
                        </form>
                    </div>

                    {{-- Drawer Footer --}}
                    <div class="border-t border-slate-200 p-4 bg-slate-50 flex items-center justify-end gap-3">
                        <button
                            type="button"
                            @click="closeDrawer()"
                            class="rounded-xl border border-slate-300 bg-white px-4 py-2 text-xs font-bold text-slate-700 hover:bg-slate-100 transition">
                            Cancel
                        </button>
                        <button
                            type="button"
                            @click="submitDrawerForm()"
                            :disabled="drawerSaving"
                            class="inline-flex items-center gap-2 rounded-xl bg-[var(--color-brand-blue)] px-5 py-2 text-xs font-bold text-white shadow-sm hover:opacity-90 disabled:opacity-50">
                            <template x-if="drawerSaving">
                                <div class="h-3.5 w-3.5 animate-spin rounded-full border-2 border-white border-t-transparent"></div>
                            </template>
                            <span x-text="drawerSaving ? 'Saving...' : 'Save Changes'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
