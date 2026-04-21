@props([
'title' => 'You have a pending order',
'description' => 'Please complete your payment to access your course and start learning.',
'buttonText' => 'Chat Admin',
])

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-blue-100 bg-[#F6F8FF] px-5 py-5']) }}>
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div class="flex items-start gap-4">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-100 text-[var(--color-brand-blue)]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 9V7a5 5 0 00-10 0v2m-2 0h14l-1 10H6L5 9zm4 4h6" />
                </svg>
            </div>

            <div>
                <h3 class="text-lg font-bold text-slate-900">{{ $title }}</h3>
                <p class="mt-1 text-sm leading-6 text-slate-600">{{ $description }}</p>
            </div>
        </div>

        <div class="lg:shrink-0">
            <x-ui.button class="px-5 py-2.5">
                {{ $buttonText }}
            </x-ui.button>
        </div>
    </div>
</div>