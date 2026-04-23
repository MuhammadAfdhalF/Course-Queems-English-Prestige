@props(['test'])

<section class="border-b border-slate-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 py-6 lg:px-8">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-start gap-3">
                <div class="mt-1 flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-[var(--color-brand-gold)]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v11.494m-5.747-8.62h11.494" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.5 5.25h15A2.25 2.25 0 0121.75 7.5v9A2.25 2.25 0 0119.5 18.75h-15A2.25 2.25 0 012.25 16.5v-9A2.25 2.25 0 014.5 5.25z" />
                    </svg>
                </div>

                <div>
                    <h1 class="text-2xl font-bold text-slate-900">
                        {{ $test['title'] }}
                    </h1>
                    <p class="mt-1 text-sm text-slate-500">
                        {{ $test['subtitle'] }}
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2 lg:justify-end">
                <x-public.test-meta-chip>
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <rect x="5" y="4" width="14" height="16" rx="2" stroke-width="1.8" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 8h6M9 12h6M9 16h4" />
                        </svg>
                    </x-slot:icon>
                    {{ $test['total_questions'] }} Questions
                </x-public.test-meta-chip>

                <x-public.test-meta-chip>
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l2.5 2.5" />
                            <circle cx="12" cy="12" r="9" stroke-width="1.8" />
                        </svg>
                    </x-slot:icon>
                    {{ $test['duration'] }}
                </x-public.test-meta-chip>

                <x-public.test-meta-chip>
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <rect x="4" y="5" width="16" height="14" rx="2" stroke-width="1.8" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 10h8M8 14h5" />
                        </svg>
                    </x-slot:icon>
                    {{ $test['requires_login'] ? 'Login required' : 'No login required' }}
                </x-public.test-meta-chip>

                <span class="inline-flex items-center rounded-lg bg-[var(--color-brand-gold)] px-4 py-2 text-[11px] font-extrabold uppercase tracking-[0.14em] text-slate-900">
                    Question {{ $test['current_question'] }} of {{ $test['total_questions'] }}
                </span>
            </div>
        </div>
    </div>
</section>