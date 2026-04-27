<x-admin.table-card {{ $attributes }}>
    <div class="overflow-x-auto">
        <table class="min-w-full text-left">
            @isset($head)
                <thead class="border-b border-slate-200 bg-slate-50">
                    <tr class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
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
        <div class="border-t border-slate-200 px-6 py-5">
            {{ $footer }}
        </div>
    @endisset
</x-admin.table-card>