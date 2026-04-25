@props([
'title' => 'Grammar',
'description' => 'Short description',
'href' => '#',
])

<a
    href="{{ $href }}"
    {{ $attributes->merge([
        'class' => 'group motion-card block rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-sm transition duration-300 hover:shadow-md'
    ]) }}>
    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-[var(--color-brand-blue)] transition-all duration-300 group-hover:bg-blue-100 group-hover:text-[var(--color-brand-blue)]">
        {{ $icon ?? '' }}
    </div>

    <h3 class="mt-5 text-xl font-bold text-slate-900">
        {{ $title }}
    </h3>

    <p class="mt-3 text-sm leading-7 text-slate-600">
        {{ $description }}
    </p>

    <span class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-[var(--color-brand-blue)]">
        <span>Start Now</span>
        <svg xmlns="http://www.w3.org/2000/svg" class="motion-link-arrow h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5l7 7-7 7" />
        </svg>
    </span>
</a>