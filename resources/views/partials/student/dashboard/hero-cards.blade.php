<div class="grid gap-8 xl:grid-cols-[1.7fr_1fr]">
    <div class="reveal reveal-delay-1">
        <x-student.welcome-card
            :status="$academicStatus"
            :name="$student->name"
            :description="$welcomeDescription"
            button-text="Go to My Courses"
            :href="route('student.my-courses')" />
    </div>

    <div class="reveal reveal-delay-2">
        <div class="rounded-[24px] border border-slate-200 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">
                Course Access
            </p>

            <h2 class="mt-4 text-3xl font-extrabold leading-tight text-slate-900">
                {{ $activeCourseCount }} Active {{ Str::plural('Course', $activeCourseCount) }}
            </h2>

            <p class="mt-3 text-sm leading-6 text-slate-500">
                Approved courses will appear in your My Courses page and can be accessed from your learning dashboard.
            </p>

            <div class="mt-6 grid grid-cols-2 gap-3">
                <div class="rounded-2xl bg-blue-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-blue-500">
                        Pending
                    </p>
                    <p class="mt-2 text-3xl font-extrabold text-[var(--color-brand-blue)]">
                        {{ $pendingOrderCount }}
                    </p>
                </div>

                <div class="rounded-2xl bg-emerald-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-emerald-500">
                        Completed
                    </p>
                    <p class="mt-2 text-3xl font-extrabold text-emerald-600">
                        {{ $completedCourseCount }}
                    </p>
                </div>
            </div>

            <a
                href="{{ route('student.my-courses') }}"
                class="motion-button mt-6 inline-flex h-11 w-full items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 text-sm font-bold text-white shadow-md transition hover:opacity-95">
                View My Courses
            </a>
        </div>
    </div>
</div>