<section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
    <div class="reveal rounded-[24px] border border-[#DDE3FF] bg-gradient-to-br from-white to-[#F4F6FF] p-5 shadow-sm">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-sm font-black text-slate-500">Active Courses</p>
                <p class="mt-3 text-3xl font-black text-[#080D4D]">
                    {{ str_pad((string) $activeCourseCount, 2, '0', STR_PAD_LEFT) }}
                </p>
                <p class="mt-2 text-xs font-bold uppercase tracking-[0.14em] text-[#080D4D]/70">
                    Currently learning
                </p>
            </div>

            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#080D4D]/10 text-[#080D4D]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <rect x="4" y="5" width="16" height="14" rx="2" stroke-width="1.8" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 9h8M8 13h5" />
                </svg>
            </div>
        </div>
    </div>

    <div class="reveal reveal-delay-1 rounded-[24px] border border-[#F1D7B5] bg-gradient-to-br from-white to-[#FFF7EA] p-5 shadow-sm">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-sm font-black text-slate-500">Average Progress</p>
                <p class="mt-3 text-3xl font-black text-[#080D4D]">
                    {{ $averageProgress }}%
                </p>
                <p class="mt-2 text-xs font-bold uppercase tracking-[0.14em] text-[#AD6B10]">
                    Across active courses
                </p>
            </div>

            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#AD6B10]/10 text-[#AD6B10]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 19V5M4 19h16M8 16v-5M12 16V8M16 16v-3" />
                </svg>
            </div>
        </div>
    </div>

    <div class="reveal reveal-delay-2 rounded-[24px] border border-emerald-100 bg-gradient-to-br from-white to-emerald-50 p-5 shadow-sm">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-sm font-black text-slate-500">Completed Courses</p>
                <p class="mt-3 text-3xl font-black text-[#080D4D]">
                    {{ str_pad((string) $completedCourseCount, 2, '0', STR_PAD_LEFT) }}
                </p>
                <p class="mt-2 text-xs font-bold uppercase tracking-[0.14em] text-emerald-600">
                    Finished learning paths
                </p>
            </div>

            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3l7 4v5c0 5-3.5 8-7 9-3.5-1-7-4-7-9V7l7-4z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="reveal reveal-delay-3 rounded-[24px] border border-[#F1D7B5] bg-gradient-to-br from-white to-[#FFF7EA] p-5 shadow-sm">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-sm font-black text-slate-500">Certificates</p>
                <p class="mt-3 text-3xl font-black text-[#080D4D]">
                    {{ str_pad((string) $issuedCertificateCount, 2, '0', STR_PAD_LEFT) }}
                </p>
                <p class="mt-2 text-xs font-bold uppercase tracking-[0.14em] text-[#AD6B10]">
                    Issued certificates
                </p>
            </div>

            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#AD6B10]/10 text-[#AD6B10]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <rect x="5" y="3" width="14" height="18" rx="2" stroke-width="1.8" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 8h6M9 12h6M9 16h3" />
                </svg>
            </div>
        </div>
    </div>
</section>