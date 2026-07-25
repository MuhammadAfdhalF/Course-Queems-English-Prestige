const initCourseBuilder = () => {
    if (!window.Alpine) return;

    window.Alpine.data('courseBuilder', (config = {}) => ({
        programId: config.programId || null,
        workspaceUrl: config.workspaceUrl || '',
        firstLevelId: config.firstLevelId || null,
        loading: false,
        error: null,
        workspaceHtml: '',
        mobileTreeOpen: false,
        expandedNodes: {},
        fetchSequenceToken: 0,
        selectedParams: {
            level: null,
            module: null,
            exam: null,
            tab: 'overview'
        },

        init() {
            this.loadExpandedState();
            this.readQueryParams();

            // Auto-expand first level by default if present
            if (this.firstLevelId && !this.selectedParams.level) {
                this.expandedNodes[`level_${this.firstLevelId}`] = true;
                this.saveExpandedState();
            }

            this.fetchWorkspace(false);

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
                if (stored) {
                    this.expandedNodes = JSON.parse(stored);
                } else {
                    this.expandedNodes = {};
                }
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

            // Prevent duplicate fetch / duplicate history push if selecting exact same node & tab
            if (
                String(newParams.level) === String(this.selectedParams.level) &&
                String(newParams.module) === String(this.selectedParams.module) &&
                String(newParams.exam) === String(this.selectedParams.exam) &&
                String(newParams.tab) === String(this.selectedParams.tab)
            ) {
                // If on mobile, just close tree drawer
                this.mobileTreeOpen = false;
                return;
            }

            // Automatically expand parent level & folders when selected
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
            this.mobileTreeOpen = false; // Close mobile tree drawer on selection

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

            // Increment sequence token to discard stale responses
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
                if (!res.ok) {
                    throw new Error(`Server returned status ${res.status}`);
                }
                return res.json();
            })
            .then(data => {
                if (currentToken !== this.fetchSequenceToken) return; // Discard stale response

                if (data.status === 'success') {
                    this.workspaceHtml = data.html;
                } else {
                    throw new Error(data.message || 'Failed to load workspace');
                }
            })
            .catch(err => {
                if (currentToken !== this.fetchSequenceToken) return; // Discard stale response
                this.error = err.message || 'Error connecting to server. Please try again.';
            })
            .finally(() => {
                if (currentToken === this.fetchSequenceToken) {
                    this.loading = false;
                }
            });
        },

        retryFetch() {
            this.fetchWorkspace();
        }
    }));
};

if (window.Alpine) {
    initCourseBuilder();
} else {
    document.addEventListener('alpine:init', initCourseBuilder);
}
