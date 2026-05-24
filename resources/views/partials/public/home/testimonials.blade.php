@php
$testimonialCount = ($testimonials ?? collect())->count();
$totalSlides = $testimonialCount > 0 ? $testimonialCount : 4;
@endphp

<section
    x-data="{
        activeIndex: 0,
        total: {{ $totalSlides }},
        visibleCount: 4,
        timer: null,

        init() {
            this.updateVisibleCount();

            window.addEventListener('resize', () => {
                this.updateVisibleCount();
            });

            this.startAutoPlay();
        },

        updateVisibleCount() {
            if (window.innerWidth < 768) {
                this.visibleCount = 1;
            } else if (window.innerWidth < 1280) {
                this.visibleCount = 2;
            } else {
                this.visibleCount = 4;
            }

            if (this.activeIndex >= this.total) {
                this.activeIndex = 0;
            }
        },

        isVisible(index) {
            if (this.total <= this.visibleCount) {
                return true;
            }

            for (let offset = 0; offset < this.visibleCount; offset++) {
                if ((this.activeIndex + offset) % this.total === index) {
                    return true;
                }
            }

            return false;
        },

        next() {
            if (this.total <= this.visibleCount) {
                return;
            }

            this.activeIndex = (this.activeIndex + 1) % this.total;
        },

        prev() {
            if (this.total <= this.visibleCount) {
                return;
            }

            this.activeIndex = (this.activeIndex - 1 + this.total) % this.total;
        },

        goTo(index) {
            this.activeIndex = index;
            this.stopAutoPlay();
            this.startAutoPlay();
        },

        startAutoPlay() {
            if (this.total <= this.visibleCount || this.timer) {
                return;
            }

            this.timer = setInterval(() => {
                this.next();
            }, 5000);
        },

        stopAutoPlay() {
            if (this.timer) {
                clearInterval(this.timer);
                this.timer = null;
            }
        }
    }"
    @mouseenter="stopAutoPlay()"
    @mouseleave="startAutoPlay()"
    class="bg-slate-50">
    <div class="mx-auto max-w-7xl px-4 py-16 lg:px-8">
        <div class="mx-auto max-w-3xl text-center">
            <p class="reveal text-xs font-black uppercase tracking-[0.22em] text-[var(--color-brand-gold)]">
                Testimonials
            </p>

            <h2 class="reveal mt-3 text-2xl font-bold text-slate-900 md:text-3xl">
                What Our <span class="text-[var(--color-brand-gold)]">Students Say</span>
            </h2>

            <p class="reveal reveal-delay-1 mt-3 text-base leading-8 text-slate-600">
                Real feedback from students who have experienced learning with Queens English Prestige.
            </p>
        </div>

        <div class="relative mt-12">
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                @forelse ($testimonials as $index => $testimonial)
                @php
                $delayClass = match ($index % 4) {
                1 => 'reveal-delay-1',
                2 => 'reveal-delay-2',
                3 => 'reveal-delay-3',
                default => '',
                };

                $displayName = $testimonial->name
                ?: ($testimonial->student?->name ?? 'Queens Student');

                $initials = collect(explode(' ', trim($displayName)))
                ->filter()
                ->take(2)
                ->map(fn ($word) => strtoupper(substr($word, 0, 1)))
                ->implode('');

                $initials = $initials ?: 'QS';

                $isCompanyFeedback = $testimonial->type === 'company';

                $typeLabel = $isCompanyFeedback ? 'Company Feedback' : 'Course Feedback';

                $typeBadgeClass = $isCompanyFeedback
                ? 'bg-purple-50 text-purple-700'
                : 'bg-blue-50 text-blue-700';

                $courseLabel = $testimonial->courseLevel?->name
                ?? ($isCompanyFeedback ? 'Queens English Prestige' : 'Course Student');

                $programLabel = $testimonial->courseLevel?->courseProgram?->name;

                if ($isCompanyFeedback) {
                $subtitle = 'Queens English Prestige';
                } elseif ($programLabel) {
                $subtitle = $courseLabel . ' • ' . $programLabel;
                } else {
                $subtitle = $courseLabel;
                }

                $rating = max(0, min(5, (int) ($testimonial->rating ?? 5)));
                @endphp

                <div
                    x-show="isVisible({{ $index }})"
                    x-transition.opacity.duration.300ms
                    class="reveal {{ $delayClass }} motion-card flex h-full flex-col rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-md">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-center gap-1 text-amber-400">
                            @for ($i = 1; $i <= 5; $i++)
                                <span>{{ $i <= $rating ? '★' : '☆' }}</span>
                                @endfor
                        </div>

                        <span class="inline-flex shrink-0 rounded-full px-3 py-1 text-[10px] font-black uppercase tracking-[0.08em] {{ $typeBadgeClass }}">
                            {{ $typeLabel }}
                        </span>
                    </div>

                    <p class="mt-5 line-clamp-5 flex-1 text-sm leading-7 text-slate-600">
                        “{{ $testimonial->testimonial }}”
                    </p>

                    <div class="mt-6 flex items-center gap-3 border-t border-slate-100 pt-5">
                        @if ($testimonial->photo)
                        <img
                            src="{{ asset('storage/' . $testimonial->photo) }}"
                            alt="{{ $displayName }}"
                            class="h-11 w-11 rounded-full object-cover">
                        @else
                        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-blue-100 text-sm font-bold text-blue-700">
                            {{ $initials }}
                        </div>
                        @endif

                        <div class="min-w-0">
                            <p class="truncate text-sm font-bold text-slate-900">
                                {{ $displayName }}
                            </p>

                            <p class="mt-0.5 line-clamp-1 text-xs font-medium text-slate-500">
                                {{ $subtitle }}
                            </p>
                        </div>
                    </div>
                </div>
                @empty
                <div
                    x-show="isVisible(0)"
                    x-transition.opacity.duration.300ms
                    class="reveal motion-card rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-1 text-amber-400">
                            <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                        </div>

                        <span class="inline-flex rounded-full bg-purple-50 px-3 py-1 text-[10px] font-black uppercase tracking-[0.08em] text-purple-700">
                            Company Feedback
                        </span>
                    </div>

                    <p class="mt-5 text-sm leading-7 text-slate-600">
                        “The lessons felt structured, practical, and premium. I finally feel confident
                        using English in daily conversations and professional settings.”
                    </p>

                    <div class="mt-6 flex items-center gap-3 border-t border-slate-100 pt-5">
                        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-blue-100 text-sm font-bold text-blue-700">
                            QS
                        </div>

                        <div>
                            <p class="text-sm font-bold text-slate-900">Queens Student</p>
                            <p class="text-xs font-medium text-slate-500">Queens English Prestige</p>
                        </div>
                    </div>
                </div>

                <div
                    x-show="isVisible(1)"
                    x-transition.opacity.duration.300ms
                    class="reveal reveal-delay-1 motion-card rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-1 text-amber-400">
                            <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                        </div>

                        <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-[10px] font-black uppercase tracking-[0.08em] text-blue-700">
                            Course Feedback
                        </span>
                    </div>

                    <p class="mt-5 text-sm leading-7 text-slate-600">
                        “The learning path was clear and easy to follow. The exercises helped me
                        understand the material step by step.”
                    </p>

                    <div class="mt-6 flex items-center gap-3 border-t border-slate-100 pt-5">
                        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-amber-100 text-sm font-bold text-amber-700">
                            QE
                        </div>

                        <div>
                            <p class="text-sm font-bold text-slate-900">Queens Learner</p>
                            <p class="text-xs font-medium text-slate-500">Course Student</p>
                        </div>
                    </div>
                </div>

                <div
                    x-show="isVisible(2)"
                    x-transition.opacity.duration.300ms
                    class="reveal reveal-delay-2 motion-card rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-1 text-amber-400">
                            <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                        </div>

                        <span class="inline-flex rounded-full bg-purple-50 px-3 py-1 text-[10px] font-black uppercase tracking-[0.08em] text-purple-700">
                            Company Feedback
                        </span>
                    </div>

                    <p class="mt-5 text-sm leading-7 text-slate-600">
                        “Queens English Prestige makes learning feel supportive and organized.
                        The program helped me become more confident.”
                    </p>

                    <div class="mt-6 flex items-center gap-3 border-t border-slate-100 pt-5">
                        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-emerald-100 text-sm font-bold text-emerald-700">
                            QP
                        </div>

                        <div>
                            <p class="text-sm font-bold text-slate-900">Queens Alumni</p>
                            <p class="text-xs font-medium text-slate-500">Queens English Prestige</p>
                        </div>
                    </div>
                </div>

                <div
                    x-show="isVisible(3)"
                    x-transition.opacity.duration.300ms
                    class="reveal reveal-delay-3 motion-card rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-1 text-amber-400">
                            <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                        </div>

                        <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-[10px] font-black uppercase tracking-[0.08em] text-blue-700">
                            Course Feedback
                        </span>
                    </div>

                    <p class="mt-5 text-sm leading-7 text-slate-600">
                        “The course materials were practical and easy to understand. I enjoyed the
                        learning process and the final assessment.”
                    </p>

                    <div class="mt-6 flex items-center gap-3 border-t border-slate-100 pt-5">
                        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-slate-100 text-sm font-bold text-slate-700">
                            EL
                        </div>

                        <div>
                            <p class="text-sm font-bold text-slate-900">English Learner</p>
                            <p class="text-xs font-medium text-slate-500">Course Student</p>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>

            <div
                x-show="total > visibleCount"
                class="mt-10 flex flex-col items-center justify-center gap-5 sm:flex-row">
                <button
                    type="button"
                    @click="prev()"
                    class="motion-button inline-flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 bg-white text-[var(--color-brand-blue)] shadow-sm transition hover:bg-blue-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <div class="flex max-w-full flex-wrap items-center justify-center gap-2">
                    @for ($i = 0; $i < $totalSlides; $i++)
                        <button
                        type="button"
                        @click="goTo({{ $i }})"
                        class="h-2 rounded-full transition-all duration-300"
                        :class="activeIndex === {{ $i }} ? 'w-8 bg-[var(--color-brand-gold)]' : 'w-2 bg-slate-300 hover:bg-slate-400'">
                        </button>
                        @endfor
                </div>

                <button
                    type="button"
                    @click="next()"
                    class="motion-button inline-flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 bg-white text-[var(--color-brand-blue)] shadow-sm transition hover:bg-blue-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</section>