@props([
'status' => 'Academic Status: Active',
'name' => 'Alex Johnson',
'description' => "Ready to continue your English journey? You’re doing great! You’ve completed 65% of your current goals this month.",
'buttonText' => 'Continue Learning',
])

<div {{ $attributes->merge(['class' => 'rounded-[24px] bg-gradient-to-r from-[#1847D1] via-[#2457E6] to-[#4F78D8] p-7 text-white shadow-sm']) }}>
    <div class="max-w-xl">
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
            <x-ui.button variant="secondary" class="px-5 py-2.5">
                {{ $buttonText }}
            </x-ui.button>
        </div>
    </div>
</div>