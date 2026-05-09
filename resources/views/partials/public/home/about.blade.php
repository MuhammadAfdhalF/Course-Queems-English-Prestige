@php
$aboutTitle = $aboutUs?->title ?: 'About Queens English Prestige';

$aboutDescription = $aboutUs?->description;

$fallbackBenefits = collect([
[
'title' => 'Online & Offline Options',
'description' => 'Learn through online modules or join guided offline sessions.',
],
[
'title' => 'Speaking-Focused Practice',
'description' => 'Practical drills to build fluency, pronunciation, and confidence.',
],
[
'title' => 'Structured Learning Path',
'description' => 'Clear levels, milestones, and a step-by-step curriculum.',
],
[
'title' => 'Exercises & Progress Tracking',
'description' => 'Practice after each lesson with measurable progress.',
],
[
'title' => 'Final Assessment & Certificate',
'description' => 'Complete the course, pass the exam, and earn your certificate.',
],
[
'title' => 'Easy Enrollment via WhatsApp',
'description' => 'Fast confirmation for online or offline orders and scheduling.',
],
]);

$benefitItems = ($whyChooseUsItems ?? collect())->isNotEmpty()
? $whyChooseUsItems
: $fallbackBenefits;
@endphp

<section class="bg-white">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-16 lg:grid-cols-[1.05fr_1fr] lg:px-8">
        <div class="max-w-xl">
            <h2 class="reveal text-2xl font-bold leading-tight text-[var(--color-brand-navy)] md:text-3xl">
                {{ $aboutTitle }}
            </h2>

            <div class="reveal reveal-delay-1 mt-5 space-y-5 text-sm leading-8 text-slate-600 md:text-base">
                @if (filled($aboutDescription))
                <div class="rich-text-content">
                    {!! $aboutDescription !!}
                </div>
                @else
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

        <div class="grid gap-x-10 gap-y-8 sm:grid-cols-2">
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
            @endphp

            <div class="reveal {{ $delayClass }} flex items-start gap-3">
                <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-md bg-[var(--color-brand-blue-soft)] text-[var(--color-brand-blue)]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3l7 4v5c0 5-3.5 8-7 9-3.5-1-7-4-7-9V7l7-4z" />
                    </svg>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-[var(--color-brand-navy)] md:text-base">
                        {{ $title }}
                    </h3>

                    <p class="mt-1 text-xs leading-6 text-slate-500 md:text-sm">
                        {{ $description }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>