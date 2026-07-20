<div class="overflow-hidden rounded-[30px] border border-slate-200 bg-white shadow-sm">
    <div class="grid gap-0 xl:grid-cols-[1.25fr_0.75fr]">
        <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-[var(--color-brand-blue)] to-slate-800 p-7 text-white lg:p-9">
            <div class="pointer-events-none absolute -right-20 -top-20 h-56 w-56 rounded-full bg-white/10 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-24 left-24 h-56 w-56 rounded-full bg-[#AD6B10]/30 blur-3xl"></div>

            <div class="relative">
                <p class="text-xs font-black uppercase tracking-[0.24em] text-white/50">
                    Admin Command Center
                </p>

                <h2 class="mt-4 text-4xl font-black leading-tight lg:text-5xl">
                    Welcome back, Admin
                </h2>

                <p class="mt-4 max-w-3xl text-sm font-semibold leading-7 text-white/75">
                    Monitor students, course access, payments, reviews, certificates, and homepage content from one dashboard.
                </p>

                <div class="mt-7 flex flex-wrap gap-3">
                    <a
                        href="{{ route('admin.orders.index') }}"
                        class="inline-flex h-11 items-center justify-center rounded-xl bg-white px-5 text-sm font-black text-[var(--color-brand-blue)] transition hover:bg-[#f8efcf]">
                        Review Orders
                    </a>

                    <a
                        href="{{ route('admin.course-management.programs.index') }}"
                        class="inline-flex h-11 items-center justify-center rounded-xl border border-white/20 bg-white/10 px-5 text-sm font-black text-white backdrop-blur-sm transition hover:bg-white/15">
                        Manage Courses
                    </a>

                    <a
                        href="{{ route('admin.notifications.index') }}"
                        class="inline-flex h-11 items-center justify-center rounded-xl border border-white/20 bg-white/10 px-5 text-sm font-black text-white backdrop-blur-sm transition hover:bg-white/15">
                        Notifications
                    </a>
                </div>
            </div>
        </div>

        <div class="grid gap-3 bg-white p-6 sm:grid-cols-3 xl:grid-cols-1">
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4">
                <p class="text-[11px] font-black uppercase tracking-[0.16em] text-emerald-500">
                    This Month Revenue
                </p>

                <p class="mt-2 text-2xl font-black text-emerald-700">
                    Rp {{ number_format((float) $thisMonthRevenue, 0, ',', '.') }}
                </p>

                <p class="mt-1 text-xs font-bold text-emerald-600/80">
                    Paid approved orders
                </p>
            </div>

            <div class="rounded-2xl border border-amber-100 bg-amber-50 px-5 py-4">
                <p class="text-[11px] font-black uppercase tracking-[0.16em] text-amber-500">
                    Pending Orders
                </p>

                <div class="mt-2 flex items-end justify-between gap-4">
                    <p class="text-3xl font-black text-amber-700">
                        {{ number_format($pendingOrders) }}
                    </p>

                    <a href="{{ route('admin.orders.index') }}" class="text-xs font-black text-amber-700 hover:underline">
                        Open
                    </a>
                </div>
            </div>

            <div class="rounded-2xl border border-blue-100 bg-blue-50 px-5 py-4">
                <p class="text-[11px] font-black uppercase tracking-[0.16em] text-blue-500">
                    Waiting Reviews
                </p>

                <div class="mt-2 flex items-end justify-between gap-4">
                    <p class="text-3xl font-black text-blue-700">
                        {{ number_format($waitingReviews) }}
                    </p>

                    <a href="{{ route('admin.course-management.programs.index') }}" class="text-xs font-black text-blue-700 hover:underline">
                        Review
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>