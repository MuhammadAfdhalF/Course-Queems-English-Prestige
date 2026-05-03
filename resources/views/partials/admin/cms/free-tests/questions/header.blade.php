<x-admin.page-toolbar
    :back-url="route('admin.cms.free-tests.index')"
    back-label="Back to Free Tests">
    <x-slot:actions>
        <button
            type="button"
            @click="createModalOpen = true"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
            <x-admin.icon name="plus" class="h-5 w-5" />
            <span>Add Question</span>
        </button>
    </x-slot:actions>
</x-admin.page-toolbar>

<x-admin.table-card class="p-5">
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                Selected Free Test
            </p>
            <h2 class="mt-1 text-xl font-bold text-slate-900">
                {{ $freeTest->title }}
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                {{ $freeTest->description ?? 'No description' }}
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">
                {{ $freeTest->duration_minutes ?? '-' }} minutes
            </span>

            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">
                Passing {{ $freeTest->passing_grade ?? '-' }}%
            </span>

            <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">
                {{ $questions->count() }} questions
            </span>
        </div>
    </div>
</x-admin.table-card>