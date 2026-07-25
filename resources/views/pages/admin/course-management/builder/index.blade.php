@extends('layouts.admin', [
    'pageTitle' => 'Course Builder',
    'pageSubtitle' => $courseProgram->name,
])

@push('scripts')
    @vite(['resources/js/course-builder.js', 'resources/js/admin-rich-text.js'])
@endpush

@section('content')
<script>
    const registerCourseBuilder = () => {
        if (!window.Alpine) return;

        window.slugify = window.slugify || function(text) {
            return text ? text.toString().toLowerCase().trim().replace(/\s+/g, '-').replace(/[^\w\-]+/g, '').replace(/\-\-+/g, '-') : '';
        };

        window.Alpine.data('courseBuilder', (config = {}) => ({
            programId: config.programId || null,
            workspaceUrl: config.workspaceUrl || '',
            treeUrl: config.treeUrl || '',
            firstLevelId: config.firstLevelId || null,
            loading: false,
            error: null,
            workspaceHtml: '',
            treeHtml: '',
            mobileTreeOpen: false,
            expandedNodes: {},
            fetchSequenceToken: 0,

            selectedParams: {
                level: null,
                module: null,
                exam: null,
                tab: 'overview'
            },

            // Drawer State
            drawerOpen: false,
            drawerType: null,
            drawerTitle: '',
            drawerParentContext: '',
            drawerLoading: false,
            drawerSaving: false,
            drawerErrors: {},
            drawerGeneralError: null,
            drawerActionUrl: '',
            drawerMethod: 'POST',
            drawerData: {},

            // Delete Modal State
            deleteModalOpen: false,
            deleteModalTitle: '',
            deleteModalItemTitle: '',
            deleteModalUrl: '',
            deleteModalDeleting: false,
            deleteModalError: null,
            deleteRedirectNode: null,

            init() {
                this.loadExpandedState();
                this.readQueryParams();

                if (this.firstLevelId && !this.selectedParams.level) {
                    this.expandedNodes[`level_${this.firstLevelId}`] = true;
                    this.saveExpandedState();
                }

                if (this.selectedParams.level || this.selectedParams.module || this.selectedParams.exam || (this.selectedParams.tab && this.selectedParams.tab !== 'overview')) {
                    this.fetchWorkspace(false);
                }

                window.addEventListener('popstate', () => {
                    this.readQueryParams();
                    this.fetchWorkspace(false);
                });
            },

            getStorageKey() {
                return `course_builder_expanded_p${this.programId}`;
            },

            loadExpandedState() {
                try {
                    const stored = localStorage.getItem(this.getStorageKey());
                    this.expandedNodes = stored ? JSON.parse(stored) : {};
                } catch (e) {
                    this.expandedNodes = {};
                }
            },

            saveExpandedState() {
                try {
                    localStorage.setItem(this.getStorageKey(), JSON.stringify(this.expandedNodes));
                } catch (e) {}
            },

            toggleNode(nodeId) {
                this.expandedNodes[nodeId] = !this.expandedNodes[nodeId];
                this.saveExpandedState();
            },

            isNodeExpanded(nodeId) {
                return !!this.expandedNodes[nodeId];
            },

            readQueryParams() {
                const urlParams = new URLSearchParams(window.location.search);
                this.selectedParams = {
                    level: urlParams.get('level') || null,
                    module: urlParams.get('module') || null,
                    exam: urlParams.get('exam') || null,
                    tab: urlParams.get('tab') || 'overview'
                };
            },

            selectNode(params = {}, updateUrl = true) {
                const newParams = {
                    level: params.level !== undefined ? params.level : this.selectedParams.level,
                    module: params.module !== undefined ? params.module : null,
                    exam: params.exam !== undefined ? params.exam : null,
                    tab: params.tab || 'overview'
                };

                if (
                    String(newParams.level) === String(this.selectedParams.level) &&
                    String(newParams.module) === String(this.selectedParams.module) &&
                    String(newParams.exam) === String(this.selectedParams.exam) &&
                    String(newParams.tab) === String(this.selectedParams.tab)
                ) {
                    this.mobileTreeOpen = false;
                    return;
                }

                if (newParams.level) {
                    this.expandedNodes[`level_${newParams.level}`] = true;
                }
                if (newParams.level && (newParams.module || newParams.exam || newParams.tab === 'final-exam')) {
                    if (newParams.module) {
                        this.expandedNodes[`level_${newParams.level}_modules`] = true;
                    }
                    if (newParams.exam || newParams.tab === 'final-exam') {
                        this.expandedNodes[`level_${newParams.level}_exam`] = true;
                    }
                }
                this.saveExpandedState();

                this.selectedParams = newParams;
                this.mobileTreeOpen = false;

                if (updateUrl) {
                    const searchParams = new URLSearchParams();
                    if (newParams.level) searchParams.set('level', newParams.level);
                    if (newParams.module) searchParams.set('module', newParams.module);
                    if (newParams.exam) searchParams.set('exam', newParams.exam);
                    if (newParams.tab && newParams.tab !== 'overview') searchParams.set('tab', newParams.tab);

                    const newRelativePathQuery = window.location.pathname + (searchParams.toString() ? '?' + searchParams.toString() : '');
                    window.history.pushState(null, '', newRelativePathQuery);
                }

                this.fetchWorkspace();
            },

            fetchWorkspace() {
                this.loading = true;
                this.error = null;

                this.fetchSequenceToken += 1;
                const currentToken = this.fetchSequenceToken;

                const searchParams = new URLSearchParams();
                if (this.selectedParams.level) searchParams.set('level', this.selectedParams.level);
                if (this.selectedParams.module) searchParams.set('module', this.selectedParams.module);
                if (this.selectedParams.exam) searchParams.set('exam', this.selectedParams.exam);
                if (this.selectedParams.tab) searchParams.set('tab', this.selectedParams.tab);

                const fetchUrl = `${this.workspaceUrl}?${searchParams.toString()}`;

                fetch(fetchUrl, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error(`Server returned status ${res.status}`);
                    return res.json();
                })
                .then(data => {
                    if (currentToken !== this.fetchSequenceToken) return;
                    if (data.status === 'success') {
                        this.workspaceHtml = data.html;
                    } else {
                        throw new Error(data.message || 'Failed to load workspace');
                    }
                })
                .catch(err => {
                    if (currentToken !== this.fetchSequenceToken) return;
                    this.error = err.message || 'Error connecting to server.';
                })
                .finally(() => {
                    if (currentToken === this.fetchSequenceToken) {
                        this.loading = false;
                    }
                });
            },

            refreshTree() {
                if (!this.treeUrl) return;
                fetch(this.treeUrl, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success' && data.html) {
                        this.treeHtml = data.html;
                    }
                })
                .catch(e => {});
            },

            // DRAWER ACTIONS
            openCreateLevelDrawer(storeUrl) {
                this.drawerType = 'create_level';
                this.drawerTitle = 'Add Course Level';
                this.drawerParentContext = 'Program Course Level';
                this.drawerActionUrl = storeUrl;
                this.drawerMethod = 'POST';
                this.drawerErrors = {};
                this.drawerGeneralError = null;
                this.drawerLoading = false;
                this.drawerSaving = false;

                this.drawerData = {
                    name: '',
                    slug: '',
                    manualSlug: false,
                    short_description: '',
                    description: '',
                    thumbnail_type: 'image',
                    thumbnail_url: null,
                    video_poster_url: null,
                    price: 0,
                    learning_mode: 'online',
                    access_type: 'lifetime',
                    access_duration_days: '',
                    sort_order: 0,
                    is_active: true
                };

                this.drawerOpen = true;
            },

            openEditLevelDrawer(editUrl) {
                this.drawerType = 'edit_level';
                this.drawerTitle = 'Edit Course Level';
                this.drawerParentContext = 'Program Course Level';
                this.drawerErrors = {};
                this.drawerGeneralError = null;
                this.drawerLoading = true;
                this.drawerSaving = false;
                this.drawerOpen = true;

                fetch(editUrl, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(res => {
                    if (res.status === 'success') {
                        const d = res.data;
                        this.drawerActionUrl = d.update_url;
                        this.drawerMethod = 'PUT';
                        this.drawerData = {
                            id: d.id,
                            name: d.name || '',
                            slug: d.slug || '',
                            manualSlug: true,
                            short_description: d.short_description || '',
                            description: d.description || '',
                            thumbnail_type: d.thumbnail_type || 'image',
                            thumbnail_url: d.thumbnail_url,
                            video_poster_url: d.video_poster_url,
                            price: d.price || 0,
                            learning_mode: d.learning_mode || 'online',
                            access_type: d.access_type || 'lifetime',
                            access_duration_days: d.access_duration_days || '',
                            sort_order: d.sort_order || 0,
                            is_active: !!d.is_active
                        };
                    } else {
                        this.drawerGeneralError = res.message || 'Failed to fetch level details.';
                    }
                })
                .catch(err => {
                    this.drawerGeneralError = err.message || 'Server error loading level.';
                })
                .finally(() => {
                    this.drawerLoading = false;
                });
            },

            openCreateModuleDrawer(levelId, storeUrl) {
                this.drawerType = 'create_module';
                this.drawerTitle = 'Add Course Module';
                this.drawerParentContext = 'Course Level Module';
                this.drawerActionUrl = storeUrl;
                this.drawerMethod = 'POST';
                this.drawerErrors = {};
                this.drawerGeneralError = null;
                this.drawerLoading = false;
                this.drawerSaving = false;

                this.drawerData = {
                    title: '',
                    slug: '',
                    manualSlug: false,
                    short_description: '',
                    sort_order: 0,
                    is_preview: false,
                    is_active: true
                };

                this.drawerOpen = true;
            },

            openEditModuleDrawer(editUrl) {
                this.drawerType = 'edit_module';
                this.drawerTitle = 'Edit Course Module';
                this.drawerParentContext = 'Course Level Module';
                this.drawerErrors = {};
                this.drawerGeneralError = null;
                this.drawerLoading = true;
                this.drawerSaving = false;
                this.drawerOpen = true;

                fetch(editUrl, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(res => {
                    if (res.status === 'success') {
                        const d = res.data;
                        this.drawerActionUrl = d.update_url;
                        this.drawerMethod = 'PUT';
                        this.drawerData = {
                            id: d.id,
                            title: d.title || '',
                            slug: d.slug || '',
                            manualSlug: true,
                            short_description: d.short_description || '',
                            sort_order: d.sort_order || 0,
                            is_preview: !!d.is_preview,
                            is_active: !!d.is_active
                        };
                    } else {
                        this.drawerGeneralError = res.message || 'Failed to fetch module details.';
                    }
                })
                .catch(err => {
                    this.drawerGeneralError = err.message || 'Server error loading module.';
                })
                .finally(() => {
                    this.drawerLoading = false;
                });
            },

            openCreateMaterialDrawer(moduleId, storeUrl) {
                this.drawerType = 'create_material';
                this.drawerTitle = 'Add Module Material';
                this.drawerParentContext = 'Module Material';
                this.drawerActionUrl = storeUrl;
                this.drawerMethod = 'POST';
                this.drawerErrors = {};
                this.drawerGeneralError = null;
                this.drawerLoading = false;
                this.drawerSaving = false;

                this.drawerData = {
                    module_id: moduleId,
                    title: '',
                    material_type: 'text',
                    content: '',
                    file_path: null,
                    file_url: null,
                    is_active: true
                };

                this.drawerOpen = true;
            },

            openEditMaterialDrawer(editUrl) {
                this.drawerType = 'edit_material';
                this.drawerTitle = 'Edit Module Material';
                this.drawerParentContext = 'Module Material';
                this.drawerErrors = {};
                this.drawerGeneralError = null;
                this.drawerLoading = true;
                this.drawerSaving = false;
                this.drawerOpen = true;

                fetch(editUrl, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(res => {
                    if (res.status === 'success') {
                        const d = res.data;
                        this.drawerActionUrl = d.update_url;
                        this.drawerMethod = 'PUT';
                        this.drawerData = {
                            id: d.id,
                            module_id: d.module_id,
                            title: d.title || '',
                            material_type: d.material_type || 'text',
                            content: d.content || '',
                            file_path: d.file_path,
                            file_url: d.file_url,
                            sort_order: d.sort_order,
                            is_active: !!d.is_active
                        };
                    } else {
                        this.drawerGeneralError = res.message || 'Failed to fetch material details.';
                    }
                })
                .catch(err => {
                    this.drawerGeneralError = err.message || 'Server error loading material.';
                })
                .finally(() => {
                    this.drawerLoading = false;
                });
            },

            isDrawerDirty() {
                if (!this.drawerOpen || this.drawerSaving || this.drawerLoading) return false;
                if (this.drawerType === 'create_level' && (this.drawerData.name || this.drawerData.short_description)) return true;
                if (this.drawerType === 'create_module' && (this.drawerData.title || this.drawerData.short_description)) return true;
                if (this.drawerType === 'create_material' && (this.drawerData.title || this.drawerData.content)) return true;
                return false;
            },

            closeDrawer(force = false) {
                if (this.drawerSaving) return;
                if (!force && this.isDrawerDirty()) {
                    if (!confirm('You have unsaved changes. Are you sure you want to close?')) return;
                }
                this.drawerOpen = false;
            },

            submitDrawerForm() {
                if (this.drawerSaving) return;
                this.drawerSaving = true;
                this.drawerErrors = {};
                this.drawerGeneralError = null;

                if (window.tinymce) {
                    try { window.tinymce.triggerSave(); } catch(e) {}
                }

                const formEl = document.getElementById('builderDrawerForm');
                const formData = new FormData(formEl);

                if (this.drawerMethod === 'PUT') {
                    formData.append('_method', 'PUT');
                }

                formData.append('expected_program_id', this.programId);

                if (this.drawerType.includes('level')) {
                    formData.set('name', this.drawerData.name || '');
                    formData.set('slug', this.drawerData.slug || '');
                    formData.set('short_description', this.drawerData.short_description || '');
                    formData.set('description', this.drawerData.description || (document.getElementById('drawer_description')?.value || ''));
                    formData.set('thumbnail_type', this.drawerData.thumbnail_type || 'image');
                    formData.set('price', this.drawerData.price || 0);
                    formData.set('learning_mode', this.drawerData.learning_mode || 'online');
                    formData.set('access_type', this.drawerData.access_type || 'lifetime');
                    if (this.drawerData.access_type === 'limited' && this.drawerData.access_duration_days) {
                        formData.set('access_duration_days', this.drawerData.access_duration_days);
                    }
                    if (this.drawerData.sort_order !== undefined) {
                        formData.set('sort_order', this.drawerData.sort_order);
                    }
                    if (this.drawerData.is_active) {
                        formData.set('is_active', '1');
                    } else {
                        formData.delete('is_active');
                    }

                    const imgInput = document.getElementById('drawer_thumbnail_file_image');
                    if (imgInput && imgInput.files.length > 0) {
                        formData.set('thumbnail_file', imgInput.files[0]);
                    }
                    const vidInput = document.getElementById('drawer_thumbnail_file_video');
                    if (vidInput && vidInput.files.length > 0) {
                        formData.set('thumbnail_file', vidInput.files[0]);
                    }
                    const posterInput = document.getElementById('drawer_video_poster_file');
                    if (posterInput && posterInput.files.length > 0) {
                        formData.set('video_poster_file', posterInput.files[0]);
                    }
                } else if (this.drawerType.includes('module')) {
                    formData.set('title', this.drawerData.title || '');
                    formData.set('slug', this.drawerData.slug || '');
                    formData.set('short_description', this.drawerData.short_description || '');
                    if (this.drawerData.sort_order !== undefined) {
                        formData.set('sort_order', this.drawerData.sort_order);
                    }
                    if (this.drawerData.is_preview) {
                        formData.set('is_preview', '1');
                    } else {
                        formData.delete('is_preview');
                    }
                    if (this.drawerData.is_active) {
                        formData.set('is_active', '1');
                    } else {
                        formData.delete('is_active');
                    }
                } else if (this.drawerType.includes('material')) {
                    formData.set('title', this.drawerData.title || '');
                    formData.set('material_type', this.drawerData.material_type || 'text');
                    if (this.drawerData.sort_order !== undefined) {
                        formData.set('sort_order', this.drawerData.sort_order);
                    }
                    if (this.drawerData.material_type === 'text') {
                        formData.set('content', this.drawerData.content || (document.getElementById('drawer_material_content')?.value || ''));
                    } else {
                        const matFileInput = document.getElementById('drawer_material_file_path');
                        if (matFileInput && matFileInput.files.length > 0) {
                            formData.set('file_path', matFileInput.files[0]);
                        }
                    }
                    if (this.drawerData.is_active) {
                        formData.set('is_active', '1');
                    } else {
                        formData.delete('is_active');
                    }
                }

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                fetch(this.drawerActionUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken || ''
                    }
                })
                .then(res => {
                    if (res.status === 422) {
                        return res.json().then(errData => {
                            this.drawerErrors = errData.errors || {};
                            this.drawerGeneralError = errData.message || 'Please check validation errors.';
                            throw new Error('Validation Error');
                        });
                    }
                    if (!res.ok) throw new Error(`Server error (${res.status})`);
                    return res.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        this.drawerOpen = false;
                        this.refreshTree();
                        if (data.redirect_node) {
                            this.selectNode(data.redirect_node);
                        } else {
                            this.fetchWorkspace();
                        }
                    } else {
                        this.drawerGeneralError = data.message || 'Operation failed.';
                    }
                })
                .catch(err => {
                    if (err.message !== 'Validation Error') {
                        this.drawerGeneralError = err.message || 'An error occurred while saving.';
                    }
                })
                .finally(() => {
                    this.drawerSaving = false;
                });
            },

            // DELETE MODAL ACTIONS
            confirmDelete(type, id, title, deleteUrl, redirectNode = null) {
                this.deleteModalTitle = type === 'level' ? 'Delete Course Level' : (type === 'module' ? 'Delete Module' : 'Delete Material');
                this.deleteModalItemTitle = title;
                this.deleteModalUrl = deleteUrl;
                this.deleteRedirectNode = redirectNode || { level: null, module: null, exam: null, tab: 'overview' };
                this.deleteModalDeleting = false;
                this.deleteModalError = null;
                this.deleteModalOpen = true;
            },

            executeDelete() {
                if (this.deleteModalDeleting) return;
                this.deleteModalDeleting = true;
                this.deleteModalError = null;

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                fetch(this.deleteModalUrl, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken || ''
                    }
                })
                .then(res => {
                    if (res.status === 422) {
                        return res.json().then(errData => {
                            this.deleteModalError = errData.message || 'Cannot delete item.';
                        });
                    }
                    if (!res.ok) throw new Error(`Delete failed (${res.status})`);
                    return res.json();
                })
                .then(data => {
                    if (!data) return;
                    if (data.status === 'success') {
                        this.deleteModalOpen = false;
                        this.refreshTree();
                        this.selectNode(data.redirect_node || this.deleteRedirectNode);
                    } else {
                        this.deleteModalError = data.message || 'Failed to delete item.';
                    }
                })
                .catch(err => {
                    if (!this.deleteModalError) {
                        this.deleteModalError = err.message || 'Server error deleting item.';
                    }
                })
                .finally(() => {
                    this.deleteModalDeleting = false;
                });
            }
        }));
    };

    if (window.Alpine) {
        registerCourseBuilder();
    } else {
        document.addEventListener('alpine:init', registerCourseBuilder);
    }
