@props([
'as' => 'h2',
])

@php
$rawTitle = trim($slot->toHtml());

$hasManualGold = str_contains($rawTitle, '[gold]') && str_contains($rawTitle, '[/gold]');

if ($hasManualGold) {
$formattedTitle = e($rawTitle);
$formattedTitle = str_replace('[br]', '<br>', $formattedTitle);
$formattedTitle = str_replace('[gold]', '<span class="text-[var(--color-brand-gold)]">', $formattedTitle);
    $formattedTitle = str_replace('[/gold]', '</span>', $formattedTitle);
} else {
$normalizedTitle = preg_replace('/\s+/', ' ', strip_tags($rawTitle));
$words = preg_split('/\s+/', trim($normalizedTitle));
$wordCount = count($words);

if ($wordCount <= 1) {
    $formattedTitle=e($normalizedTitle);
    } elseif ($wordCount===2) {
    $bluePart=$words[0];
    $goldPart=$words[1];

    $formattedTitle=e($bluePart) . ' <span class="text-[var(--color-brand-gold)]">' . e($goldPart) . '</span>' ;
    } else {
    $goldWords=array_slice($words, -2);
    $blueWords=array_slice($words, 0, -2);

    $bluePart=implode(' ', $blueWords);
        $goldPart = implode(' ', $goldWords);

        $formattedTitle = e($bluePart) . ' <span class="text-[var(--color-brand-gold)]">' . e($goldPart) . '</span>';
    }

    $formattedTitle = str_replace('[br]', '<br>', $formattedTitle);
    }
    @endphp

    <{{ $as }}
        {{ $attributes->merge([
        'class' => 'font-bold leading-tight text-[var(--color-brand-blue)]'
    ]) }}>
        {!! $formattedTitle !!}
    </{{ $as }}>