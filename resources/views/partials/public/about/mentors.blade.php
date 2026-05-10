@php
$mentorItems = ($mentors ?? collect())->values();

$mentorItemsForJs = $mentorItems
->map(function ($mentor) {
$photo = $mentor->photo
? asset('storage/' . $mentor->photo)
: 'https://placehold.co/500x650/F1F5F9/0F172A?text=' . urlencode($mentor->name);

return [
'name' => $mentor->name,
'position' => $mentor->position ?: $mentor->expertise ?: 'Mentor',
'expertise' => $mentor->expertise,
'description' => $mentor->description ?: 'Dedicated mentor committed to helping learners grow with confidence.',
'photo' => $photo,
];
});
@endphp

<section class="bg-slate-50">
    <div
        x-data="{
            mentors: {{ Js::from($mentorItemsForJs) }},
            activeIndex: 0,
            visibleCount: 4,
            timer: null,
            previewOpen: false,
            previewMentor: null,

            init() {
                this.updateVisibleCount();

                window.addEventListener('resize', () => {
                    this.updateVisibleCount();
                });

                this.startAutoPlay();

                window.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape') {
                        this.closePreview();
                    }
                });
            },

            get total() {
                return this.mentors.length;
            },

            updateVisibleCount() {
                if (window.innerWidth < 640) {
                    this.visibleCount = 1;
                } else if (window.innerWidth < 1024) {
                    this.visibleCount = 2;
                } else {
                    this.visibleCount = 4;
                }

                if (this.activeIndex >= this.total) {
                    this.activeIndex = 0;
                }
            },

            visibleMentors() {
                if (this.total <= this.visibleCount) {
                    return this.mentors;
                }

                return Array.from({ length: this.visibleCount }, (_, offset) => {
                    return this.mentors[(this.activeIndex + offset) % this.total];
                });
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
                }, 4500);
            },

            stopAutoPlay() {
                if (this.timer) {
                    clearInterval(this.timer);
                    this.timer = null;
                }
            },

            openPreview(mentor) {
                this.previewMentor = mentor;
                this.previewOpen = true;
                this.stopAutoPlay();
                document.body.classList.add('overflow-hidden');
            },

            closePreview() {
                this.previewOpen = false;
                this.previewMentor = null;
                document.body.classList.remove('overflow-hidden');
                this.startAutoPlay();
            }
        }"
        @mouseenter="stopAutoPlay()"
        @mouseleave="startAutoPlay()"
        class="mx-auto max-w-7xl px-4 py-16 lg:px-8">

        <div class="text-center">
            <x-public.section-title class="reveal text-2xl md:text-3xl">
                Meet Our Lead [gold]Mentors[/gold]
            </x-public.section-title>

            <p class="reveal reveal-delay-1 mt-3 text-sm text-slate-500">
                World-class experts dedicated to your success.
            </p>
        </div>

        @if ($mentorItems->isNotEmpty())
        <div class="mt-10">
            <div class="flex items-center gap-4">
                <button
                    type="button"
                    x-show="total > visibleCount"
                    @click="prev()"
                    class="motion-button hidden h-11 w-11 shrink-0 items-center justify-center rounded-full border border-slate-200 bg-white text-[var(--color-brand-blue)] shadow-sm transition hover:-translate-y-0.5 hover:bg-blue-50 sm:inline-flex">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <div class="min-w-0 flex-1">
                    <div
                        :class="total <= visibleCount ? 'flex flex-wrap justify-center gap-6' : 'grid gap-6 sm:grid-cols-2 lg:grid-cols-4'"
                        class="items-stretch">

                        <template x-for="(mentor, index) in visibleMentors()" :key="activeIndex + '-' + index + '-' + mentor.name">
                            <div
                                :class="total <= visibleCount ? 'w-full sm:w-[calc(50%-0.75rem)] lg:w-[calc(25%-1.125rem)]' : ''"
                                class="group motion-card overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-md">

                                <button
                                    type="button"
                                    @click="openPreview(mentor)"
                                    class="relative block w-full overflow-hidden bg-slate-100 text-left">
                                    <div class="aspect-[4/4.3] overflow-hidden">
                                        <img
                                            :src="mentor.photo"
                                            :alt="mentor.name"
                                            class="motion-image h-full w-full object-cover">
                                    </div>

                                    <div class="absolute inset-0 flex items-end justify-center bg-gradient-to-t from-slate-900/55 via-slate-900/0 to-transparent opacity-0 transition duration-300 group-hover:opacity-100">
                                        <span class="mb-4 inline-flex items-center gap-2 rounded-full bg-white/95 px-4 py-2 text-xs font-bold text-[var(--color-brand-blue)] shadow-sm">
                                            Preview Photo
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </span>
                                    </div>
                                </button>

                                <div class="flex flex-col p-4">
                                    <h3
                                        x-text="mentor.name"
                                        class="line-clamp-2 text-base font-bold leading-tight text-slate-900">
                                    </h3>

                                    <p
                                        x-text="mentor.position"
                                        class="mt-1 line-clamp-1 text-xs font-semibold text-[var(--color-brand-blue)]">
                                    </p>

                                    <template x-if="mentor.expertise && mentor.expertise !== mentor.position">
                                        <p
                                            x-text="mentor.expertise"
                                            class="mt-1 line-clamp-1 text-[11px] font-semibold text-[var(--color-brand-gold)]">
                                        </p>
                                    </template>

                                    <p
                                        x-text="mentor.description"
                                        class="mt-3 line-clamp-3 text-xs leading-6 text-slate-500">
                                    </p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <button
                    type="button"
                    x-show="total > visibleCount"
                    @click="next()"
                    class="motion-button hidden h-11 w-11 shrink-0 items-center justify-center rounded-full border border-slate-200 bg-white text-[var(--color-brand-blue)] shadow-sm transition hover:-translate-y-0.5 hover:bg-blue-50 sm:inline-flex">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            <div
                x-show="total > visibleCount"
                class="mt-8 flex items-center justify-center gap-2">
                <template x-for="(_, index) in Array.from({ length: total })" :key="index">
                    <button
                        type="button"
                        @click="goTo(index)"
                        class="h-2.5 rounded-full transition-all duration-300"
                        :class="activeIndex === index ? 'w-8 bg-[var(--color-brand-gold)]' : 'w-2.5 bg-slate-300 hover:bg-slate-400'">
                    </button>
                </template>
            </div>

            <div
                x-show="total > visibleCount"
                class="mt-5 flex items-center justify-center gap-3 sm:hidden">
                <button
                    type="button"
                    @click="prev()"
                    class="motion-button inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-[var(--color-brand-blue)] shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <button
                    type="button"
                    @click="next()"
                    class="motion-button inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-[var(--color-brand-blue)] shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Preview Modal --}}
        <div
            x-show="previewOpen"
            x-transition.opacity.duration.200ms
            class="fixed inset-0 z-[80] flex items-center justify-center bg-slate-950/75 px-4 py-8 backdrop-blur-sm"
            style="display: none;"
            @click.self="closePreview()">

            <div
                x-show="previewOpen"
                x-transition.scale.origin.center.duration.200ms
                class="relative w-full max-w-[520px]">

                <button
                    type="button"
                    @click="closePreview()"
                    class="absolute -right-2 -top-2 z-10 inline-flex h-10 w-10 items-center justify-center rounded-full bg-white text-slate-700 shadow-lg transition hover:bg-slate-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <div class="overflow-hidden rounded-[28px] border border-white/20 bg-white shadow-2xl">
                    <div class="bg-slate-100">
                        <img
                            :src="previewMentor?.photo"
                            :alt="previewMentor?.name"
                            class="max-h-[72vh] w-full object-contain">
                    </div>

                    <div class="px-6 py-5 text-center">
                        <h3
                            x-text="previewMentor?.name"
                            class="text-xl font-bold text-slate-900">
                        </h3>

                        <p
                            x-text="previewMentor?.position"
                            class="mt-1 text-sm font-semibold text-[var(--color-brand-blue)]">
                        </p>

                        <template x-if="previewMentor?.expertise && previewMentor?.expertise !== previewMentor?.position">
                            <p
                                x-text="previewMentor?.expertise"
                                class="mt-1 text-xs font-semibold text-[var(--color-brand-gold)]">
                            </p>
                        </template>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="reveal mt-10 rounded-[24px] border border-slate-200 bg-white px-6 py-12 text-center shadow-sm">
            <h3 class="text-xl font-bold text-slate-900">
                Mentor profiles will be available soon.
            </h3>

            <p class="mx-auto mt-3 max-w-xl text-sm leading-7 text-slate-500">
                Our team is preparing mentor information to help you get to know the experts behind Queens English Prestige.
            </p>
        </div>
        @endif
    </div>
</section>