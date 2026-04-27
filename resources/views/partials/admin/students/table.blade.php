<x-admin.data-table>
    <x-slot:head>
        <th class="px-6 py-4">Student Name</th>
        <th class="px-6 py-4">Email Address</th>
        <th class="px-6 py-4">WhatsApp</th>
        <th class="px-6 py-4">Reg. Date</th>
        <th class="px-6 py-4">Courses</th>
        <th class="px-6 py-4">Status</th>
        <th class="px-6 py-4 text-right">Action</th>
    </x-slot:head>

    <template x-for="student in students" :key="student.id">
        <tr
            x-show="matches(student)"
            x-transition.opacity.duration.200ms
            class="transition hover:bg-slate-50"
        >
            <td class="px-6 py-5">
                <div class="flex items-center gap-3">
                    <template x-if="student.image">
                        <img
                            :src="student.image"
                            :alt="student.name"
                            class="h-11 w-11 shrink-0 rounded-xl object-cover">
                    </template>

                    <template x-if="!student.image">
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl text-sm font-extrabold"
                            :class="student.avatarColor"
                            x-text="student.initials">
                        </div>
                    </template>

                    <div>
                        <p class="text-base font-extrabold leading-tight text-slate-900" x-text="student.name"></p>

                        <p
                            class="mt-1 text-[11px] font-extrabold uppercase tracking-[0.12em]"
                            :class="student.memberType.toLowerCase().includes('premium') ? 'text-[var(--color-brand-gold)]' : 'text-slate-400'"
                            x-text="student.memberType">
                        </p>
                    </div>
                </div>
            </td>

            <td class="whitespace-nowrap px-6 py-5 text-sm font-semibold text-slate-700" x-text="student.email"></td>

            <td class="whitespace-nowrap px-6 py-5">
                <div class="flex items-center gap-2">
                    <span
                        class="text-emerald-500"
                        :class="student.status === 'active' ? 'text-emerald-500' : 'text-slate-300'">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-4l-4 4v-4z" />
                        </svg>
                    </span>

                    <span class="text-sm font-bold leading-6 text-slate-600" x-text="student.whatsapp"></span>
                </div>
            </td>

            <td class="whitespace-nowrap px-6 py-5 text-sm font-bold leading-6 text-slate-500" x-text="student.registeredDate"></td>

            <td class="whitespace-nowrap px-6 py-5">
                <span class="inline-flex rounded-lg bg-slate-100 px-3 py-2 text-xs font-extrabold text-slate-700">
                    <span x-text="student.coursesCount"></span>
                    <span class="ml-1" x-text="student.coursesCount === 1 ? 'Course' : 'Courses'"></span>
                </span>
            </td>

            <td class="whitespace-nowrap px-6 py-5">
                <span
                    class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-extrabold"
                    :class="student.status === 'active'
                        ? 'bg-blue-50 text-[var(--color-brand-blue)]'
                        : 'bg-slate-100 text-slate-500'">
                    <span
                        class="h-2 w-2 rounded-full"
                        :class="student.status === 'active' ? 'bg-[var(--color-brand-gold)]' : 'bg-slate-400'">
                    </span>
                    <span x-text="student.statusLabel"></span>
                </span>
            </td>

            <td class="whitespace-nowrap px-6 py-5 text-right">
                <button
                    type="button"
                    @click="openStudent(student)"
                    class="text-sm font-extrabold uppercase tracking-[0.14em] text-[var(--color-brand-blue)] transition hover:opacity-70">
                    View
                </button>
            </td>
        </tr>
    </template>

    <tr x-show="students.filter(student => matches(student)).length === 0">
        <td colspan="7" class="px-6 py-12 text-center text-sm font-medium text-slate-400">
            No students match your current filter.
        </td>
    </tr>

    <x-slot:footer>
        <x-admin.pagination text="Showing 1 to 5 of 1,240 students" />
    </x-slot:footer>
</x-admin.data-table>