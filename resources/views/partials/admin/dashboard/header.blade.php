<div class="relative overflow-hidden rounded-2xl border border-slate-200/90 border-t-2 border-t-[#AD6B10] bg-white p-5 sm:p-6 shadow-2xs">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="min-w-0 max-w-3xl">
            <div class="flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-[#AD6B10]"></span>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">
                    Admin Command Center
                </p>
            </div>

            <h2 class="mt-1 text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                Welcome back, Admin
            </h2>

            <p class="mt-1 text-xs sm:text-sm font-medium leading-relaxed text-slate-500">
                Monitor student enrollments, course access, orders, manual grading, certificates, and website CMS content from one central overview.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5 shrink-0">
            <a
                href="{{ route('admin.orders.index') }}"
                class="inline-flex h-9 items-center justify-center rounded-xl bg-[#080D4D] px-4 text-xs font-bold text-white shadow-2xs transition hover:bg-[#060A3B]">
                Review Orders
            </a>

            <a
                href="{{ route('admin.course-management.programs.index') }}"
                class="inline-flex h-9 items-center justify-center rounded-xl border border-slate-200/90 bg-white px-4 text-xs font-bold text-slate-700 shadow-2xs transition hover:bg-slate-50">
                Manage Courses
            </a>
        </div>
    </div>
</div>