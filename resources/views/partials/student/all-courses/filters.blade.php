<div class="relative z-10 -mt-8">
    <div class="mx-auto max-w-7xl px-4 lg:px-8">
        <form
            action="{{ route('student.all-courses') }}"
            method="GET"
            class="reveal reveal-delay-2 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="grid gap-4 lg:grid-cols-[1fr_1fr_1fr_auto] lg:items-end">
                <div>
                    <label for="mode" class="mb-2 block text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">
                        Type Course
                    </label>

                    <x-ui.select id="mode" name="mode">
                        <option value="">All Type Course</option>
                        <option value="online" @selected(($selectedMode ?? null)==='online' )>Online</option>
                        <option value="offline" @selected(($selectedMode ?? null)==='offline' )>Offline</option>
                        <option value="hybrid" @selected(($selectedMode ?? null)==='hybrid' )>Hybrid</option>
                    </x-ui.select>
                </div>

                <div>
                    <label for="program" class="mb-2 block text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">
                        Program Course
                    </label>

                    <x-ui.select id="program" name="program">
                        <option value="">All Programs</option>

                        @foreach ($programs ?? [] as $program)
                        <option value="{{ $program->slug }}" @selected(($selectedProgram ?? null)===$program->slug)>
                            {{ $program->name }}
                        </option>
                        @endforeach
                    </x-ui.select>
                </div>

                <div>
                    <label for="status" class="mb-2 block text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">
                        Course Status
                    </label>

                    <x-ui.select id="status" name="status">
                        <option value="">All Status</option>
                        <option value="available" @selected(($selectedStatus ?? null)==='available' )>Available</option>
                        <option value="enrolled" @selected(($selectedStatus ?? null)==='enrolled' )>Enrolled</option>
                        <option value="completed" @selected(($selectedStatus ?? null)==='completed' )>Completed</option>
                        <option value="pending" @selected(($selectedStatus ?? null)==='pending' )>Waiting Approval</option>
                        <option value="rejected" @selected(($selectedStatus ?? null)==='rejected' )>Rejected</option>
                    </x-ui.select>
                </div>

                <div class="flex gap-3">
                    <x-ui.button type="submit" class="w-full px-6 py-3 lg:w-auto">
                        Filter
                    </x-ui.button>

                    @if (($selectedMode ?? null) || ($selectedProgram ?? null) || ($selectedStatus ?? null))
                    <a
                        href="{{ route('student.all-courses') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-6 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                        Reset
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>