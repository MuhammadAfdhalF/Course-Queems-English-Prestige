<x-admin.page-toolbar
    :back-url="route('admin.course-management.programs.index')"
    back-label="Back to Course Programs">
    <x-slot:actions>
        <a
            href="{{ route('admin.course-management.programs.levels.create', $courseProgram) }}"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
            <x-admin.icon name="plus" class="h-5 w-5" />
            <span>Add Course Level</span>
        </a>
    </x-slot:actions>
</x-admin.page-toolbar>

<x-admin.table-card class="p-5">
    <div>
        <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
            Course Program
        </p>

        <h2 class="mt-1 text-xl font-bold text-slate-900">
            {{ $courseProgram->name }}
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Manage all course levels under this program.
        </p>
    </div>
</x-admin.table-card>