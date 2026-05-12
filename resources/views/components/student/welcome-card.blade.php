@props([
'status' => 'Academic Status: Active',
'name' => 'Alex Johnson',
'description' => "Ready to continue your English journey? You’re doing great! You’ve completed 65% of your current goals this month.",
'buttonText' => 'Continue Learning',
'href' => null,
])

<div {{ $attributes->merge(['class' => 'relative overflow-hidden rounded-[24px] bg-gradient-to-r from-[#1847D1] via-[#2457E6] to-[#4F78D8] p-7 text-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg']) }}>
    <div class="pointer-events-none absolute -right-10 -top-10 h-32 w-32 rounded-full bg-white/10 blur-2xl"></div>
    <div class="pointer-events-none absolute -bottom-8 left-10 h-24 w-24 rounded-full bg-white/10 blur-2xl"></div>

    <div class="relative max-w-xl">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-blue-100">
            {{ $status }}
        </p>

        <h2 class="mt-4 text-4xl font-bold leading-tight lg:text-[42px]">
            Hi, {{ $name }}!
        </h2>

        <p class="mt-4 max-w-xl text-base leading-8 text-blue-50">
            {{ $description }}
        </p>

        <div class="mt-7">
            @if ($href)
            <a
                href="{{ $href }}"
                class="motion-button inline-flex items-center justify-center rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-slate-900 transition-all duration-200 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-white/70 focus:ring-offset-2 focus:ring-offset-[#1847D1]">
                {{ $buttonText }}
            </a>
            @else
            <x-ui.button variant="secondary" class="px-5 py-2.5">
                {{ $buttonText }}
            </x-ui.button>
            @endif
        </div>
    </div>
</div>