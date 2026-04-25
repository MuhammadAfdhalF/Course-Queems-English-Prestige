<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 rounded-full border border-[#eadca6] bg-[#f8efcf] px-5 py-2.5 text-sm font-bold text-slate-900']) }}>
    @isset($icon)
        <span class="text-slate-700">
            {{ $icon }}
        </span>
    @endisset

    <span>{{ $slot }}</span>
</span>