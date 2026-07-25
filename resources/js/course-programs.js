const initCourseProgramsAdmin = () => {
    if (!window.Alpine) return;

    window.Alpine.data('courseProgramsAdmin', (config = {}) => ({
        createModalOpen: !!(config.errorsAny && config.formType === 'create'),
        editModalOpen: !!(config.errorsAny && config.formType === 'edit'),
        deleteModalOpen: false,
        reorderUrl: config.reorderUrl || '',
        searchQuery: '',
        statusFilter: 'all',
        originalList: JSON.parse(JSON.stringify(config.programs || [])),
        programsList: JSON.parse(JSON.stringify(config.programs || [])),
        draggedIndex: null,
        isOrderChanged: false,
        selectedProgram: null,
        selectedItem: { title: '', delete_url: '#' },

        get filteredPrograms() {
            return this.programsList.filter(program => {
                const query = (this.searchQuery || '').trim().toLowerCase();
                const nameMatch = (program.name || '').toLowerCase().includes(query);
                const slugMatch = (program.slug || '').toLowerCase().includes(query);
                const matchesSearch = !query || nameMatch || slugMatch;

                const isActive = Boolean(program.is_active && program.is_active !== '0' && program.is_active !== 0);
                let matchesStatus = true;

                if (this.statusFilter === 'active') {
                    matchesStatus = isActive;
                } else if (this.statusFilter === 'inactive') {
                    matchesStatus = !isActive;
                }

                return matchesSearch && matchesStatus;
            });
        },

        get isReorderEnabled() {
            return (this.searchQuery || '').trim() === '' && this.statusFilter === 'all';
        },

        onDragStart(index, event) {
            if (!this.isReorderEnabled) return;
            this.draggedIndex = index;
            if (event && event.dataTransfer) {
                event.dataTransfer.effectAllowed = 'move';
            }
        },

        onDrop(dropIndex) {
            if (!this.isReorderEnabled || this.draggedIndex === null || this.draggedIndex === dropIndex) return;
            this.moveProgram(this.draggedIndex, dropIndex);
            this.draggedIndex = null;
        },

        moveProgram(fromIndex, toIndex) {
            if (!this.isReorderEnabled) return;
            if (toIndex < 0 || toIndex >= this.programsList.length) return;
            const item = this.programsList.splice(fromIndex, 1)[0];
            this.programsList.splice(toIndex, 0, item);
            this.isOrderChanged = true;
        },

        discardOrder() {
            this.programsList = JSON.parse(JSON.stringify(this.originalList));
            this.isOrderChanged = false;
        },

        saveOrder() {
            if (!this.reorderUrl || !this.isOrderChanged) return;
            const orderIds = this.programsList.map(prog => prog.id);

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = this.reorderUrl;

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (csrfToken) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);
            }

            orderIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'order[]';
                input.value = id;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        },

        openEditModal(item) {
            this.selectedProgram = item;
            this.editModalOpen = true;
        },

        openDeleteModal(item) {
            this.selectedItem = item;
            this.deleteModalOpen = true;
        }
    }));
};

if (window.Alpine) {
    initCourseProgramsAdmin();
} else {
    document.addEventListener('alpine:init', initCourseProgramsAdmin);
}
