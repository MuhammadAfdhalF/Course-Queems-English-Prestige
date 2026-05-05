<x-admin.page-toolbar
    :back-url="route('admin.course-management.programs.levels.index', $courseLevel->courseProgram)"
    back-label="Back to Course Levels">
    <x-slot:actions>
        <button
            type="button"
            @click="createModalOpen = true"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
            <x-admin.icon name="plus" class="h-5 w-5" />
            <span>Add Module</span>
        </button>
    </x-slot:actions>
</x-admin.page-toolbar>

<x-admin.table-card class="p-5">
    <div class="grid gap-4 md:grid-cols-[1fr_auto] md:items-center">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                Course Level
            </p>

            <h2 class="mt-1 text-xl font-bold text-slate-900">
                {{ $courseLevel->name }}
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                {{ $courseLevel->courseProgram->name }} — Manage modules under this course level.
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">
                {{ $modules->count() }} modules
            </span>

            @if ($courseLevel->is_active)
            <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">
                Active
            </span>
            @else
            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-500">
                Inactive
            </span>
            @endif
        </div>
    </div>
</x-admin.table-card>