@extends('layouts.admin', [
    'pageTitle' => 'Course Programs',
    'pageSubtitle' => 'Manage and organize all course programs',
])

@php
$programsData = $coursePrograms->map(function($prog) {
    return [
        'id' => $prog->id,
        'name' => $prog->name,
        'slug' => $prog->slug,
        'sort_order' => $prog->sort_order,
        'is_active' => (bool) $prog->is_active,
        'course_levels_count' => $prog->course_levels_count,
        'modules_count' => $prog->courseLevels->sum('modules_count'),
        'final_exams_count' => $prog->courseLevels->sum('final_exams_count'),
        'builder_url' => route('admin.course-management.programs.builder', $prog->id),
        'levels_url' => route('admin.course-management.programs.levels.index', $prog->id),
        'update_url' => route('admin.course-management.programs.update', $prog->id),
        'delete_url' => route('admin.course-management.programs.destroy', $prog->id),
    ];
})->values();
@endphp

@section('content')
<script>
    const registerCourseProgramsAdmin = () => {
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
        registerCourseProgramsAdmin();
    } else {
        document.addEventListener('alpine:init', registerCourseProgramsAdmin);
    }
</script>

<section
    x-data="courseProgramsAdmin({
        errorsAny: {{ $errors->any() ? 'true' : 'false' }},
        formType: '{{ old('_form_type') }}',
        reorderUrl: '{{ route('admin.course-management.programs.reorder') }}',
        programs: @js($programsData)
    })"
    class="mx-auto max-w-7xl space-y-6">

    @include('partials.admin.course-management.programs.header')

    <x-admin.flash-message />

    @include('partials.admin.course-management.programs.card-list')
    @include('partials.admin.course-management.programs.create-modal')
    @include('partials.admin.course-management.programs.edit-modal')

    <x-admin.confirm-delete-modal
        model="deleteModalOpen"
        title="Delete Course Program"
        subtitle="This action cannot be undone."
        item-name="selectedItem.title"
        form-id="deleteCourseProgramForm"
        form-action="selectedItem.delete_url"
        message="Are you sure you want to delete this course program?" />
</section>
@endsection
