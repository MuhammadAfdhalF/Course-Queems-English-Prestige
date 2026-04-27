<x-admin.table-card {{ $attributes->merge(['class' => 'overflow-hidden rounded-[20px]']) }}>
    <div class="overflow-x-auto">
        <table class="min-w-full text-left">
            @isset($head)
            <thead class="border-b border-slate-200 bg-slate-50">
                <tr class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-400">
                    {{ $head }}
                </tr>
            </thead>
            @endisset

            <tbody class="divide-y divide-slate-100 bg-white">
                {{ $slot }}
            </tbody>
        </table>
    </div>

    @isset($footer)
    <div class="border-t border-slate-200 bg-slate-50/70 px-6 py-5">
        {{ $footer }}
    </div>
    @endisset
</x-admin.table-card>