<section class="space-y-4">
    <div class="reveal flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs font-black uppercase tracking-[0.2em] text-[#AD6B10]">
                Learning Hub
            </p>

            <h2 class="mt-1 text-2xl font-black text-[#080D4D] lg:text-3xl">
                Continue Learning
            </h2>

            <p class="mt-1 text-sm font-semibold text-slate-500">
                Pick up your active course and continue learning forward.
            </p>
        </div>

        <a
            href="{{ route('student.my-courses') }}"
            class="text-sm font-extrabold text-[#080D4D] hover:text-[#AD6B10] hover:underline">
            View all courses
        </a>
    </div>

    @if (count($continueLearningCourses) > 0)
    <div class="grid gap-4">
        @foreach ($continueLearningCourses as $course)
        <a
            href="{{ $course['href'] }}"
            class="reveal group rounded-[26px] border border-[#DDE3FF] bg-gradient-to-br from-white to-[#F4F6FF] p-4 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-[#F1D7B5] hover:shadow-[0_20px_50px_rgba(8,13,77,0.12)]">
            <div class="grid gap-4 lg:grid-cols-[140px_1fr_auto] lg:items-center">
                <div class="relative h-28 overflow-hidden rounded-[20px] bg-[#F4F6FF] lg:h-32">
                    <img
                        src="{{ $course['image'] }}"
                        alt="{{ $course['title'] }}"
                        class="h-full w-full object-cover transition duration-700 group-hover:scale-105">

                    <div class="absolute inset-0 bg-[#080D4D]/5"></div>
                </div>

                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex rounded-full bg-[#AD6B10]/10 px-3 py-1 text-[10px] font-black uppercase tracking-[0.14em] text-[#AD6B10]">
                            {{ $course['program'] }}
                        </span>

                        <span class="inline-flex rounded-full bg-[#080D4D]/10 px-3 py-1 text-[10px] font-black uppercase tracking-[0.14em] text-[#080D4D]">
                            {{ $course['statusLabel'] }}
                        </span>
                    </div>

                    <h3 class="mt-3 truncate text-2xl font-black text-[#080D4D]">
                        {{ $course['title'] }}
                    </h3>

                    <p class="mt-1 text-sm font-semibold text-slate-500">
                        Continue your module, review materials, and track your progress.
                    </p>

                    <div class="mt-4">
                        <div class="mb-2 flex items-center justify-between">
                            <p class="text-xs font-black uppercase tracking-[0.14em] text-slate-400">
                                Progress
                            </p>

                            <p class="text-sm font-black text-[#AD6B10]">
                                {{ $course['progress'] }}%
                            </p>
                        </div>

                        <div class="h-2.5 overflow-hidden rounded-full bg-white shadow-inner">
                            <div
                                class="h-full rounded-full bg-gradient-to-r from-[#080D4D] to-[#AD6B10]"
                                style="width: {{ min(100, max(0, $course['progress'])) }}%">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:pl-4">
                    <span class="motion-button inline-flex h-11 w-full items-center justify-center rounded-2xl bg-[#080D4D] px-5 text-sm font-extrabold text-white shadow-md transition group-hover:bg-[#AD6B10] lg:w-auto">
                        {{ $course['buttonText'] }}
                    </span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
    @else
    <div class="reveal rounded-[28px] border border-dashed border-[#DDE3FF] bg-gradient-to-br from-white to-[#F4F6FF] p-8 text-center shadow-sm">
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#080D4D]/10 text-[#080D4D]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <rect x="4" y="5" width="16" height="14" rx="2" stroke-width="1.8" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 9h8M8 13h5" />
            </svg>
        </div>

        <h3 class="mt-5 text-2xl font-black text-[#080D4D]">
            No Active Course Yet
        </h3>

        <p class="mx-auto mt-3 max-w-xl text-sm font-semibold leading-6 text-slate-500">
            Your active courses will appear here after your order is approved by our admin.
        </p>

        <div class="mt-6 flex flex-col justify-center gap-3 sm:flex-row">
            <a
                href="{{ route('courses') }}"
                class="motion-button inline-flex h-11 items-center justify-center rounded-2xl bg-[#080D4D] px-5 text-sm font-extrabold text-white shadow-md transition hover:bg-[#AD6B10]">
                Explore Courses
            </a>

            <a
                href="{{ route('student.my-courses') }}"
                class="motion-button inline-flex h-11 items-center justify-center rounded-2xl border border-[#DDE3FF] bg-white px-5 text-sm font-extrabold text-[#080D4D] transition hover:border-[#F1D7B5] hover:bg-[#FFF7EA]">
                My Courses
            </a>
        </div>
    </div>
    @endif
</section>