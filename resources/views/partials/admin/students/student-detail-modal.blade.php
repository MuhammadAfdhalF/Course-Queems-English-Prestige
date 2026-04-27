<x-admin.modal
    model="studentDetailModalOpen"
    title="Student Details"
    subtitle=""
    size="lg">
    <template x-if="selectedStudent">
        <div class="-mx-6 -my-6">
            {{-- Header inside modal --}}
            <div class="border-b border-slate-200 px-6 py-5">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <div class="flex flex-wrap items-center gap-3">
                            <h2 class="text-2xl font-extrabold text-slate-900">
                                Student Details
                            </h2>

                            <span
                                class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-[11px] font-extrabold uppercase tracking-[0.12em] text-emerald-600">
                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                <span x-text="selectedStudent.statusLabel"></span>
                            </span>
                        </div>

                        <p class="mt-1 text-sm font-medium text-slate-500">
                            <span x-text="selectedStudent.name"></span>
                            <span class="mx-1">•</span>
                            <span>ID:</span>
                            <span x-text="selectedStudent.id"></span>
                        </p>
                    </div>
                </div>

                {{-- Tabs --}}
                <div class="mt-5 flex gap-6 border-b border-slate-200">
                    <button
                        type="button"
                        @click="activeDetailTab = 'profile'"
                        class="relative pb-3 text-sm font-extrabold transition"
                        :class="activeDetailTab === 'profile' ? 'text-[var(--color-brand-blue)]' : 'text-slate-400 hover:text-slate-600'">
                        Profile
                        <span
                            x-show="activeDetailTab === 'profile'"
                            class="absolute bottom-[-1px] left-0 h-[3px] w-full rounded-full bg-[var(--color-brand-blue)]">
                        </span>
                    </button>

                    <button
                        type="button"
                        @click="activeDetailTab = 'enrollments'"
                        class="relative pb-3 text-sm font-extrabold transition"
                        :class="activeDetailTab === 'enrollments' ? 'text-[var(--color-brand-blue)]' : 'text-slate-400 hover:text-slate-600'">
                        Enrollments
                        <span
                            x-show="activeDetailTab === 'enrollments'"
                            class="absolute bottom-[-1px] left-0 h-[3px] w-full rounded-full bg-[var(--color-brand-blue)]">
                        </span>
                    </button>

                    <button
                        type="button"
                        @click="activeDetailTab = 'security'"
                        class="relative pb-3 text-sm font-extrabold transition"
                        :class="activeDetailTab === 'security' ? 'text-[var(--color-brand-blue)]' : 'text-slate-400 hover:text-slate-600'">
                        Security
                        <span
                            x-show="activeDetailTab === 'security'"
                            class="absolute bottom-[-1px] left-0 h-[3px] w-full rounded-full bg-[var(--color-brand-blue)]">
                        </span>
                    </button>
                </div>
            </div>

            {{-- Content --}}
            <div class="px-6 py-6">
                <div x-show="activeDetailTab === 'profile'" x-transition.opacity>
                    @include('partials.admin.students.detail.profile-tab')
                </div>

                <div x-show="activeDetailTab === 'enrollments'" x-transition.opacity>
                    @include('partials.admin.students.detail.enrollments-tab')
                </div>

                <div x-show="activeDetailTab === 'security'" x-transition.opacity>
                    @include('partials.admin.students.detail.security-tab')
                </div>
            </div>
        </div>
    </template>

    <x-slot:footer>
        <button
            type="button"
            class="mr-auto inline-flex h-11 items-center justify-center gap-2 rounded-xl px-5 text-sm font-extrabold text-rose-600 transition hover:bg-rose-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M18 6L6 18M6 6l12 12" />
            </svg>
            Deactivate Student
        </button>

        <button
            type="button"
            @click="studentDetailModalOpen = false"
            class="h-11 rounded-xl border border-slate-200 bg-white px-6 text-sm font-extrabold text-slate-600 transition hover:bg-slate-50">
            Close
        </button>

        <button
            type="button"
            @click="alert('Student data saved.'); studentDetailModalOpen = false"
            class="h-11 rounded-xl bg-[var(--color-brand-blue)] px-6 text-sm font-extrabold text-white shadow-md transition hover:opacity-95">
            Save Changes
        </button>
    </x-slot:footer>
</x-admin.modal>