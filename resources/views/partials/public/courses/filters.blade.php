<section class="relative z-10 -mt-8">
    <div class="mx-auto max-w-7xl px-4 lg:px-8">
        <form
            action="{{ route('courses') }}"
            method="GET"
            class="reveal rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="grid gap-4 lg:grid-cols-[1fr_1fr_auto] lg:items-end">
                <div class="reveal">
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

                <div class="reveal reveal-delay-1">
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

                <div class="reveal reveal-delay-2 flex gap-3">
                    <x-ui.button type="submit" class="w-full px-6 py-3 lg:w-auto">
                        Filter Courses
                    </x-ui.button>

                    @if (($selectedMode ?? null) || ($selectedProgram ?? null))
                    <a
                        href="{{ route('courses') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-6 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                        Reset
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</section>