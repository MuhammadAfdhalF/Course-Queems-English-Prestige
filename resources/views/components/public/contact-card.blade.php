@props([
'title' => 'WhatsApp',
'description' => 'Contact description',
'value' => '',
'secondaryValue' => null,
'href' => '#',
'featured' => false,
'isEmail' => false,
])

@php
$cardClasses = $featured
? 'border-[#eadca6] bg-white shadow-[0_10px_24px_rgba(212,160,23,0.08)]'
: 'border-[#d9e1ec] bg-white shadow-[0_4px_14px_rgba(15,23,42,0.05)] hover:shadow-[0_10px_24px_rgba(15,23,42,0.08)]';

$iconClasses = $featured
? 'bg-[#fbf4de] text-[var(--color-brand-gold)]'
: 'bg-[#eef4ff] text-[var(--color-brand-blue)]';

$valueClasses = $isEmail
? 'text-[14px] leading-6'
: 'text-[15px] leading-7';
@endphp

<a
    href="{{ $href }}"
    target="_blank"
    rel="noopener noreferrer"
    {{ $attributes->merge(['class' => 'group relative block min-h-[210px] rounded-[22px] border p-6 transition duration-200 hover:-translate-y-1 ' . $cardClasses]) }}>

    @if($featured)
    <span class="absolute right-4 top-4 inline-flex items-center rounded-md bg-[var(--color-brand-gold)] px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.12em] text-white">
        Primary
    </span>
    @endif

    <div class="flex h-11 w-11 items-center justify-center rounded-[16px] {{ $iconClasses }}">
        {{ $icon ?? '' }}
    </div>

    <div class="mt-6">
        <h3 class="text-[20px] font-bold leading-tight text-slate-900">
            {{ $title }}
        </h3>

        <p class="mt-4 max-w-[26ch] text-sm leading-8 text-slate-500">
            {{ $description }}
        </p>

        @if($value)
        @if($isEmail)
        <div class="mt-6 font-bold text-[var(--color-brand-blue)] group-hover:underline {{ $valueClasses }}">
            <span class="block">hello@quue</span>
        </div>
        @else
        <p class="mt-6 font-bold text-[var(--color-brand-blue)] group-hover:underline {{ $valueClasses }}">
            {{ $value }}
        </p>
        @endif
        @endif

        @if($secondaryValue)
        <p class="mt-1 text-[15px] font-bold leading-7 break-all text-[var(--color-brand-blue)] group-hover:underline">
            {{ $secondaryValue }}
        </p>
        @endif
    </div>
</a>