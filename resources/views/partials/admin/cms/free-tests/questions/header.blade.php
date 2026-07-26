<x-admin.page-toolbar
    :back-url="route('admin.cms.free-tests.index')"
    back-label="Back to Free Tests">
    <x-slot:actions>
        @if (!($readiness['is_locked'] ?? false))
        <button
            type="button"
            @click="createModalOpen = true"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
            <x-admin.icon name="plus" class="h-5 w-5" />
            <span>Add Question</span>
        </button>
        @endif
    </x-slot:actions>
</x-admin.page-toolbar>

@if ($readiness['is_locked'] ?? false)
<div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-xs font-semibold text-amber-800">
    Questions cannot be changed because this free test already has student results.
</div>
@endif

<x-admin.table-card class="p-5 space-y-4">
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
            <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">
                {{ $questions->count() }} questions
            </span>
        </div>
    </div>

    {{-- Score Allocation Metric Cards --}}
    <div class="grid gap-4 sm:grid-cols-4 border-t border-slate-100 pt-4">
        <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-3 shadow-sm">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Score</span>
            <p class="mt-1 text-2xl font-black text-slate-800">{{ $readiness['total_score'] }}</p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-3 shadow-sm">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Allocated Score</span>
            <p class="mt-1 text-2xl font-black text-emerald-700">{{ $readiness['allocated_score'] }}</p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-3 shadow-sm">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Remaining Score</span>
            <p class="mt-1 text-2xl font-black {{ $readiness['remaining_score'] < 0 ? 'text-rose-600' : 'text-blue-700' }}">{{ $readiness['remaining_score'] }}</p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-3 shadow-sm">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Readiness Status</span>
            <p class="mt-1 text-xs font-black uppercase tracking-wider">
                @if ($readiness['status'] === 'ready')
                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-bold text-emerald-800">Ready to Activate</span>
                @elseif ($readiness['status'] === 'invalid_over_allocated')
                    <span class="inline-flex items-center gap-1 rounded-full bg-rose-100 px-2.5 py-1 text-xs font-bold text-rose-800">Over Allocated</span>
                @else
                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2.5 py-1 text-xs font-bold text-amber-800">Incomplete</span>
                @endif
            </p>
        </div>
    </div>
</x-admin.table-card>