<div class="space-y-5">
    <div>
        <h3 class="text-lg font-extrabold text-slate-900">
            Enrolled Courses
            <span class="ml-2 text-xs font-bold text-slate-400">(Read-only)</span>
        </h3>

        <p class="mt-1 flex items-center gap-2 text-sm font-medium text-slate-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <circle cx="12" cy="12" r="9" stroke-width="1.8" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4m0 4h.01" />
            </svg>
            Enrollments are granted automatically from approved orders.
        </p>
    </div>

    <div class="grid gap-4 sm:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-white p-4">
            <p class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-slate-400">
                Total Enrolled
            </p>
            <p class="mt-2 text-2xl font-extrabold text-slate-900">
                03
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4">
            <p class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-slate-400">
                Active
            </p>
            <p class="mt-2 text-2xl font-extrabold text-[var(--color-brand-blue)]">
                02
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4">
            <p class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-slate-400">
                Completed
            </p>
            <p class="mt-2 text-2xl font-extrabold text-slate-900">
                01
            </p>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left">
                <thead class="border-b border-slate-200 bg-slate-50">
                    <tr class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-slate-400">
                        <th class="px-4 py-3">Course & Order ID</th>
                        <th class="px-4 py-3">Level</th>
                        <th class="px-4 py-3">Enrollment Date</th>
                        <th class="px-4 py-3">Status & Progress</th>
                        <th class="px-4 py-3">Certificate</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    <tr class="transition hover:bg-slate-50">
                        <td class="px-4 py-4">
                            <p class="text-sm font-extrabold leading-snug text-slate-900">
                                Prestige English Masterclass
                            </p>
                            <p class="mt-1 text-[11px] font-bold text-slate-400">
                                Order: #QEP-000125
                            </p>
                        </td>

                        <td class="whitespace-nowrap px-4 py-4 text-sm font-bold text-slate-600">
                            C1 Advanced
                        </td>

                        <td class="whitespace-nowrap px-4 py-4 text-sm font-bold text-slate-500">
                            Jan 12, 2024
                        </td>

                        <td class="px-4 py-4">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex rounded-md border border-slate-300 bg-white px-2 py-0.5 text-[10px] font-extrabold uppercase text-slate-700">
                                    Active
                                </span>
                            </div>

                            <div class="mt-2 flex items-center gap-2">
                                <div class="h-1.5 w-24 overflow-hidden rounded-full bg-slate-200">
                                    <div class="h-full w-[66%] rounded-full bg-[var(--color-brand-blue)]"></div>
                                </div>
                                <span class="text-[11px] font-bold text-slate-400">
                                    4/6 modules
                                </span>
                            </div>
                        </td>

                        <td class="whitespace-nowrap px-4 py-4">
                            <span class="inline-flex items-center gap-1 rounded-md bg-yellow-50 px-2 py-1 text-[10px] font-extrabold uppercase tracking-[0.08em] text-[var(--color-brand-gold)] ring-1 ring-yellow-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 9V7a5 5 0 00-10 0v2m-2 0h14l-1 10H6L5 9z" />
                                </svg>
                                Locked
                            </span>
                        </td>

                        <td class="whitespace-nowrap px-4 py-4 text-right">
                            <div class="inline-flex items-center gap-2">
                                <button
                                    type="button"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-[var(--color-brand-blue)] transition hover:bg-blue-50"
                                    title="View course">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 5c-5 0-9 3.5-9 7s4 7 9 7 9-3.5 9-7-4-7-9-7z" />
                                        <circle cx="12" cy="12" r="3" stroke-width="1.8" />
                                    </svg>
                                </button>

                                <button
                                    type="button"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-50 hover:text-slate-600"
                                    title="Chat student">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-4l-4 4v-4z" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr class="transition hover:bg-slate-50">
                        <td class="px-4 py-4">
                            <p class="text-sm font-extrabold leading-snug text-slate-900">
                                Business English Foundations
                            </p>
                            <p class="mt-1 text-[11px] font-bold text-slate-400">
                                Order: #QEP-000089
                            </p>
                        </td>

                        <td class="whitespace-nowrap px-4 py-4 text-sm font-bold text-slate-600">
                            B2 Intermediate
                        </td>

                        <td class="whitespace-nowrap px-4 py-4 text-sm font-bold text-slate-500">
                            Dec 05, 2023
                        </td>

                        <td class="px-4 py-4">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex rounded-md bg-slate-900 px-2 py-0.5 text-[10px] font-extrabold uppercase text-white">
                                    Completed
                                </span>
                            </div>

                            <div class="mt-2 flex items-center gap-2">
                                <div class="h-1.5 w-24 overflow-hidden rounded-full bg-slate-200">
                                    <div class="h-full w-full rounded-full bg-emerald-500"></div>
                                </div>
                                <span class="text-[11px] font-bold uppercase text-emerald-600">
                                    10/10 modules
                                </span>
                            </div>
                        </td>

                        <td class="whitespace-nowrap px-4 py-4">
                            <span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-[10px] font-extrabold uppercase tracking-[0.08em] text-emerald-600 ring-1 ring-emerald-100">
                                Available
                            </span>
                        </td>

                        <td class="whitespace-nowrap px-4 py-4 text-right">
                            <div class="inline-flex items-center gap-2">
                                <button
                                    type="button"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-[var(--color-brand-blue)] transition hover:bg-blue-50"
                                    title="View course">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 5c-5 0-9 3.5-9 7s4 7 9 7 9-3.5 9-7-4-7-9-7z" />
                                        <circle cx="12" cy="12" r="3" stroke-width="1.8" />
                                    </svg>
                                </button>

                                <button
                                    type="button"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-[var(--color-brand-blue)] transition hover:bg-blue-50"
                                    title="Download certificate">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <rect x="5" y="3" width="14" height="18" rx="2" stroke-width="1.8" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7h8M8 11h8M8 15h5" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr class="transition hover:bg-slate-50">
                        <td class="px-4 py-4">
                            <p class="text-sm font-extrabold leading-snug text-slate-900">
                                Academic IELTS Prep
                            </p>
                            <p class="mt-1 text-[11px] font-bold text-slate-400">
                                Order: #QEP-000145
                            </p>
                        </td>

                        <td class="whitespace-nowrap px-4 py-4 text-sm font-bold text-slate-600">
                            Test Prep
                        </td>

                        <td class="whitespace-nowrap px-4 py-4 text-sm font-bold text-slate-500">
                            Feb 20, 2024
                        </td>

                        <td class="px-4 py-4">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex rounded-md border border-slate-300 bg-white px-2 py-0.5 text-[10px] font-extrabold uppercase text-slate-700">
                                    Active
                                </span>
                            </div>

                            <div class="mt-2 flex items-center gap-2">
                                <div class="h-1.5 w-24 overflow-hidden rounded-full bg-slate-200">
                                    <div class="h-full w-[8%] rounded-full bg-[var(--color-brand-blue)]"></div>
                                </div>
                                <span class="text-[11px] font-bold text-slate-400">
                                    1/12 modules
                                </span>
                            </div>
                        </td>

                        <td class="whitespace-nowrap px-4 py-4">
                            <span class="inline-flex rounded-md bg-slate-100 px-2 py-1 text-[10px] font-extrabold uppercase tracking-[0.08em] text-slate-400">
                                Not Eligible
                            </span>
                        </td>

                        <td class="whitespace-nowrap px-4 py-4 text-right">
                            <div class="inline-flex items-center gap-2">
                                <button
                                    type="button"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-[var(--color-brand-blue)] transition hover:bg-blue-50"
                                    title="View course">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 5c-5 0-9 3.5-9 7s4 7 9 7 9-3.5 9-7-4-7-9-7z" />
                                        <circle cx="12" cy="12" r="3" stroke-width="1.8" />
                                    </svg>
                                </button>

                                <button
                                    type="button"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-50 hover:text-slate-600"
                                    title="Chat student">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-4l-4 4v-4z" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>