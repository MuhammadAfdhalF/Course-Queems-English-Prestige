@php
$aboutTitle = $aboutUs?->title ?: 'About Queens English Prestige';

$aboutDescription = $aboutUs?->description;

$fallbackBenefits = collect([
[
'title' => 'Online & Offline Options',
'description' => 'Learn through online modules or join guided offline sessions.',
'icon' => null,
],
[
'title' => 'Speaking-Focused Practice',
'description' => 'Practical drills to build fluency, pronunciation, and confidence.',
'icon' => null,
],
[
'title' => 'Structured Learning Path',
'description' => 'Clear levels, milestones, and a step-by-step curriculum.',
'icon' => null,
],
[
'title' => 'Exercises & Progress Tracking',
'description' => 'Practice after each lesson with measurable progress.',
'icon' => null,
],
[
'title' => 'Final Assessment & Certificate',
'description' => 'Complete the course, pass the exam, and earn your certificate.',
'icon' => null,
],
[
'title' => 'Easy Enrollment via WhatsApp',
'description' => 'Fast confirmation for online or offline orders and scheduling.',
'icon' => null,
],
]);

$benefitItems = ($whyChooseUsItems ?? collect())->isNotEmpty()
? $whyChooseUsItems->values()
: $fallbackBenefits;

$totalBenefits = $benefitItems->count();
@endphp

<section class="bg-white">
    <div
        x-data="{
            activeIndex: 0,
            total: {{ $totalBenefits }},
            visibleCount: 6,
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
                    this.visibleCount = 2;
                } else if (window.innerWidth < 1024) {
                    this.visibleCount = 4;
                } else {
                    this.visibleCount = 6;
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

            startAutoPlay() {
                if (this.total <= this.visibleCount || this.timer) {
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

            goTo(index) {
                this.activeIndex = index;
                this.stopAutoPlay();
                this.startAutoPlay();
            }
        }"
        @mouseenter="stopAutoPlay()"
        @mouseleave="startAutoPlay()"
        class="mx-auto grid max-w-7xl items-center gap-10 px-4 py-16 lg:grid-cols-[1.02fr_0.98fr] lg:px-8">

        <div class="max-w-xl">
            <x-public.section-title class="reveal text-2xl md:text-3xl">
                {{ $aboutTitle }}
            </x-public.section-title>

            <div class="reveal reveal-delay-1 mt-5 text-sm leading-8 text-slate-600 md:text-base">
                @if (filled($aboutDescription))
                <div class="whitespace-pre-line">
                    {{ $aboutDescription }}
                </div>
                @else
                <div class="space-y-5">
                    <p>
                        Queens English Prestige provides premium English learning through
                        online courses and offline sessions, designed for learners who want
                        real progress in speaking, grammar, and test preparation.
                    </p>

                    <p>
                        Choose your program, select Online or Offline, and complete your order
                        through our website. Our team will confirm the details via WhatsApp
                        to get you started quickly.
                    </p>
                </div>
                @endif
            </div>

            <div class="reveal reveal-delay-2 mt-7">
                <a href="{{ route('about') }}">
                    <x-ui.button class="bg-[var(--color-brand-blue)] hover:opacity-90">
                        Learn More
                    </x-ui.button>
                </a>
            </div>
        </div>

        <div class="lg:place-self-center">
            <div class="grid gap-x-12 gap-y-10 sm:grid-cols-2">
                @foreach ($benefitItems as $index => $item)
                @php
                $delayClass = match ($index % 5) {
                1 => 'reveal-delay-1',
                2 => 'reveal-delay-2',
                3 => 'reveal-delay-3',
                4 => 'reveal-delay-4',
                default => '',
                };

                $title = is_array($item) ? $item['title'] : $item->title;
                $description = is_array($item) ? $item['description'] : $item->description;
                $icon = is_array($item) ? ($item['icon'] ?? null) : $item->icon;
                @endphp

                <div
                    x-show="isVisible({{ $index }})"
                    x-transition.opacity.duration.300ms
                    class="reveal {{ $delayClass }} flex items-start gap-3">
                    <x-public.why-icon
                        :icon="$icon"
                        class="mt-0.5 h-8 w-8 rounded-md" />

                    <div class="min-w-0">
                        <h3 class="text-sm font-bold leading-snug text-[var(--color-brand-navy)] md:text-[15px]">
                            {{ $title }}
                        </h3>

                        <p class="mt-1.5 line-clamp-2 text-xs leading-6 text-slate-500 md:text-sm md:leading-6">
                            {{ $description }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>

            <div
                x-show="total > visibleCount"
                class="mt-8 flex items-center justify-center gap-3">
                <button
                    type="button"
                    @click="prev()"
                    class="motion-button inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 bg-white text-[var(--color-brand-blue)] shadow-sm transition hover:bg-blue-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <div class="flex items-center gap-2">
                    @foreach ($benefitItems as $index => $item)
                    <button
                        type="button"
                        @click="goTo({{ $index }})"
                        class="h-2 rounded-full transition-all duration-300"
                        :class="activeIndex === {{ $index }} ? 'w-7 bg-[var(--color-brand-gold)]' : 'w-2 bg-slate-300 hover:bg-slate-400'">
                    </button>
                    @endforeach
                </div>

                <button
                    type="button"
                    @click="next()"
                    class="motion-button inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 bg-white text-[var(--color-brand-blue)] shadow-sm transition hover:bg-blue-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</section>