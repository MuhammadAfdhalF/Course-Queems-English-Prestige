<x-admin.page-toolbar
    :back-url="route('admin.course-management.levels.modules.index', $module->courseLevel)"
    back-label="Back to Modules">
    <x-slot:actions>
        <a
            href="{{ route('admin.course-management.modules.materials.create', $module) }}"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
            <x-admin.icon name="plus" class="h-5 w-5" />
            <span>Add Material</span>
        </a>
    </x-slot:actions>
</x-admin.page-toolbar>

<x-admin.table-card class="p-5">
    <div class="grid gap-4 md:grid-cols-[1fr_auto] md:items-center">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                Module
            </p>

            <h2 class="mt-1 text-xl font-bold text-slate-900">
                {{ $module->title }}
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                {{ $module->courseLevel->courseProgram->name }}
                —
                {{ $module->courseLevel->name }}
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">
                {{ $materials->count() }} materials
            </span>

            @if ($module->is_active)
            <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">
                Active Module
            </span>
            @else
            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-500">
                Inactive Module
            </span>
            @endif
        </div>
    </div>
</x-admin.table-card>