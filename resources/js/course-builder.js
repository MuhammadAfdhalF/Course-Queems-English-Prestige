const initCourseBuilder = () => {
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
        drawerType: null, // 'create_level', 'edit_level', 'create_module', 'edit_module', 'create_material', 'edit_material', 'create_practice', 'edit_practice', 'create_question', 'edit_question'
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
                tab: urlParams.get('tab') || 'overview',
                page: urlParams.get('page') || 1
            };
        },

        selectNode(params = {}, updateUrl = true) {
            const newParams = {
                level: params.level !== undefined ? params.level : this.selectedParams.level,
                module: params.module !== undefined ? params.module : null,
                exam: params.exam !== undefined ? params.exam : null,
                tab: params.tab || 'overview',
                page: params.page || 1
            };

            if (
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
                        sort_order: d.sort_order,
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
                        sort_order: d.sort_order,
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

        openCreatePracticeDrawer(storeUrl) {
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
        },

        openEditPracticeDrawer(editUrl) {
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
        },

        openEditQuestionDrawer(editUrl) {
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

        isDrawerDirty() {
            if (!this.drawerOpen || this.drawerSaving || this.drawerLoading) return false;
            if (this.drawerType === 'create_level' && (this.drawerData.name || this.drawerData.short_description)) return true;
            if (this.drawerType === 'create_module' && (this.drawerData.title || this.drawerData.short_description)) return true;
            if (this.drawerType === 'create_material' && (this.drawerData.title || this.drawerData.content)) return true;
            if (this.drawerType === 'create_practice' && (this.drawerData.title || this.drawerData.description)) return true;
            if (this.drawerType === 'create_question' && (this.drawerData.question)) return true;
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
                formData.set('description', this.drawerData.description || '');
                formData.set('passing_grade', this.drawerData.passing_grade || 70);
                formData.set('grading_method', this.drawerData.grading_method || 'auto');
                if (this.drawerData.max_attempts) formData.set('max_attempts', this.drawerData.max_attempts); else formData.delete('max_attempts');
                if (this.drawerData.is_required) formData.set('is_required', '1'); else formData.delete('is_required');
                if (this.drawerData.is_active) formData.set('is_active', '1'); else formData.delete('is_active');
            } else if (this.drawerType.includes('question')) {
                formData.set('question_type', this.drawerData.question_type || 'multiple_choice');
                formData.set('question', this.drawerData.question || (document.getElementById('drawer_question_text')?.value || ''));
                formData.set('explanation', this.drawerData.explanation || '');
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
            this.deleteModalTitle = type === 'level' ? 'Delete Course Level' : (type === 'module' ? 'Delete Module' : (type === 'material' ? 'Delete Material' : (type === 'practice' ? 'Delete Practice' : 'Delete Question')));
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
    initCourseBuilder();
} else {
    document.addEventListener('alpine:init', initCourseBuilder);
}
