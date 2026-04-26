@props([
'title' => 'TOEFL Preparation Mastery',
'level' => 'Intermediate (B1)',
'progress' => 33,
'modulesCompleted' => '2 of 6 modules',
])

<div class="rounded-[24px] border border-slate-200 bg-white px-6 py-6 shadow-sm lg:px-8">
    <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
        <div class="min-w-0 flex-1">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center">
                <h1 class="text-4xl font-bold leading-tight text-slate-900">
                    {{ $title }}
                </h1>

                <span class="inline-flex items-center self-start rounded-full border border-yellow-200 bg-yellow-50 px-4 py-1.5 text-sm font-semibold text-[var(--color-brand-gold)]">
                    {{ $level }}
                </span>
            </div>

            <div class="mt-6 flex flex-col gap-3 lg:flex-row lg:items-center">
                <div class="min-w-[230px]">
                    <div class="mb-2 flex items-center justify-between text-sm font-semibold text-slate-600">
                        <span>Course Progress</span>
                        <span class="text-[var(--color-brand-blue)]">{{ $progress }}% Completed</span>
                    </div>

                    <div class="h-3 overflow-hidden rounded-full bg-slate-200">
                        <div
                            class="h-full rounded-full bg-[var(--color-brand-gold)] transition-all duration-700 ease-out"
                            style="width: {{ $progress }}%"></div>
                    </div>
                </div>

                <p class="text-lg font-semibold text-slate-500">
                    {{ $modulesCompleted }}
                </p>
            </div>
        </div>

        <div class="shrink-0">
            <a
                href="#"
                class="inline-flex h-14 items-center justify-center gap-2 rounded-xl bg-[var(--color-brand-blue)] px-8 text-lg font-bold text-white shadow-md transition hover:opacity-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 5v14l11-7-11-7z" />
                </svg>
                Continue Learning
            </a>
        </div>
    </div>
</div>