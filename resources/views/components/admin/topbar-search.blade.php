<div {{ $attributes->merge(['class' => 'hidden items-center gap-2.5 rounded-xl border border-slate-200/80 bg-slate-50/80 px-3.5 py-2 transition focus-within:border-[#080D4D]/40 focus-within:bg-white focus-within:ring-2 focus-within:ring-[#080D4D]/10 md:flex md:w-[240px] lg:w-[280px]']) }}>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
    </svg>

    <input
        type="text"
        placeholder="Search Admin..."
        class="w-full bg-transparent text-xs font-medium text-slate-800 outline-none placeholder:text-slate-400"
    >
</div>