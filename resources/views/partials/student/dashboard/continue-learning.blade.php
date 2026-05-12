<div class="space-y-4">
    <div class="reveal flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 lg:text-3xl">
                Continue Learning
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Continue from your active approved courses.
            </p>
        </div>

        <a
            href="{{ route('student.my-courses') }}"
            class="text-sm font-bold text-[var(--color-brand-blue)] hover:underline">
            View all courses
        </a>
    </div>

    @if (count($continueLearningCourses) > 0)
    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
        @foreach ($continueLearningCourses as $index => $course)
        @php
        $delayClass = match ($index) {
        1 => 'reveal-delay-1',
        2 => 'reveal-delay-2',
        3 => 'reveal-delay-3',
        default => '',
        };
        @endphp

        <div class="reveal {{ $delayClass }}">
            <x-student.course-progress-card
                :title="$course['title']"
                :level="$course['level']"
                :progress="$course['progress']"
                :image="$course['image']"
                :href="$course['href'] ?? null" />
        </div>
        @endforeach
    </div>
    @else
    <div class="reveal rounded-[24px] border border-dashed border-slate-300 bg-white px-6 py-12 text-center shadow-sm">
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-blue-50 text-[var(--color-brand-blue)]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <rect x="4" y="5" width="16" height="14" rx="2" stroke-width="1.8" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 9h8M8 13h5" />
            </svg>
        </div>

        <h3 class="mt-5 text-2xl font-extrabold text-slate-900">
            No Active Course Yet
        </h3>

        <p class="mx-auto mt-3 max-w-md text-sm leading-6 text-slate-500">
            Your active courses will appear here after your order is approved by our admin.
        </p>

        <div class="mt-6 flex flex-col justify-center gap-3 sm:flex-row">
            <a
                href="{{ route('courses') }}"
                class="motion-button inline-flex h-12 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-6 text-sm font-bold text-white shadow-md transition hover:opacity-95">
                Explore Courses
            </a>

            <a
                href="{{ route('student.my-courses') }}"
                class="motion-button inline-flex h-12 items-center justify-center rounded-xl border border-slate-300 bg-white px-6 text-sm font-bold text-slate-600 transition hover:bg-slate-50">
                My Courses
            </a>
        </div>
    </div>
    @endif
</div>