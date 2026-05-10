<div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <form
        action="{{ route('admin.cms.free-test-results.index') }}"
        method="GET"
        class="grid gap-4 lg:grid-cols-[1fr_280px_auto_auto] lg:items-end">

        <div>
            <label for="search" class="mb-2 block text-sm font-bold text-slate-700">
                Search Participant
            </label>

            <input
                type="text"
                name="search"
                id="search"
                value="{{ $search }}"
                placeholder="Name, email, or WhatsApp..."
                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-[var(--color-brand-blue)] focus:ring-4 focus:ring-blue-50">
        </div>

        <div>
            <label for="free_test_id" class="mb-2 block text-sm font-bold text-slate-700">
                Filter by Test
            </label>

            <select
                name="free_test_id"
                id="free_test_id"
                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-[var(--color-brand-blue)] focus:ring-4 focus:ring-blue-50">
                <option value="">All Free Tests</option>

                @foreach ($freeTests as $freeTest)
                <option
                    value="{{ $freeTest->id }}"
                    @selected((string) $selectedFreeTestId===(string) $freeTest->id)>
                    {{ $freeTest->title }}
                </option>
                @endforeach
            </select>
        </div>

        <button
            type="submit"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
            <span>Apply Filter</span>
        </button>

        <a
            href="{{ route('admin.cms.free-test-results.index') }}"
            class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
            Reset
        </a>
    </form>
</div>