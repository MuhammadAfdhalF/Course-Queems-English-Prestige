@props([
    'content' => '',
    'as' => 'div',
    'class' => 'rich-text-content text-xs sm:text-sm text-slate-700 leading-relaxed'
])

@if (!empty($content))
    <{{ $as }} {{ $attributes->merge(['class' => $class]) }}>
        {!! $content !!}
    </{{ $as }}>
@endif
