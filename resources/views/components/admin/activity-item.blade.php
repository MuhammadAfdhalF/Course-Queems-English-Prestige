@props([
    'initials' => 'SJ',
    'name' => 'Sarah Johnson',
    'description' => 'Purchased Business English Advanced',
    'variant' => 'pending',
    'avatar' => 'rose',
])

@php
    $avatarMap = [
        'rose' => 'bg-rose-100 text-rose-700',
        'blue' => 'bg-blue-100 text-blue-700',
        'green' => 'bg-emerald-100 text-emerald-700',
        'amber' => 'bg-amber-100 text-amber-700',
    ];

    $avatarClass = $avatarMap[$avatar] ?? $avatarMap['blue'];
@endphp

<div class="flex items-start gap-3">
    <div class="flex h-11 w-11 items-center justify-center rounded-full text-sm font-semibold {{ $avatarClass }}">
        {{ $initials }}
    </div>

    <div class="min-w-0">
        <p class="font-semibold text-slate-900">{{ $name }}</p>
        <p class="text-sm text-slate-500">{{ $description }}</p>

        <div class="mt-2">
            <x-admin.status-badge :variant="$variant">
                {{ ucfirst($variant) }}
            </x-admin.status-badge>
        </div>
    </div>
</div>