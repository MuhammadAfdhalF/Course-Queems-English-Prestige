@php
$learningModeLabel = match ($courseLevel->learning_mode) {
'offline' => 'Offline',
'hybrid' => 'Hybrid',
default => 'Online',
};

$accessLabel = $courseLevel->access_type === 'limited'
? ($courseLevel->access_duration_days . ' days access')
: 'Lifetime access';

$thumbnailUrl = $courseLevel->thumbnail_file
? asset('storage/' . $courseLevel->thumbnail_file)
: 'https://placehold.co/800x500/EEF3FF/2457E6?text=Queens+English';
@endphp

<aside class="reveal lg:sticky lg:top-24">
    <div class="motion-card rounded-[22px] border border-slate-200 bg-white p-4 shadow-sm">
        <div class="overflow-hidden rounded-[18px] bg-slate-100">
            @if ($courseLevel->thumbnail_file && $courseLevel->thumbnail_type === 'video')
            <video
                src="{{ $thumbnailUrl }}"
                controls
                class="motion-image h-[165px] w-full bg-slate-900 object-cover">
            </video>
            @else
            <img
                src="{{ $thumbnailUrl }}"
                alt="{{ $courseLevel->name }}"
                class="motion-image h-[165px] w-full object-cover">
            @endif
        </div>

        <div class="mt-4">
            <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">
                Course Tuition
            </p>

            <div class="mt-2">
                <p class="text-[34px] font-bold leading-[0.95] text-[#D4A017] lg:text-[38px]">
                    Rp {{ number_format((float) $courseLevel->price, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <div class="mt-6 space-y-3.5">
            <div class="reveal flex items-center gap-3 text-slate-700">
                <div class="flex h-4 w-4 items-center justify-center text-[#2457E6]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <rect x="5" y="3" width="14" height="18" rx="2" stroke-width="1.8" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7h8M8 11h8M8 15h5" />
                    </svg>
                </div>
                <span class="text-[14px] font-semibold">
                    {{ $accessLabel }}
                </span>
            </div>

            <div class="reveal reveal-delay-1 flex items-center gap-3 text-slate-700">
                <div class="flex h-4 w-4 items-center justify-center text-[#2457E6]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3l7 4v5c0 5-3.5 8-7 9-3.5-1-7-4-7-9V7l7-4z" />
                    </svg>
                </div>
                <span class="text-[14px] font-semibold">
                    Structured learning modules
                </span>
            </div>

            <div class="reveal reveal-delay-2 flex items-center gap-3 text-slate-700">
                <div class="flex h-4 w-4 items-center justify-center text-[#2457E6]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        @if ($courseLevel->learning_mode === 'offline')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.5 10.5h15M5.25 6.75h13.5A1.5 1.5 0 0120.25 8.25v7.5a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5v-7.5a1.5 1.5 0 011.5-1.5z" />
                        @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8.111 16.404a5 5 0 017.778 0M5.636 13.929a8.5 8.5 0 0112.728 0M3.161 11.454a12 12 0 0117.678 0M12 20h.01" />
                        @endif
                    </svg>
                </div>
                <span class="text-[14px] font-semibold">
                    {{ $learningModeLabel }} learning
                </span>
            </div>
        </div>

        <div class="reveal reveal-delay-3 mt-6">
            <a href="{{ route('contact') }}">
                <x-ui.button class="w-full justify-center px-5 py-3 text-sm font-bold">
                    Pesan Sekarang
                </x-ui.button>
            </a>
        </div>

        <p class="reveal reveal-delay-4 mx-auto mt-4 max-w-[250px] text-center text-xs leading-6 text-slate-500">
            *Our admin will contact you via WhatsApp for further process and schedule selection.
        </p>

        <div class="reveal reveal-delay-4 mt-5 border-t border-slate-200 pt-4">
            <div class="flex items-center justify-center gap-3 text-slate-400">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 transition-colors duration-200 hover:bg-slate-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.325 4.317a1 1 0 011.35-.936l.94.47a1 1 0 00.894 0l.94-.47a1 1 0 011.35.936l.094 1.04a1 1 0 00.592.823l.95.475a1 1 0 01.376 1.527l-.665.83a1 1 0 000 1.25l.665.83a1 1 0 01-.376 1.527l-.95.475a1 1 0 00-.592.823l-.094 1.04a1 1 0 01-1.35.936l-.94-.47a1 1 0 00-.894 0l-.94.47a1 1 0 01-1.35-.936l-.094-1.04a1 1 0 00-.592-.823l-.95-.475a1 1 0 01-.376-1.527l.665-.83a1 1 0 000-1.25l-.665-.83a1 1 0 01.376-1.527l.95-.475a1 1 0 00.592-.823l.094-1.04z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </span>

                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 transition-colors duration-200 hover:bg-slate-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3l7 4v5c0 5-3.5 8-7 9-3.5-1-7-4-7-9V7l7-4z" />
                    </svg>
                </span>

                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 transition-colors duration-200 hover:bg-slate-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <circle cx="12" cy="12" r="9" stroke-width="1.8" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4" />
                    </svg>
                </span>
            </div>
        </div>
    </div>
</aside>