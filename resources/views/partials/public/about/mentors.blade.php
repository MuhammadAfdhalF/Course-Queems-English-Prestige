<section class="bg-slate-50">
    <div class="mx-auto max-w-7xl px-4 py-16 lg:px-8">
        <div class="text-center">
            <h2 class="reveal text-2xl font-bold text-[var(--color-brand-blue)] md:text-3xl">
                Meet Our Lead Mentors
            </h2>

            <p class="reveal reveal-delay-1 mt-3 text-sm text-slate-500">
                World-class experts dedicated to your success.
            </p>
        </div>

        @if (($mentors ?? collect())->isNotEmpty())
        <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-5">
            @foreach ($mentors as $mentor)
            @php
            $delayClass = match ($loop->index % 5) {
            1 => 'reveal-delay-1',
            2 => 'reveal-delay-2',
            3 => 'reveal-delay-3',
            4 => 'reveal-delay-4',
            default => '',
            };

            $mentorPhoto = $mentor->photo
            ? asset('storage/' . $mentor->photo)
            : 'https://placehold.co/500x650/F1F5F9/0F172A?text=' . urlencode($mentor->name);
            @endphp

            <div class="reveal {{ $delayClass }} group motion-card overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="aspect-[4/5] overflow-hidden bg-slate-100">
                    <img
                        src="{{ $mentorPhoto }}"
                        alt="{{ $mentor->name }}"
                        class="motion-image h-full w-full object-cover">
                </div>

                <div class="p-4">
                    <h3 class="text-base font-bold text-slate-900">
                        {{ $mentor->name }}
                    </h3>

                    <p class="mt-1 text-xs font-semibold text-[var(--color-brand-blue)]">
                        {{ $mentor->position ?: $mentor->expertise ?: 'Mentor' }}
                    </p>

                    @if ($mentor->expertise && $mentor->position)
                    <p class="mt-1 text-[11px] font-semibold text-[var(--color-brand-gold)]">
                        {{ $mentor->expertise }}
                    </p>
                    @endif

                    <p class="mt-3 text-xs leading-6 text-slate-500">
                        {{ $mentor->description ?: 'Dedicated mentor committed to helping learners grow with confidence.' }}
                    </p>
                </div>
            </div>
            @endforeach
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