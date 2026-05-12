@props([
'title' => 'Course Final Exam',
'description' => null,
'passingGrade' => 70,
'maxAttempts' => null,
])

<div class="relative overflow-hidden rounded-[26px] bg-gradient-to-r from-[#071738] via-[#081B4A] to-[#0A2059] px-7 py-8 text-white shadow-[0_18px_45px_rgba(15,23,42,0.18)]">
    <div class="relative z-10 flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">
        <div class="max-w-2xl">
            <span class="inline-flex items-center rounded-full bg-white/10 px-4 py-2 text-xs font-bold uppercase tracking-[0.22em] text-slate-200">
                Certification Milestone
            </span>

            <h3 class="mt-6 text-[38px] font-bold leading-tight text-white">
                {{ $title }}
            </h3>

            <p class="mt-4 max-w-xl text-lg leading-8 text-blue-100">
                {{ $description ?: 'This comprehensive exam covers all materials from this course. Your certificate will be processed after passing the required assessment.' }}
            </p>

            <p class="mt-5 inline-flex items-center gap-2 text-base font-semibold text-[var(--color-brand-gold)]">
                Passing grade: {{ $passingGrade }}%
                @if ($maxAttempts)
                • Max attempts: {{ $maxAttempts }}
                @endif
            </p>
        </div>

        <div class="shrink-0">
            <button
                type="button"
                class="inline-flex h-14 min-w-[240px] cursor-not-allowed items-center justify-center gap-3 rounded-2xl bg-white/80 px-8 text-lg font-bold text-[var(--color-brand-blue)] shadow-lg">
                Final Exam Coming Soon
            </button>
        </div>
    </div>

    <div class="pointer-events-none absolute -right-10 top-8 h-40 w-40 rotate-12 rounded-[32px] border-[14px] border-white/10"></div>
</div>