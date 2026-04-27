<div class="space-y-5">
    <div>
        <h3 class="text-lg font-extrabold text-slate-900">
            Security & Account Access
        </h3>

        <p class="mt-1 text-sm font-medium text-slate-500">
            Manage login credentials, account access, and security actions for this student.
        </p>
    </div>

    {{-- ACCOUNT STATUS --}}
    <div class="grid gap-4 sm:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-white p-4">
            <p class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-slate-400">
                Account Status
            </p>

            <div class="mt-3">
                <span
                    class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-extrabold"
                    :class="selectedStudent.status === 'active'
                        ? 'bg-blue-50 text-[var(--color-brand-blue)]'
                        : 'bg-slate-100 text-slate-500'">
                    <span
                        class="h-2 w-2 rounded-full"
                        :class="selectedStudent.status === 'active' ? 'bg-[var(--color-brand-gold)]' : 'bg-slate-400'">
                    </span>
                    <span x-text="selectedStudent.statusLabel"></span>
                </span>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4">
            <p class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-slate-400">
                Last Login
            </p>

            <p class="mt-3 text-sm font-extrabold text-slate-900">
                Today, 09:42 AM
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4">
            <p class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-slate-400">
                Login Method
            </p>

            <p class="mt-3 text-sm font-extrabold text-slate-900">
                Email & Password
            </p>
        </div>
    </div>

    {{-- SECURITY NOTICE --}}
    <div class="rounded-2xl border border-blue-100 bg-blue-50 p-5">
        <div class="flex items-start gap-3">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-[var(--color-brand-blue)] shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <circle cx="12" cy="12" r="9" stroke-width="1.8" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4m0 4h.01" />
                </svg>
            </span>

            <div>
                <h4 class="text-sm font-extrabold text-[var(--color-brand-blue)]">
                    Password reset guidance
                </h4>

                <p class="mt-1 text-sm leading-6 text-blue-800/80">
                    Use a temporary password only when requested by the student. Ask them to change it after the next login.
                </p>
            </div>
        </div>
    </div>

    {{-- RESET PASSWORD FORM --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-5">
        <div class="flex flex-col gap-2 border-b border-slate-200 pb-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h4 class="text-base font-extrabold text-slate-900">
                    Reset Student Password
                </h4>

                <p class="mt-1 text-sm text-slate-500">
                    Create a temporary password for this student account.
                </p>
            </div>

            <span class="inline-flex w-fit rounded-full bg-yellow-50 px-3 py-1 text-[11px] font-extrabold uppercase tracking-[0.12em] text-[var(--color-brand-gold)] ring-1 ring-yellow-100">
                Admin Only
            </span>
        </div>

        <div class="mt-5 grid gap-5 sm:grid-cols-2">
            <div>
                <label class="mb-2 block text-xs font-extrabold uppercase tracking-[0.14em] text-slate-500">
                    New Temporary Password
                </label>

                <div class="flex h-12 items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 transition focus-within:border-[var(--color-brand-blue)] focus-within:ring-2 focus-within:ring-blue-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 9V7a5 5 0 00-10 0v2m-2 0h14l-1 10H6L5 9zm4 4h6" />
                    </svg>

                    <input
                        type="password"
                        placeholder="••••••••"
                        class="w-full bg-transparent text-sm font-bold text-slate-700 outline-none placeholder:text-slate-400">
                </div>
            </div>

            <div>
                <label class="mb-2 block text-xs font-extrabold uppercase tracking-[0.14em] text-slate-500">
                    Confirm Password
                </label>

                <div class="flex h-12 items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 transition focus-within:border-[var(--color-brand-blue)] focus-within:ring-2 focus-within:ring-blue-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3l7 4v5c0 5-3.5 8-7 9-3.5-1-7-4-7-9V7l7-4z" />
                    </svg>

                    <input
                        type="password"
                        placeholder="••••••••"
                        class="w-full bg-transparent text-sm font-bold text-slate-700 outline-none placeholder:text-slate-400">
                </div>
            </div>
        </div>

        <div class="mt-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
            <button
                type="button"
                class="inline-flex h-11 items-center justify-center rounded-xl border border-slate-200 bg-white px-5 text-sm font-extrabold text-slate-600 transition hover:bg-slate-50">
                Generate Password
            </button>

            <button
                type="button"
                @click="alert('Temporary password updated.')"
                class="inline-flex h-11 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 text-sm font-extrabold text-white shadow-md transition hover:opacity-95">
                Update Password
            </button>
        </div>
    </div>

    {{-- SECURITY ACTIONS --}}
    <div class="grid gap-4 sm:grid-cols-2">
        <button
            type="button"
            class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-white p-5 text-left transition hover:border-blue-200 hover:bg-blue-50/40">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-[var(--color-brand-blue)]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8m-18 8h18V8H3v8z" />
                </svg>
            </span>

            <span>
                <span class="block text-sm font-extrabold text-slate-900">
                    Send Login Instructions
                </span>

                <span class="mt-1 block text-sm leading-6 text-slate-500">
                    Send account access guide to student email.
                </span>
            </span>
        </button>

        <button
            type="button"
            class="flex items-start gap-3 rounded-2xl border border-rose-100 bg-white p-5 text-left transition hover:bg-rose-50">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-rose-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M18 6L6 18M6 6l12 12" />
                </svg>
            </span>

            <span>
                <span class="block text-sm font-extrabold text-rose-600">
                    Force Logout
                </span>

                <span class="mt-1 block text-sm leading-6 text-slate-500">
                    End all active sessions for this student.
                </span>
            </span>
        </button>
    </div>
</div>