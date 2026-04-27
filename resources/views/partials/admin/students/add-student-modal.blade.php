<x-admin.modal
    model="addStudentModalOpen"
    title="Add Student"
    subtitle="Enter the details to register a new student in the system."
    size="md">
    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label class="mb-2 block text-xs font-extrabold uppercase tracking-[0.14em] text-slate-500">
                Full Name
            </label>

            <div class="flex h-12 items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 transition focus-within:border-[var(--color-brand-blue)] focus-within:ring-2 focus-within:ring-blue-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.75 7.5a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a7.5 7.5 0 0115 0" />
                </svg>

                <input
                    type="text"
                    placeholder="John Doe"
                    class="w-full bg-transparent text-sm font-semibold text-slate-700 outline-none placeholder:text-slate-400">
            </div>
        </div>

        <div>
            <label class="mb-2 block text-xs font-extrabold uppercase tracking-[0.14em] text-slate-500">
                Email Address
            </label>

            <div class="flex h-12 items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 transition focus-within:border-[var(--color-brand-blue)] focus-within:ring-2 focus-within:ring-blue-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8m-18 8h18V8H3v8z" />
                </svg>

                <input
                    type="email"
                    placeholder="john@example.com"
                    class="w-full bg-transparent text-sm font-semibold text-slate-700 outline-none placeholder:text-slate-400">
            </div>
        </div>

        <div>
            <label class="mb-2 block text-xs font-extrabold uppercase tracking-[0.14em] text-slate-500">
                WhatsApp Number
            </label>

            <div class="flex h-12 items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 transition focus-within:border-[var(--color-brand-blue)] focus-within:ring-2 focus-within:ring-blue-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 5a2 2 0 012-2h3.28a2 2 0 011.95 1.56l.57 2.3a2 2 0 01-.58 1.95l-1.27 1.27a16 16 0 006.36 6.36l1.27-1.27a2 2 0 011.95-.58l2.3.57A2 2 0 0121 15.72V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>

                <input
                    type="text"
                    placeholder="+1 (555) 000-0000"
                    class="w-full bg-transparent text-sm font-semibold text-slate-700 outline-none placeholder:text-slate-400">
            </div>
        </div>

        <div>
            <label class="mb-2 block text-xs font-extrabold uppercase tracking-[0.14em] text-slate-500">
                Initial Password
            </label>

            <div class="flex h-12 items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 transition focus-within:border-[var(--color-brand-blue)] focus-within:ring-2 focus-within:ring-blue-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 9V7a5 5 0 00-10 0v2m-2 0h14l-1 10H6L5 9zm4 4h6" />
                </svg>

                <input
                    type="password"
                    placeholder="••••••••"
                    class="w-full bg-transparent text-sm font-semibold text-slate-700 outline-none placeholder:text-slate-400">

                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 5c-5 0-9 3.5-9 7s4 7 9 7 9-3.5 9-7-4-7-9-7z" />
                    <circle cx="12" cy="12" r="3" stroke-width="1.8" />
                </svg>
            </div>
        </div>

        <div>
            <label class="mb-2 block text-xs font-extrabold uppercase tracking-[0.14em] text-slate-500">
                Gender
            </label>

            <select class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm font-semibold text-slate-500 outline-none transition focus:border-[var(--color-brand-blue)] focus:ring-2 focus:ring-blue-100">
                <option>Select Gender</option>
                <option>Male</option>
                <option>Female</option>
            </select>
        </div>

        <div>
            <label class="mb-2 block text-xs font-extrabold uppercase tracking-[0.14em] text-slate-500">
                Profession
            </label>

            <div class="flex h-12 items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 transition focus-within:border-[var(--color-brand-blue)] focus-within:ring-2 focus-within:ring-blue-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <rect x="4" y="6" width="16" height="14" rx="2" stroke-width="1.8" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 6V4h6v2" />
                </svg>

                <input
                    type="text"
                    placeholder="Software Engineer"
                    class="w-full bg-transparent text-sm font-semibold text-slate-700 outline-none placeholder:text-slate-400">
            </div>
        </div>

        <div class="sm:col-span-2">
            <label class="mb-2 block text-xs font-extrabold uppercase tracking-[0.14em] text-slate-500">
                Institution / University
            </label>

            <div class="flex h-12 items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 transition focus-within:border-[var(--color-brand-blue)] focus-within:ring-2 focus-within:ring-blue-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3l9 5-9 5-9-5 9-5z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 10v5c0 2 3 4 7 4s7-2 7-4v-5" />
                </svg>

                <input
                    type="text"
                    placeholder="Harvard University"
                    class="w-full bg-transparent text-sm font-semibold text-slate-700 outline-none placeholder:text-slate-400">
            </div>
        </div>

        <div class="sm:col-span-2">
            <label class="mb-2 block text-xs font-extrabold uppercase tracking-[0.14em] text-slate-500">
                Address
            </label>

            <textarea
                rows="4"
                placeholder="123 Street Name, City, Country"
                class="w-full resize-none rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-[var(--color-brand-blue)] focus:ring-2 focus:ring-blue-100"></textarea>
        </div>
    </div>

    <x-slot:footer>
        <button
            type="button"
            @click="addStudentModalOpen = false"
            class="h-11 rounded-xl px-6 text-sm font-extrabold text-slate-500 transition hover:text-slate-700">
            Cancel
        </button>

        <button
            type="button"
            @click="alert('Student created successfully.'); addStudentModalOpen = false"
            class="h-11 rounded-xl bg-[var(--color-brand-blue)] px-6 text-sm font-extrabold text-white shadow-md transition hover:opacity-95">
            Create Student
        </button>
    </x-slot:footer>
</x-admin.modal>