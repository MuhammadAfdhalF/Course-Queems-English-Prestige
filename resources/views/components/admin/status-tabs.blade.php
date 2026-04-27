@props([
    'tabs' => [],
    'model' => 'activeTab',
])

<div {{ $attributes->merge(['class' => 'border-b border-slate-200']) }}>
    <div class="flex flex-wrap gap-8">
        @foreach($tabs as $tab)
            <button
                type="button"
                @click="{{ $model }} = '{{ $tab['key'] }}'"
                class="relative pb-4 text-sm font-bold transition"
                :class="{{ $model }} === '{{ $tab['key'] }}'
                    ? 'text-[var(--color-brand-blue)]'
                    : 'text-slate-400 hover:text-slate-600'"
            >
                {{ $tab['label'] }}

                @if(isset($tab['count']))
                    <span
                        class="ml-2 rounded-lg px-2 py-0.5 text-xs"
                        :class="{{ $model }} === '{{ $tab['key'] }}'
                            ? 'bg-blue-50 text-[var(--color-brand-blue)]'
                            : 'bg-slate-100 text-slate-400'"
                    >
                        {{ $tab['count'] }}
                    </span>
                @endif

                <span
                    x-show="{{ $model }} === '{{ $tab['key'] }}'"
                    class="absolute bottom-0 left-0 h-[3px] w-full rounded-full bg-[var(--color-brand-gold)]">
                </span>
            </button>
        @endforeach
    </div>
</div>