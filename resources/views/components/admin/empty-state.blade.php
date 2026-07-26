@props([
    'colspan' => null,
    'title' => 'No data found',
    'description' => null,
    'icon' => null,
])

@php
$content = function() use ($title, $description, $icon, $slot) {
    return '
    <div class="mx-auto flex max-w-sm flex-col items-center text-center p-6 sm:p-8">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
        </div>

        <h4 class="mt-3 text-xs font-bold text-slate-900">
            ' . e($title) . '
        </h4>

        ' . ($description ? '<p class="mt-1 text-[11px] font-medium text-slate-500 leading-relaxed">' . e($description) . '</p>' : '') . '

        ' . ($slot->isNotEmpty() ? '<div class="mt-4 flex flex-wrap justify-center gap-2">' . $slot . '</div>' : '') . '
    </div>';
};
@endphp

@if ($colspan !== null)
    <tr>
        <td colspan="{{ $colspan }}" {{ $attributes->merge(['class' => 'p-0']) }}>
            {!! $content() !!}
        </td>
    </tr>
@else
    <div {{ $attributes->merge(['class' => 'rounded-2xl border border-slate-200/90 bg-white shadow-2xs']) }}>
        {!! $content() !!}
    </div>
@endif