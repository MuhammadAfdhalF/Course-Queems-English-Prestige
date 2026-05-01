@props([
'name',
])

@php
$baseClass = $attributes->get('class', 'h-5 w-5');
@endphp

@switch($name)
@case('plus')
<svg {{ $attributes->merge(['class' => $baseClass]) }} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 5v14m-7-7h14" />
</svg>
@break

@case('pencil')
@case('edit')
<svg {{ $attributes->merge(['class' => $baseClass]) }} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16.862 4.487l1.651-1.651a2.121 2.121 0 113 3l-1.651 1.651M16.862 4.487L7.5 13.849V17.25h3.401l9.361-9.363M16.862 4.487l3 3" />
</svg>
@break

@case('trash')
@case('delete')
<svg {{ $attributes->merge(['class' => $baseClass]) }} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 7h12m-9 0V5a1.5 1.5 0 011.5-1.5h3A1.5 1.5 0 0115 5v2m2 0l-.75 12A2 2 0 0114.25 21h-4.5a2 2 0 01-2-2L7 7m3 4v6m4-6v6" />
</svg>
@break

@case('eye')
@case('view')
<svg {{ $attributes->merge(['class' => $baseClass]) }} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12z" />
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
</svg>
@break

@case('x')
@case('close')
<svg {{ $attributes->merge(['class' => $baseClass]) }} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12" />
</svg>
@break

@case('image')
<svg {{ $attributes->merge(['class' => $baseClass]) }} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <rect x="3" y="5" width="18" height="14" rx="2" stroke-width="1.8" />
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 13l2.5-2.5L14 14l1.5-1.5L19 16M8 9h.01" />
</svg>
@break

@case('check')
<svg {{ $attributes->merge(['class' => $baseClass]) }} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 13l4 4L19 7" />
</svg>
@break

@case('search')
<svg {{ $attributes->merge(['class' => $baseClass]) }} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z" />
</svg>
@break

@case('filter')
<svg {{ $attributes->merge(['class' => $baseClass]) }} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M7 12h10M10 18h4" />
</svg>
@break

@case('arrow-left')
@case('back')
<svg {{ $attributes->merge(['class' => $baseClass]) }} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19l-7-7 7-7" />
</svg>
@break

@default
<svg {{ $attributes->merge(['class' => $baseClass]) }} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <circle cx="12" cy="12" r="9" stroke-width="1.8" />
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4m0 4h.01" />
</svg>
@endswitch