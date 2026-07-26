export function registerCourseBuilder(Alpine) {
    if (!Alpine) return;

    window.slugify = window.slugify || function(text) {
        return text ? text.toString().toLowerCase().trim().replace(/\s+/g, '-').replace(/[^\w\-]+/g, '').replace(/\-\-+/g, '-') : '';
    };

    Alpine.data('courseBuilder', (config = {}) => ({
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
        lastActiveTriggerEl: null,

        selectedParams: {
            level: null,
            module: null,
            exam: null,
            tab: 'overview',
            page: 1
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

        // Reorder Mode State (Phase F)
        reorderMode: false,
        reorderType: null,
        reorderTitle: '',
        reorderItems: [],
        originalReorderItems: '',
        reorderSaving: false,
        reorderError: null,
        reorderConflict: false,
        reorderSaveUrl: '',
        draggedItemIndex: null,

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

            // Global Keyboard Shortcuts
            window.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    if (this.deleteModalOpen) {
                        this.deleteModalOpen = false;
                    } else if (this.drawerOpen) {
                        this.closeDrawer();
                    } else if (this.reorderMode) {
                        this.discardReorder();
                    } else if (this.mobileTreeOpen) {
                        this.mobileTreeOpen = false;
                    }
                }
            });

            // Unsaved Changes Guard for Page Reload / Navigation
            window.addEventListener('beforeunload', (e) => {
                if (this.isDrawerDirty() || (this.reorderMode && JSON.stringify(this.reorderItems) !== this.originalReorderItems)) {
                    e.preventDefault();
                    e.returnValue = '';
                }
            });

            // Body Scroll Locking Watchers
            this.$watch('mobileTreeOpen', val => this.updateBodyScroll());
            this.$watch('drawerOpen', val => this.updateBodyScroll());
            this.$watch('deleteModalOpen', val => this.updateBodyScroll());
        },

        updateBodyScroll() {
            const isAnyModalOpen = this.mobileTreeOpen || this.drawerOpen || this.deleteModalOpen;
            document.body.classList.toggle('overflow-hidden', isAnyModalOpen);
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
                tab: urlParams.get('tab') || 'overview',
                page: urlParams.get('page') || 1
            };
        },

        selectNode(params = {}, updateUrl = true, force = false) {
            if (!force) {
                if (this.reorderMode && JSON.stringify(this.reorderItems) !== this.originalReorderItems) {
                    if (!confirm('You have unsaved reorder changes. Are you sure you want to discard them?')) {
                        return;
                    }
                }
                if (this.isDrawerDirty()) {
                    if (!confirm('You have unsaved form changes. Are you sure you want to navigate away?')) {
                        return;
                    }
                }
            }
            this.reorderMode = false;
            this.reorderConflict = false;

            const newParams = {
                level: params.level !== undefined ? params.level : this.selectedParams.level,
                module: params.module !== undefined ? params.module : null,
                exam: params.exam !== undefined ? params.exam : null,
                tab: params.tab || 'overview',
                page: params.page || 1
            };

            if (
                !force &&
                String(newParams.level) === String(this.selectedParams.level) &&
                String(newParams.module) === String(this.selectedParams.module) &&
                String(newParams.exam) === String(this.selectedParams.exam) &&
                String(newParams.tab) === String(this.selectedParams.tab) &&
                String(newParams.page) === String(this.selectedParams.page)
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
                if (newParams.page && newParams.page > 1) searchParams.set('page', newParams.page);

                const newRelativePathQuery = window.location.pathname + (searchParams.toString() ? '?' + searchParams.toString() : '');
                window.history.pushState(null, '', newRelativePathQuery);
            }

            this.fetchWorkspace(force);
        },

        fetchWorkspace(force = false) {
            this.loading = true;
            this.error = null;

            this.fetchSequenceToken += 1;
            const currentToken = this.fetchSequenceToken;

            const searchParams = new URLSearchParams();
            if (this.selectedParams.level) searchParams.set('level', this.selectedParams.level);
            if (this.selectedParams.module) searchParams.set('module', this.selectedParams.module);
            if (this.selectedParams.exam) searchParams.set('exam', this.selectedParams.exam);
            if (this.selectedParams.tab) searchParams.set('tab', this.selectedParams.tab);
            if (this.selectedParams.page && this.selectedParams.page > 1) searchParams.set('page', this.selectedParams.page);

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
                if (typeof window !== 'undefined' && !window.navigator.onLine) {
                    this.error = 'Network disconnected. Please check your internet connection and click Retry.';
                } else {
                    this.error = err.message || 'Error connecting to server. Please try again.';
                }
            })
            .finally(() => {
                if (currentToken === this.fetchSequenceToken) {
                    this.loading = false;
                }
            });
        },

        retryFetch() {
            this.fetchWorkspace(true);
        },

        refreshTree() {
            if (!this.treeUrl) return Promise.resolve();
            return fetch(this.treeUrl, {
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

        // RICH TEXT TINYMCE LIFECYCLE MANAGEMENT
        initRichTextEditors() {
            setTimeout(() => {
                if (typeof window.initAdminRichTextEditors === 'function') {
                    window.initAdminRichTextEditors();
                }
                if (window.tinymce) {
                    document.querySelectorAll('#builderDrawerForm textarea.js-admin-rich-text').forEach(el => {
                        const inst = window.tinymce.get(el.id);
                        if (inst && this.drawerData) {
                            const propName = el.id.replace('drawer_', '').replace('_text', '');
                            const val = this.drawerData[propName] || this.drawerData[el.name] || '';
                            if (val !== undefined) {
                                inst.setContent(val);
                            }
                        }
                    });
                }
            }, 120);
        },

        destroyRichTextEditors() {
            if (window.tinymce) {
                document.querySelectorAll('#builderDrawerForm textarea.js-admin-rich-text').forEach(el => {
                    const inst = window.tinymce.get(el.id);
                    if (inst) {
                        try { inst.destroy(); } catch(e) {}
                    }
                    el.removeAttribute('data-tinymce-ready');
                });
            }
        },

        // REORDER MANAGEMENT METHODS
        startReorder(type, title, saveUrl, itemsList = []) {
            if (this.isDrawerDirty()) {
                if (!confirm('You have unsaved drawer changes. Discard them to enter Reorder Mode?')) return;
                this.closeDrawer(true);
            }
            this.reorderType = type;
            this.reorderTitle = title;
            this.reorderSaveUrl = saveUrl;
            this.reorderItems = JSON.parse(JSON.stringify(itemsList));
            this.originalReorderItems = JSON.stringify(itemsList);
            this.reorderSaving = false;
            this.reorderError = null;
            this.reorderConflict = false;
            this.reorderMode = true;
        },

        moveReorderItem(index, direction) {
            const targetIndex = index + direction;
            if (targetIndex < 0 || targetIndex >= this.reorderItems.length) return;
            const temp = this.reorderItems[index];
            this.reorderItems[index] = this.reorderItems[targetIndex];
            this.reorderItems[targetIndex] = temp;
        },

        moveReorderItemToBoundary(index, position) {
            if (position === 'top' && index > 0) {
                const item = this.reorderItems.splice(index, 1)[0];
                this.reorderItems.unshift(item);
            } else if (position === 'bottom' && index < this.reorderItems.length - 1) {
                const item = this.reorderItems.splice(index, 1)[0];
                this.reorderItems.push(item);
            }
        },

        handleDragStart(index, event) {
            this.draggedItemIndex = index;
            if (event.dataTransfer) {
                event.dataTransfer.effectAllowed = 'move';
                event.dataTransfer.setData('text/plain', index);
            }
        },

        handleDragOver(index, event) {
            event.preventDefault();
            if (event.dataTransfer) {
                event.dataTransfer.dropEffect = 'move';
            }
        },

        handleDrop(index, event) {
            event.preventDefault();
            if (this.draggedItemIndex === null || this.draggedItemIndex === index) return;
            const item = this.reorderItems.splice(this.draggedItemIndex, 1)[0];
            this.reorderItems.splice(index, 0, item);
            this.draggedItemIndex = null;
        },

        discardReorder(force = false) {
            if (!force && JSON.stringify(this.reorderItems) !== this.originalReorderItems) {
                if (!confirm('Are you sure you want to discard unsaved order changes?')) return;
            }
            this.reorderItems = JSON.parse(this.originalReorderItems);
            this.reorderMode = false;
            this.reorderError = null;
            this.reorderConflict = false;
        },

        saveReorder() {
            if (this.reorderSaving) return;
            this.reorderSaving = true;
            this.reorderError = null;
            this.reorderConflict = false;

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const orderedIds = this.reorderItems.map(item => item.id);
            const originalOrderedIds = JSON.parse(this.originalReorderItems).map(item => item.id);

            fetch(this.reorderSaveUrl, {
                method: 'PUT',
                body: JSON.stringify({
                    ordered_ids: orderedIds,
                    original_ordered_ids: originalOrderedIds
                }),
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken || ''
                }
            })
            .then(res => {
                if (res.status === 409) {
                    return res.json().then(errData => {
                        this.reorderConflict = true;
                        this.reorderError = errData.message || 'The order has been changed by another administrator. Reload the latest order and try again.';
                        throw new Error('Conflict Error');
                    });
                }
                if (res.status === 422) {
                    return res.json().then(errData => {
                        this.reorderError = errData.message || 'The item list has changed. Reload the latest order and try again.';
                        throw new Error('Validation Error');
                    });
                }
                if (!res.ok) throw new Error(`Server returned status ${res.status}`);
                return res.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    this.reorderMode = false;
                    this.reorderConflict = false;
                    this.refreshTree().then(() => {
                        this.fetchWorkspace(true);
                    });
                } else {
                    this.reorderError = data.message || 'Failed to save new order.';
                }
            })
            .catch(err => {
                if (err.message !== 'Validation Error' && err.message !== 'Conflict Error') {
                    this.reorderError = err.message || 'Error saving order.';
                }
            })
            .finally(() => {
                this.reorderSaving = false;
            });
        },

        reloadLatestOrder() {
            this.reorderMode = false;
            this.reorderConflict = false;
            this.reorderError = null;
            this.refreshTree().then(() => {
                this.fetchWorkspace(true);
            });
        },

        // DRAWER ACTIONS & FOCUS MANAGEMENT
        focusFirstDrawerInput() {
            setTimeout(() => {
                const formEl = document.getElementById('builderDrawerForm');
                if (!formEl) return;
                const firstInput = formEl.querySelector('input:not([type="hidden"]), select, textarea, button[type="submit"]');
                if (firstInput) firstInput.focus();
            }, 100);
        },

        focusFirstErrorInput() {
            setTimeout(() => {
                const firstErr = document.querySelector('#builderDrawerForm .border-rose-500, #builderDrawerForm [aria-invalid="true"]');
                if (firstErr) firstErr.focus();
            }, 100);
        },

        openCreateLevelDrawer(storeUrl) {
            this.destroyRichTextEditors();
            this.lastActiveTriggerEl = document.activeElement;
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
                is_active: true
            };

            this.drawerOpen = true;
            this.focusFirstDrawerInput();
            this.initRichTextEditors();
        },

        openEditLevelDrawer(editUrl) {
            this.destroyRichTextEditors();
            this.lastActiveTriggerEl = document.activeElement;
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
                        sort_order: d.sort_order,
                        is_active: !!d.is_active
                    };
                    this.focusFirstDrawerInput();
                    this.initRichTextEditors();
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
            this.destroyRichTextEditors();
            this.lastActiveTriggerEl = document.activeElement;
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
                is_preview: false,
                is_active: true
            };

            this.drawerOpen = true;
            this.focusFirstDrawerInput();
        },

        openEditModuleDrawer(editUrl) {
            this.destroyRichTextEditors();
            this.lastActiveTriggerEl = document.activeElement;
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
                        sort_order: d.sort_order,
                        is_preview: !!d.is_preview,
                        is_active: !!d.is_active
                    };
                    this.focusFirstDrawerInput();
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
            this.destroyRichTextEditors();
            this.lastActiveTriggerEl = document.activeElement;
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
            this.focusFirstDrawerInput();
            this.initRichTextEditors();
        },

        openEditMaterialDrawer(editUrl) {
            this.destroyRichTextEditors();
            this.lastActiveTriggerEl = document.activeElement;
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
                    this.focusFirstDrawerInput();
                    this.initRichTextEditors();
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

        openCreatePracticeDrawer(storeUrl) {
            this.destroyRichTextEditors();
            this.lastActiveTriggerEl = document.activeElement;
            this.drawerType = 'create_practice';
            this.drawerTitle = 'Configure Module Practice';
            this.drawerParentContext = 'Module Practice Quiz';
            this.drawerActionUrl = storeUrl;
            this.drawerMethod = 'POST';
            this.drawerErrors = {};
            this.drawerGeneralError = null;
            this.drawerLoading = false;
            this.drawerSaving = false;

            this.drawerData = {
                title: '',
                description: '',
                passing_grade: 70,
                grading_method: 'auto',
                max_attempts: '',
                is_required: true,
                is_active: true
            };

            this.drawerOpen = true;
            this.focusFirstDrawerInput();
            this.initRichTextEditors();
        },

        openEditPracticeDrawer(editUrl) {
            this.destroyRichTextEditors();
            this.lastActiveTriggerEl = document.activeElement;
            this.drawerType = 'edit_practice';
            this.drawerTitle = 'Edit Module Practice';
            this.drawerParentContext = 'Module Practice Quiz';
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
                        description: d.description || '',
                        passing_grade: d.passing_grade || 70,
                        grading_method: d.grading_method || 'auto',
                        max_attempts: d.max_attempts || '',
                        is_required: !!d.is_required,
                        is_active: !!d.is_active
                    };
                    this.focusFirstDrawerInput();
                    this.initRichTextEditors();
                } else {
                    this.drawerGeneralError = res.message || 'Failed to fetch practice details.';
                }
            })
            .catch(err => {
                this.drawerGeneralError = err.message || 'Server error loading practice.';
            })
            .finally(() => {
                this.drawerLoading = false;
            });
        },

        openCreateQuestionDrawer(storeUrl) {
            this.destroyRichTextEditors();
            this.lastActiveTriggerEl = document.activeElement;
            this.drawerType = 'create_question';
            this.drawerTitle = 'Add Practice Question';
            this.drawerParentContext = 'Practice Quiz Question';
            this.drawerActionUrl = storeUrl;
            this.drawerMethod = 'POST';
            this.drawerErrors = {};
            this.drawerGeneralError = null;
            this.drawerLoading = false;
            this.drawerSaving = false;

            this.drawerData = {
                question_type: 'multiple_choice',
                question: '',
                explanation: '',
                score: 10,
                options: { A: '', B: '', C: '', D: '' },
                correct_option: 'A',
                is_active: true
            };

            this.drawerOpen = true;
            this.focusFirstDrawerInput();
            this.initRichTextEditors();
        },

        openEditQuestionDrawer(editUrl) {
            this.destroyRichTextEditors();
            this.lastActiveTriggerEl = document.activeElement;
            this.drawerType = 'edit_question';
            this.drawerTitle = 'Edit Practice Question';
            this.drawerParentContext = 'Practice Quiz Question';
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
                        module_practice_id: d.module_practice_id,
                        question_type: d.question_type || 'multiple_choice',
                        question: d.question || '',
                        explanation: d.explanation || '',
                        score: d.score || 10,
                        sort_order: d.sort_order,
                        options: d.options || { A: '', B: '', C: '', D: '' },
                        correct_option: d.correct_option || 'A',
                        is_active: !!d.is_active
                    };
                    this.focusFirstDrawerInput();
                    this.initRichTextEditors();
                } else {
                    this.drawerGeneralError = res.message || 'Failed to fetch question details.';
                }
            })
            .catch(err => {
                this.drawerGeneralError = err.message || 'Server error loading question.';
            })
            .finally(() => {
                this.drawerLoading = false;
            });
        },

        openCreateFinalExamSectionDrawer(storeUrl) {
            this.destroyRichTextEditors();
            this.lastActiveTriggerEl = document.activeElement;
            this.drawerType = 'create_final_exam_section';
            this.drawerTitle = 'Add Final Exam Section';
            this.drawerParentContext = 'Course Level Final Exam';
            this.drawerActionUrl = storeUrl;
            this.drawerMethod = 'POST';
            this.drawerErrors = {};
            this.drawerGeneralError = null;
            this.drawerLoading = false;
            this.drawerSaving = false;

            this.drawerData = {
                title: '',
                description: '',
                passing_grade: 75,
                grading_method: 'auto',
                max_attempts: '',
                is_active: true
            };

            this.drawerOpen = true;
            this.focusFirstDrawerInput();
            this.initRichTextEditors();
        },

        openEditFinalExamSectionDrawer(editUrl) {
            this.destroyRichTextEditors();
            this.lastActiveTriggerEl = document.activeElement;
            this.drawerType = 'edit_final_exam_section';
            this.drawerTitle = 'Edit Final Exam Section';
            this.drawerParentContext = 'Course Level Final Exam';
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
                        course_level_id: d.course_level_id,
                        title: d.title || '',
                        description: d.description || '',
                        passing_grade: d.passing_grade || 75,
                        grading_method: d.grading_method || 'auto',
                        max_attempts: d.max_attempts || '',
                        sort_order: d.sort_order,
                        is_active: !!d.is_active
                    };
                    this.focusFirstDrawerInput();
                    this.initRichTextEditors();
                } else {
                    this.drawerGeneralError = res.message || 'Failed to fetch section details.';
                }
            })
            .catch(err => {
                this.drawerGeneralError = err.message || 'Server error loading section.';
            })
            .finally(() => {
                this.drawerLoading = false;
            });
        },

        openCreateFinalExamQuestionDrawer(storeUrl) {
            this.destroyRichTextEditors();
            this.lastActiveTriggerEl = document.activeElement;
            this.drawerType = 'create_final_exam_question';
            this.drawerTitle = 'Add Final Exam Question';
            this.drawerParentContext = 'Final Exam Section Question';
            this.drawerActionUrl = storeUrl;
            this.drawerMethod = 'POST';
            this.drawerErrors = {};
            this.drawerGeneralError = null;
            this.drawerLoading = false;
            this.drawerSaving = false;

            this.drawerData = {
                question_type: 'multiple_choice',
                question: '',
                explanation: '',
                score: 10,
                options: { A: '', B: '', C: '', D: '' },
                correct_option: 'A',
                is_active: true
            };

            this.drawerOpen = true;
            this.focusFirstDrawerInput();
            this.initRichTextEditors();
        },

        openEditFinalExamQuestionDrawer(editUrl) {
            this.destroyRichTextEditors();
            this.lastActiveTriggerEl = document.activeElement;
            this.drawerType = 'edit_final_exam_question';
            this.drawerTitle = 'Edit Final Exam Question';
            this.drawerParentContext = 'Final Exam Section Question';
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
                        final_exam_id: d.final_exam_id,
                        question_type: d.question_type || 'multiple_choice',
                        question: d.question || '',
                        explanation: d.explanation || '',
                        score: d.score || 10,
                        sort_order: d.sort_order,
                        options: d.options || { A: '', B: '', C: '', D: '' },
                        correct_option: d.correct_option || 'A',
                        is_active: !!d.is_active
                    };
                    this.focusFirstDrawerInput();
                    this.initRichTextEditors();
                } else {
                    this.drawerGeneralError = res.message || 'Failed to fetch question details.';
                }
            })
            .catch(err => {
                this.drawerGeneralError = err.message || 'Server error loading question.';
            })
            .finally(() => {
                this.drawerLoading = false;
            });
        },

        toggleFinalExamSectionActive(toggleUrl) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            fetch(toggleUrl, {
                method: 'PATCH',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken || ''
                }
            })
            .then(res => {
                if (res.status === 422) {
                    return res.json().then(data => {
                        alert(data.message || 'Cannot activate section.');
                    });
                }
                if (!res.ok) throw new Error(`Status ${res.status}`);
                return res.json();
            })
            .then(data => {
                if (data && data.status === 'success') {
                    this.refreshTree().then(() => {
                        this.fetchWorkspace(true);
                    });
                }
            })
            .catch(err => {
                alert(err.message || 'Error toggling section status.');
            });
        },

        isDrawerDirty() {
            if (!this.drawerOpen || this.drawerSaving || this.drawerLoading) return false;
            if (this.drawerType === 'create_level' && (this.drawerData.name || this.drawerData.short_description)) return true;
            if (this.drawerType === 'create_module' && (this.drawerData.title || this.drawerData.short_description)) return true;
            if (this.drawerType === 'create_material' && (this.drawerData.title || this.drawerData.content)) return true;
            if (this.drawerType === 'create_practice' && (this.drawerData.title || this.drawerData.description)) return true;
            if (this.drawerType === 'create_question' && (this.drawerData.question)) return true;
            if (this.drawerType === 'create_final_exam_section' && (this.drawerData.title || this.drawerData.description)) return true;
            if (this.drawerType === 'create_final_exam_question' && (this.drawerData.question)) return true;
            return false;
        },

        closeDrawer(force = false) {
            if (this.drawerSaving) return;
            if (!force && this.isDrawerDirty()) {
                if (!confirm('You have unsaved changes. Are you sure you want to close?')) return;
            }
            this.destroyRichTextEditors();
            this.drawerOpen = false;
            if (this.lastActiveTriggerEl && typeof this.lastActiveTriggerEl.focus === 'function') {
                this.lastActiveTriggerEl.focus();
            }
        },

        submitDrawerForm() {
            if (this.drawerSaving) return;

            if (window.tinymce) {
                try { window.tinymce.triggerSave(); } catch(e) {}
            }

            this.drawerSaving = true;
            this.drawerErrors = {};
            this.drawerGeneralError = null;

            const formEl = document.getElementById('builderDrawerForm');
            const formData = new FormData(formEl);

            if (this.drawerMethod === 'PUT') {
                formData.append('_method', 'PUT');
            }

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
                if (this.drawerData.sort_order !== undefined) formData.set('sort_order', this.drawerData.sort_order);
                if (this.drawerData.is_active) formData.set('is_active', '1'); else formData.delete('is_active');

                const imgInput = document.getElementById('drawer_thumbnail_file_image');
                if (imgInput && imgInput.files.length > 0) formData.set('thumbnail_file', imgInput.files[0]);
                const vidInput = document.getElementById('drawer_thumbnail_file_video');
                if (vidInput && vidInput.files.length > 0) formData.set('thumbnail_file', vidInput.files[0]);
                const posterInput = document.getElementById('drawer_video_poster_file');
                if (posterInput && posterInput.files.length > 0) formData.set('video_poster_file', posterInput.files[0]);
            } else if (this.drawerType.includes('module')) {
                formData.set('title', this.drawerData.title || '');
                formData.set('slug', this.drawerData.slug || '');
                formData.set('short_description', this.drawerData.short_description || '');
                if (this.drawerData.sort_order !== undefined) formData.set('sort_order', this.drawerData.sort_order);
                if (this.drawerData.is_preview) formData.set('is_preview', '1'); else formData.delete('is_preview');
                if (this.drawerData.is_active) formData.set('is_active', '1'); else formData.delete('is_active');
            } else if (this.drawerType.includes('material')) {
                formData.set('title', this.drawerData.title || '');
                formData.set('material_type', this.drawerData.material_type || 'text');
                if (this.drawerData.sort_order !== undefined) formData.set('sort_order', this.drawerData.sort_order);
                if (this.drawerData.material_type === 'text') {
                    formData.set('content', this.drawerData.content || (document.getElementById('drawer_material_content')?.value || ''));
                } else {
                    const matFileInput = document.getElementById('drawer_material_file_path');
                    if (matFileInput && matFileInput.files.length > 0) formData.set('file_path', matFileInput.files[0]);
                }
                if (this.drawerData.is_active) formData.set('is_active', '1'); else formData.delete('is_active');
            } else if (this.drawerType.includes('practice') && !this.drawerType.includes('question')) {
                formData.set('title', this.drawerData.title || '');
                formData.set('description', this.drawerData.description || (document.getElementById('drawer_practice_description')?.value || ''));
                formData.set('passing_grade', this.drawerData.passing_grade || 70);
                formData.set('grading_method', this.drawerData.grading_method || 'auto');
                if (this.drawerData.max_attempts) formData.set('max_attempts', this.drawerData.max_attempts); else formData.delete('max_attempts');
                if (this.drawerData.is_required) formData.set('is_required', '1'); else formData.delete('is_required');
                if (this.drawerData.is_active) formData.set('is_active', '1'); else formData.delete('is_active');
            } else if (this.drawerType.includes('question') && !this.drawerType.includes('final_exam')) {
                formData.set('question_type', this.drawerData.question_type || 'multiple_choice');
                formData.set('question', this.drawerData.question || (document.getElementById('drawer_question_text')?.value || ''));
                formData.set('explanation', this.drawerData.explanation || (document.getElementById('drawer_question_explanation')?.value || ''));
                formData.set('score', this.drawerData.score || 10);
                if (this.drawerData.question_type === 'multiple_choice') {
                    if (this.drawerData.options) {
                        formData.set('options[A]', this.drawerData.options.A || '');
                        formData.set('options[B]', this.drawerData.options.B || '');
                        formData.set('options[C]', this.drawerData.options.C || '');
                        formData.set('options[D]', this.drawerData.options.D || '');
                    }
                    if (this.drawerData.correct_option) {
                        formData.set('correct_option', this.drawerData.correct_option);
                    }
                }
                if (this.drawerData.is_active) formData.set('is_active', '1'); else formData.delete('is_active');
            } else if (this.drawerType.includes('final_exam_section')) {
                formData.set('title', this.drawerData.title || '');
                formData.set('description', this.drawerData.description || (document.getElementById('drawer_exam_section_description')?.value || ''));
                formData.set('passing_grade', this.drawerData.passing_grade || 75);
                formData.set('grading_method', this.drawerData.grading_method || 'auto');
                if (this.drawerData.max_attempts) formData.set('max_attempts', this.drawerData.max_attempts); else formData.delete('max_attempts');
                if (this.drawerData.sort_order !== undefined) formData.set('sort_order', this.drawerData.sort_order);
                if (this.drawerData.is_active) formData.set('is_active', '1'); else formData.delete('is_active');
            } else if (this.drawerType.includes('final_exam_question')) {
                formData.set('question_type', this.drawerData.question_type || 'multiple_choice');
                formData.set('question', this.drawerData.question || (document.getElementById('drawer_exam_question_text')?.value || ''));
                formData.set('explanation', this.drawerData.explanation || (document.getElementById('drawer_exam_question_explanation')?.value || ''));
                formData.set('score', this.drawerData.score || 10);
                if (this.drawerData.question_type === 'multiple_choice') {
                    if (this.drawerData.options) {
                        formData.set('options[A]', this.drawerData.options.A || '');
                        formData.set('options[B]', this.drawerData.options.B || '');
                        formData.set('options[C]', this.drawerData.options.C || '');
                        formData.set('options[D]', this.drawerData.options.D || '');
                    }
                    if (this.drawerData.correct_option) {
                        formData.set('correct_option', this.drawerData.correct_option);
                    }
                }
                if (this.drawerData.is_active) formData.set('is_active', '1'); else formData.delete('is_active');
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
                        this.focusFirstErrorInput();
                        throw new Error('Validation Error');
                    });
                }
                if (!res.ok) throw new Error(`Server error (${res.status})`);
                return res.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    this.destroyRichTextEditors();
                    this.drawerOpen = false;
                    const targetNode = data.redirect_node || this.selectedParams;
                    this.refreshTree().then(() => {
                        this.selectNode(targetNode, true, true);
                    });
                    if (this.lastActiveTriggerEl && typeof this.lastActiveTriggerEl.focus === 'function') {
                        this.lastActiveTriggerEl.focus();
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

        // DELETE MODAL ACTIONS & FOCUS MANAGEMENT
        confirmDelete(type, id, title, deleteUrl, redirectNode = null) {
            this.lastActiveTriggerEl = document.activeElement;
            this.deleteModalTitle = type === 'level' ? 'Delete Course Level' : (type === 'module' ? 'Delete Module' : (type === 'material' ? 'Delete Material' : (type === 'practice' ? 'Delete Practice' : (type === 'final_exam_section' ? 'Delete Exam Section' : 'Delete Question'))));
            this.deleteModalItemTitle = title;
            this.deleteModalUrl = deleteUrl;
            this.deleteRedirectNode = redirectNode || { level: null, module: null, exam: null, tab: 'overview' };
            this.deleteModalDeleting = false;
            this.deleteModalError = null;
            this.deleteModalOpen = true;

            setTimeout(() => {
                const cancelBtn = document.getElementById('deleteModalCancelBtn');
                if (cancelBtn) cancelBtn.focus();
            }, 100);
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
                    const targetNode = data.redirect_node || this.deleteRedirectNode;
                    this.refreshTree().then(() => {
                        this.selectNode(targetNode, true, true);
                    });
                    if (this.lastActiveTriggerEl && typeof this.lastActiveTriggerEl.focus === 'function') {
                        this.lastActiveTriggerEl.focus();
                    }
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
}

if (window.Alpine) {
    registerCourseBuilder(window.Alpine);
} else {
    document.addEventListener('alpine:init', () => {
        if (window.Alpine) registerCourseBuilder(window.Alpine);
    });
}
