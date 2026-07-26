@props([
    'content' => '',
    'limit' => null,
    'fallback' => '',
    'as' => 'span',
    'class' => ''
])

@php
    $plain = \App\Support\RichText::toPlainText($content, $limit);
@endphp

<{{ $as }} {{ $attributes->merge(['class' => $class]) }}>
    {{ $plain !== '' ? $plain : $fallback }}
</{{ $as }}>
