<div class="flex flex-wrap items-center gap-3">
    <button
        type="button"
        @click="activeTab = 'all'"
        :class="activeTab === 'all'
            ? 'bg-[var(--color-brand-blue)] text-white border-[var(--color-brand-blue)] shadow-sm'
            : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300'"
        class="rounded-full border px-6 py-2.5 text-sm font-semibold transition-all duration-200">
        All
    </button>

    <button
        type="button"
        @click="activeTab = 'active'"
        :class="activeTab === 'active'
            ? 'bg-[var(--color-brand-blue)] text-white border-[var(--color-brand-blue)] shadow-sm'
            : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300'"
        class="rounded-full border px-6 py-2.5 text-sm font-semibold transition-all duration-200">
        Active
    </button>

    <button
        type="button"
        @click="activeTab = 'pending'"
        :class="activeTab === 'pending'
            ? 'bg-[var(--color-brand-blue)] text-white border-[var(--color-brand-blue)] shadow-sm'
            : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300'"
        class="rounded-full border px-6 py-2.5 text-sm font-semibold transition-all duration-200">
        Pending
    </button>

    <button
        type="button"
        @click="activeTab = 'completed'"
        :class="activeTab === 'completed'
            ? 'bg-[var(--color-brand-blue)] text-white border-[var(--color-brand-blue)] shadow-sm'
            : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300'"
        class="rounded-full border px-6 py-2.5 text-sm font-semibold transition-all duration-200">
        Completed
    </button>
</div>