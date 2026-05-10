@php
$fallbackWhyStudyItems = collect([
[
'title' => 'Experienced Mentors',
'description' => 'Industry-leading educators with practical experience in English learning, test preparation, and communication skills.',
'icon' => null,
],
[
'title' => 'Structured Curriculum',
'description' => 'A clear learning path designed to help students improve step by step with measurable progress.',
'icon' => null,
],
[
'title' => 'Practical Learning',
'description' => 'Real-world practice, guided exercises, and learning activities that help students use English with confidence.',
'icon' => null,
],
[
'title' => 'Real Results',
'description' => 'Focused programs built to support students in reaching their academic, professional, and communication goals.',
'icon' => null,
],
]);

$whyStudyItems = isset($whyChooseUsItems) && $whyChooseUsItems->isNotEmpty()
? $whyChooseUsItems
: $fallbackWhyStudyItems;

$whyStudyItemsForJs = $whyStudyItems
->values()
->map(function ($item) {
return [
'title' => is_array($item) ? ($item['title'] ?? '') : ($item->title ?? ''),
'description' => is_array($item) ? ($item['description'] ?? '') : ($item->description ?? ''),
'icon' => is_array($item) ? ($item['icon'] ?? null) : ($item->icon ?? null),
'icon_url' => (is_array($item) ? ($item['icon'] ?? null) : ($item->icon ?? null))
? asset('storage/' . (is_array($item) ? $item['icon'] : $item->icon))
: null,
];
});

$totalItems = $whyStudyItemsForJs->count();
@endphp

<section class="bg-slate-50">
    <div
        x-data="{
            activeIndex: 0,
            total: {{ $totalItems }},
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
                if (window.innerWidth < 640) {
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

            startAutoPlay() {
                if (this.total <= this.visibleCount) {
                    return;
                }

                this.timer = setInterval(() => {
                    this.next();
                }, 4000);
            },

            stopAutoPlay() {
                if (this.timer) {
                    clearInterval(this.timer);
                    this.timer = null;
                }
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

            visibleItems() {
                const items = {{ Js::from($whyStudyItemsForJs) }};

                if (this.total <= this.visibleCount) {
                    return items;
                }

                return Array.from({ length: this.visibleCount }, (_, offset) => {
                    return items[(this.activeIndex + offset) % this.total];
                });
            },

            goTo(index) {
                this.activeIndex = index;
                this.stopAutoPlay();
                this.startAutoPlay();
            }
        }"
        @mouseenter="stopAutoPlay()"
        @mouseleave="startAutoPlay()"
        class="mx-auto max-w-7xl px-4 py-16 lg:px-8">

        <div class="text-center">
            <x-public.section-title class="reveal text-2xl md:text-3xl">
                Why Study with [gold]Us?[/gold]
            </x-public.section-title>

            <div class="reveal reveal-delay-1 mx-auto mt-3 h-1 w-16 rounded-full bg-[var(--color-brand-gold)]"></div>
        </div>

        <div class="mt-10 flex items-center justify-between gap-4">
            <div class="hidden sm:block">
                <button
                    type="button"
                    x-show="total > visibleCount"
                    @click="prev()"
                    class="motion-button inline-flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 bg-white text-[var(--color-brand-blue)] shadow-sm transition hover:-translate-y-0.5 hover:bg-blue-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
            </div>

            <div class="min-w-0 flex-1">
                <div
                    x-transition.opacity.duration.300ms
                    :key="activeIndex"
                    class="grid items-stretch gap-6 sm:grid-cols-2 xl:grid-cols-4">

                    <template x-for="(item, index) in visibleItems()" :key="activeIndex + '-' + index + '-' + item.title">
                        <div class="group motion-card flex h-full flex-col rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-sm transition duration-300 hover:-translate-y-1 hover:border-blue-100 hover:shadow-md">
                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-[var(--color-brand-blue)] transition duration-300 group-hover:bg-[#fff6da] group-hover:text-[var(--color-brand-gold)]">
                                <template x-if="item.icon_url">
                                    <img
                                        :src="item.icon_url"
                                        :alt="item.title"
                                        class="h-6 w-6 object-contain">
                                </template>

                                <template x-if="!item.icon_url">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3l7 4v5c0 5-3.5 8-7 9-3.5-1-7-4-7-9V7l7-4z" />
                                    </svg>
                                </template>
                            </div>

                            <h3
                                x-text="item.title"
                                class="mt-6 line-clamp-2 min-h-[3rem] text-base font-bold leading-tight text-slate-900 md:text-lg">
                            </h3>

                            <p
                                x-text="item.description"
                                class="mt-3 line-clamp-4 text-sm leading-7 text-slate-500">
                            </p>
                        </div>
                    </template>
                </div>
            </div>

            <div class="hidden sm:block">
                <button
                    type="button"
                    x-show="total > visibleCount"
                    @click="next()"
                    class="motion-button inline-flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 bg-white text-[var(--color-brand-blue)] shadow-sm transition hover:-translate-y-0.5 hover:bg-blue-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
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
</section>