<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 rounded-lg bg-[#201a12] px-3.5 py-2 text-xs font-semibold text-white']) }}>
    @isset($icon)
        <span class="text-white/90">
            {{ $icon }}
        </span>
    @endisset

    <span>{{ $slot }}</span>
</span>