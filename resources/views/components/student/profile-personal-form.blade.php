<div class="overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-sm">
    <div class="flex items-center justify-between border-b border-slate-100 px-7 py-5">
        <h2 class="flex items-center gap-3 text-2xl font-bold text-slate-900">
            <span class="text-[var(--color-brand-blue)]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19a4 4 0 00-8 0m8 0H5m10 0h4M12 12a4 4 0 100-8 4 4 0 000 8z" />
                </svg>
            </span>
            Personal Information
        </h2>

        <span class="rounded-md border border-slate-200 px-3 py-1 text-xs font-bold uppercase tracking-[0.12em] text-slate-400">
            Step 01
        </span>
    </div>

    <div class="space-y-6 p-7">
        <div>
            <label class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">Full Name</label>
            <input value="Alex Thompson" class="h-13 w-full rounded-xl border border-slate-200 px-4 text-base font-semibold text-slate-700 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">Whatsapp Number</label>
                <div class="grid grid-cols-[80px_1fr] gap-3">
                    <input value="+44" class="h-13 rounded-xl border border-slate-200 px-4 text-base font-semibold text-slate-700 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">
                    <input value="7911 123456" class="h-13 rounded-xl border border-slate-200 px-4 text-base font-semibold text-slate-700 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>
            </div>

            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">Place of Birth</label>
                <input value="London, United Kingdom" class="h-13 w-full rounded-xl border border-slate-200 px-4 text-base font-semibold text-slate-700 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">
            </div>

            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">Date of Birth</label>
                <input value="05/14/1998" class="h-13 w-full rounded-xl border border-slate-200 px-4 text-base font-semibold text-slate-700 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">
            </div>

            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">Gender</label>
                <select class="h-13 w-full rounded-xl border border-slate-200 px-4 text-base font-semibold text-slate-700 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">
                    <option>Male</option>
                    <option>Female</option>
                </select>
            </div>
        </div>

        <div>
            <label class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">Mailing Address</label>
            <textarea rows="4" class="w-full resize-none rounded-xl border border-slate-200 px-4 py-4 text-base font-semibold text-slate-700 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">221B Baker Street, London, NW1 6XE, United Kingdom</textarea>
        </div>
    </div>

    <div class="flex justify-end gap-4 border-t border-slate-100 bg-slate-50 px-7 py-5">
        <button class="h-12 rounded-xl px-6 text-sm font-bold text-slate-500 hover:text-slate-700">
            Cancel
        </button>

        <button class="h-12 rounded-xl bg-[var(--color-brand-blue)] px-7 text-sm font-bold text-white shadow-md hover:opacity-95">
            Save Changes
        </button>
    </div>
</div>