<div class="mt-8 space-y-6">
    @forelse ($courses as $index => $course)
    @php
    $delayClass = match ($index) {
    1 => 'reveal-delay-1',
    2 => 'reveal-delay-2',
    default => '',
    };
    @endphp

    <div
        x-show="matches('{{ $course['status'] }}', '{{ strtolower($course['title']) }}')"
        x-transition.opacity.duration.250ms
        class="reveal {{ $delayClass }}">
        <x-student.my-course-card
            :title="$course['title']"
            :level="$course['level']"
            :status="$course['status']"
            :status-label="$course['statusLabel']"
            :meta="$course['meta']"
            :progress="$course['progress']"
            :progress-label="$course['progressLabel']"
            :badge="$course['badge']"
            :image="$course['image']"
            :primary-button="$course['primaryButton']"
            :secondary-button="$course['secondaryButton']" />
    </div>
    @empty
    <div class="reveal rounded-[24px] border border-dashed border-slate-300 bg-white px-6 py-14 text-center shadow-sm">
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-blue-50 text-[var(--color-brand-blue)]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <rect x="4" y="5" width="16" height="14" rx="2" stroke-width="1.8" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 9h8M8 13h5" />
            </svg>
        </div>

        <h2 class="mt-5 text-2xl font-extrabold text-slate-900">
            No Courses Yet
        </h2>

        <p class="mx-auto mt-3 max-w-md text-sm leading-6 text-slate-500">
            Your courses will appear here after you place an order and our admin approves your enrollment.
        </p>

        <a
            href="{{ route('courses') }}"
            class="motion-button mt-6 inline-flex h-12 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-6 text-sm font-bold text-white shadow-md transition hover:opacity-95">
            Explore Courses
        </a>
    </div>
    @endforelse

    @if (count($courses) > 0)
    <div
        x-show="!{{ count($courses) }} || Array.from(document.querySelectorAll('[x-show]')).length"
        class="hidden">
    </div>

    <div
        x-show="{{ Js::from(count($courses) > 0) }} && !Array.from($el.parentElement.querySelectorAll('.reveal')).some(element => element.offsetParent !== null)"
        class="rounded-[24px] border border-dashed border-slate-300 bg-white px-6 py-10 text-center text-sm font-medium text-slate-500">
        No courses match your current filter.
    </div>
    @endif
</div>