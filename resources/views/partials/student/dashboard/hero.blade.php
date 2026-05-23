<section class="reveal">
    <div class="grid gap-5 lg:grid-cols-[1.35fr_0.65fr]">
        <div class="relative overflow-hidden rounded-[30px] bg-gradient-to-br from-[#080D4D] via-[#101C72] to-[#AD6B10] p-6 text-white shadow-[0_24px_70px_rgba(8,13,77,0.28)] lg:p-7">
            <div class="pointer-events-none absolute -right-16 -top-16 h-44 w-44 rounded-full bg-white/10 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-20 left-20 h-44 w-44 rounded-full bg-[#AD6B10]/30 blur-3xl"></div>
            <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(255,255,255,0.12),transparent_32%)]"></div>

            <div class="relative">
                <div class="inline-flex items-center rounded-full border border-white/15 bg-white/10 px-4 py-2 text-[11px] font-black uppercase tracking-[0.18em] text-white">
                    {{ $academicStatus }}
                </div>

                <h1 class="mt-5 max-w-3xl text-3xl font-black leading-tight lg:text-5xl">
                    Welcome back, {{ $student->name }} 👋
                </h1>

                <p class="mt-4 max-w-2xl text-sm font-semibold leading-7 text-white/90 lg:text-base">
                    {{ $welcomeDescription }}
                </p>

                <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                    <a
                        href="{{ $primaryLearningUrl }}"
                        class="motion-button inline-flex h-11 items-center justify-center rounded-2xl bg-white px-5 text-sm font-extrabold text-[#080D4D] shadow-md transition hover:bg-[#FFF7EA]">
                        {{ $primaryLearningButton }}
                    </a>

                    <a
                        href="{{ route('courses') }}"
                        class="motion-button inline-flex h-11 items-center justify-center rounded-2xl border border-white/25 bg-white/10 px-5 text-sm font-extrabold text-white transition hover:bg-white/15">
                        Browse Courses
                    </a>
                </div>
            </div>
        </div>

        <div class="rounded-[30px] border border-[#DDE3FF] bg-gradient-to-br from-white via-[#F4F6FF] to-[#FFF7EA] p-6 shadow-[0_18px_45px_rgba(8,13,77,0.08)]">
            <p class="text-xs font-black uppercase tracking-[0.18em] text-[#080D4D]/50">
                Learning Overview
            </p>

            <div class="mt-5 flex items-end gap-2">
                <span class="text-5xl font-black leading-none text-[#080D4D]">
                    {{ $averageProgress }}%
                </span>
                <span class="pb-1 text-sm font-bold text-slate-500">
                    average
                </span>
            </div>

            <div class="mt-5 h-3 overflow-hidden rounded-full bg-white shadow-inner">
                <div
                    class="h-full rounded-full bg-gradient-to-r from-[#080D4D] to-[#AD6B10]"
                    style="width: {{ min(100, max(0, $averageProgress)) }}%">
                </div>
            </div>

            <div class="mt-6 grid gap-3">
                <div class="flex items-center justify-between rounded-2xl border border-[#DDE3FF] bg-white/80 px-4 py-3">
                    <span class="text-sm font-bold text-slate-600">Active Courses</span>
                    <span class="text-lg font-black text-[#080D4D]">{{ $activeCourseCount }}</span>
                </div>

                <div class="flex items-center justify-between rounded-2xl border border-emerald-100 bg-white/80 px-4 py-3">
                    <span class="text-sm font-bold text-slate-600">Completed</span>
                    <span class="text-lg font-black text-emerald-600">{{ $completedCourseCount }}</span>
                </div>

                <div class="flex items-center justify-between rounded-2xl border border-[#F1D7B5] bg-white/80 px-4 py-3">
                    <span class="text-sm font-bold text-slate-600">Certificates</span>
                    <span class="text-lg font-black text-[#AD6B10]">{{ $issuedCertificateCount }}</span>
                </div>
            </div>
        </div>
    </div>
</section>