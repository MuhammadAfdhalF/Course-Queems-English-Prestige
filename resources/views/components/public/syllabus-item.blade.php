@props([
    'title' => 'Module Title',
    'content' => 'Module description.',
    'open' => false,
    'isPreview' => false,
    'previewHref' => null,
    'learningHref' => null,
    'hasActiveEnrollment' => false,
])

<div x-data="{ open: @js($open) }" class="rounded-2xl border border-slate-200 bg-white shadow-sm">
    <button
        type="button"
        @click="open = !open"
        class="flex w-full items-center justify-between gap-4 px-5 py-4 text-left">
        <span class="flex min-w-0 flex-wrap items-center gap-2 text-lg font-bold text-[#080D4D]">
            <span>{{ $title }}</span>

            @if ($isPreview)
            <span class="inline-flex rounded-full bg-emerald-50 border border-emerald-200/80 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-emerald-700">
                Preview
            </span>
            @endif
        </span>

        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5 shrink-0 text-slate-400 transition-transform"
            :class="open ? 'rotate-180' : ''"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="open" x-transition class="border-t border-slate-200 px-5 pb-5 pt-4">
        <p class="text-sm leading-7 text-slate-600">
            {{ $content }}
        </p>

        @if ($hasActiveEnrollment && $learningHref)
        <div class="mt-4">
            <a
                href="{{ $learningHref }}"
                class="inline-flex items-center gap-1.5 rounded-xl bg-[#080D4D] px-4 py-2 text-xs font-bold text-white shadow-2xs transition hover:bg-[#060A3B]">
                <span>Continue Learning</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        </div>
        @elseif ($isPreview && $previewHref)
        <div class="mt-4">
            <a
                href="{{ $previewHref }}"
                class="inline-flex items-center gap-1.5 rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-2 text-xs font-bold text-emerald-800 shadow-2xs transition hover:bg-emerald-100">
                <span>Read Preview</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        </div>
        @endif
    </div>
</div>