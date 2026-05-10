<section class="bg-[#f8f8f6]">
    <div class="mx-auto max-w-7xl px-4 pt-8 lg:px-8 lg:pt-10">
        <div class="reveal border-b border-slate-200"></div>
        <div class="flex flex-wrap items-center gap-6 text-sm font-medium">
            <a
                href="{{ route('news') }}"
                class="{{ blank($selectedType ?? null)
                        ? 'inline-flex border-b-2 border-[#2457E6] pb-4 font-bold text-[#2457E6] transition-colors duration-200'
                        : 'inline-flex pb-4 text-slate-500 transition-colors duration-200 hover:text-[var(--color-brand-blue)]' }}">
                All News
            </a>

            @foreach (($types ?? collect()) as $typeValue => $typeLabel)
            <a
                href="{{ route('news', ['type' => $typeValue]) }}"
                class="{{ ($selectedType ?? null) === $typeValue
                            ? 'inline-flex border-b-2 border-[#2457E6] pb-4 font-bold text-[#2457E6] transition-colors duration-200'
                            : 'inline-flex pb-4 text-slate-500 transition-colors duration-200 hover:text-[var(--color-brand-blue)]' }}">
                {{ $typeLabel }}
            </a>
            @endforeach
        </div>
    </div>
    </div>
</section>