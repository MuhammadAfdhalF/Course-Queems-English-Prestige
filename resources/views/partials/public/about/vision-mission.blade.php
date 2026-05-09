@php
$visionText = $visionMission?->vision ?: 'To be a leading English learning provider that empowers learners to communicate confidently in global academic and professional settings.';
$missionItems = $visionMission?->missionItems ?? collect();

$fallbackMissions = [
'Deliver structured learning through modules, practice exercises, and clear course milestones.',
'Provide both online learning and offline sessions to match different learner preferences.',
'Offer a Free Test to help learners identify their level and choose the right program.',
'Support a smooth enrollment process with WhatsApp confirmation and certification upon completion.',
];
@endphp

<section class="bg-white">
    <div class="mx-auto grid max-w-7xl gap-12 px-4 py-16 lg:grid-cols-2 lg:px-8">
        <div class="reveal">
            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[var(--color-brand-gold)]">
                Our Vision
            </p>

            <h2 class="mt-8 max-w-xl text-3xl font-bold leading-tight text-[var(--color-brand-blue)] md:text-4xl">
                {{ $visionText }}
            </h2>
        </div>

        <div class="reveal reveal-delay-1">
            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[var(--color-brand-gold)]">
                Our Mission
            </p>

            <div class="mt-8 space-y-5">
                @if ($missionItems->isNotEmpty())
                @foreach ($missionItems as $item)
                @php
                $delayClass = match ($loop->index % 4) {
                1 => 'reveal-delay-1',
                2 => 'reveal-delay-2',
                3 => 'reveal-delay-3',
                default => '',
                };
                @endphp

                <div class="reveal {{ $delayClass }} flex items-start gap-3">
                    <div class="mt-1 flex h-5 w-5 items-center justify-center rounded-full border border-[var(--color-brand-gold)] text-[10px] text-[var(--color-brand-gold)]">
                        ✓
                    </div>
                    <p class="text-sm leading-7 text-slate-600">
                        {{ $item->content }}
                    </p>
                </div>
                @endforeach
                @else
                @foreach ($fallbackMissions as $mission)
                @php
                $delayClass = match ($loop->index % 4) {
                1 => 'reveal-delay-1',
                2 => 'reveal-delay-2',
                3 => 'reveal-delay-3',
                default => '',
                };
                @endphp

                <div class="reveal {{ $delayClass }} flex items-start gap-3">
                    <div class="mt-1 flex h-5 w-5 items-center justify-center rounded-full border border-[var(--color-brand-gold)] text-[10px] text-[var(--color-brand-gold)]">
                        ✓
                    </div>
                    <p class="text-sm leading-7 text-slate-600">
                        {{ $mission }}
                    </p>
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>
</section>