</script>

<section
    x-data="courseBuilder({
        programId: {{ $courseProgram->id }},
        workspaceUrl: '{{ route('admin.course-management.programs.builder.workspace', $courseProgram->id) }}',
        treeUrl: '{{ route('admin.course-management.programs.builder.tree', $courseProgram->id) }}',
        firstLevelId: {{ $firstLevelId ?: 'null' }}
    })"
    class="mx-auto max-w-7xl space-y-5">

    {{-- Compact Header Bar --}}
    <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <a
                href="{{ route('admin.course-management.programs.index') }}"
                class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-slate-600 shadow-sm transition hover:bg-slate-100 hover:text-slate-900"
                title="Back to Course Programs">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>

            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Course Builder</span>
                    <span class="text-xs text-slate-300">•</span>
                    <span class="inline-flex items-center rounded-full px-2 py-0.2 text-[10px] font-bold uppercase {{ $courseProgram->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                        {{ $courseProgram->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <h1 class="text-lg font-bold text-slate-900 lg:text-xl">
                    {{ $courseProgram->name }}
                </h1>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            {{-- Mobile Toggle Tree Button --}}
            <button
                type="button"
                @click="mobileTreeOpen = !mobileTreeOpen"
                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-100 lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                </svg>
                <span>Structure Tree</span>
            </button>

            {{-- Primary Action: Add Level via Drawer --}}
            <button
                type="button"
                @click="openCreateLevelDrawer('{{ route('admin.course-management.programs.levels.store', $courseProgram->id) }}')"
                class="inline-flex items-center gap-1.5 rounded-xl bg-[var(--color-brand-blue)] px-4 py-2 text-xs font-bold text-white shadow-sm transition hover:opacity-90">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>+ Add Level</span>
            </button>
        </div>
    </div>

    {{-- Main Builder 2-Column Grid Layout --}}
    <div class="grid gap-6 lg:grid-cols-12">
        {{-- Left Column: Tree Navigation --}}
        <div
            :class="mobileTreeOpen ? 'block' : 'hidden lg:block'"
            class="lg:col-span-4 xl:col-span-4">
            <div class="sticky top-6 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm max-h-[calc(100vh-140px)] overflow-y-auto">
                <div class="mb-3 flex items-center justify-between border-b border-slate-100 pb-2 lg:hidden">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Course Structure</span>
                    <button
                        type="button"
                        @click="mobileTreeOpen = false"
                        class="rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div x-html="treeHtml || $el.innerHTML">
                    @include('partials.admin.course-management.builder.tree')
                </div>
            </div>
        </div>

        {{-- Right Column: Workspace Panel --}}
        <div class="lg:col-span-8 xl:col-span-8">
            @include('partials.admin.course-management.builder.workspace')
        </div>
    </div>

    {{-- Slide-Over Drawer --}}
    @include('partials.admin.course-management.builder.drawer')

    {{-- Delete Modal --}}
    @include('partials.admin.course-management.builder.delete-modal')
</section>
@endsection
