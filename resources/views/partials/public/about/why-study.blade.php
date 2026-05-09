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
@endphp

<section class="bg-slate-50">
    <div class="mx-auto max-w-7xl px-4 py-16 lg:px-8">
        <div class="text-center">
            <x-public.section-title class="reveal text-2xl md:text-3xl">
                Why Study with [gold]Us?[/gold]
            </x-public.section-title>

            <div class="reveal reveal-delay-1 mx-auto mt-3 h-1 w-16 rounded-full bg-[var(--color-brand-gold)]"></div>
        </div>

        <div class="mx-auto mt-10 grid max-w-5xl gap-6 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($whyStudyItems as $index => $item)
            @php
            $delayClass = match ($index % 4) {
            1 => 'reveal-delay-1',
            2 => 'reveal-delay-2',
            3 => 'reveal-delay-3',
            default => '',
            };

            $title = is_array($item) ? $item['title'] : $item->title;
            $description = is_array($item) ? $item['description'] : $item->description;
            $icon = is_array($item) ? ($item['icon'] ?? null) : $item->icon;
            @endphp

            <div class="reveal {{ $delayClass }} motion-card rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-md">
                <x-public.why-icon
                    :icon="$icon"
                    class="mx-auto h-12 w-12 rounded-full" />

                <h3 class="mt-5 text-base font-bold text-slate-900">
                    {{ $title }}
                </h3>

                <p class="mt-3 text-sm leading-6 text-slate-500">
                    {{ $description }}
                </p>
            </div>
            @endforeach
        </div>
    </div>
</section>