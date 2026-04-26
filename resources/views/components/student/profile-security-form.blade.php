<div class="overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-100 px-7 py-5">
        <h2 class="flex items-center gap-3 text-2xl font-bold text-slate-900">
            <span class="text-[var(--color-brand-blue)]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3l7 4v5c0 5-3.5 8-7 9-3.5-1-7-4-7-9V7l7-4z" />
                </svg>
            </span>
            Security & Password
        </h2>
    </div>

    <div class="space-y-6 p-7">
        <div class="rounded-xl border border-blue-100 bg-blue-50 px-5 py-4 text-sm font-medium leading-7 text-[var(--color-brand-blue)]">
            Ensure your new password is at least 8 characters long and includes a mix of numbers and letters for better security.
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">Current Password</label>
                <input type="password" value="password" class="h-13 w-full rounded-xl border border-slate-200 px-4 text-base font-semibold text-slate-700 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">
            </div>

            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">New Password</label>
                <input type="password" value="password" class="h-13 w-full rounded-xl border border-slate-200 px-4 text-base font-semibold text-slate-700 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">
            </div>

            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">Confirm New Password</label>
                <input type="password" value="password" class="h-13 w-full rounded-xl border border-slate-200 px-4 text-base font-semibold text-slate-700 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">
            </div>
        </div>

        <div class="flex justify-end">
            <button class="inline-flex h-13 items-center justify-center gap-2 rounded-xl bg-slate-900 px-7 text-sm font-bold text-white shadow-md transition hover:bg-slate-800">
                Update Password
            </button>
        </div>
    </div>
</div>