@props([
'colspan' => 1,
'title' => 'No data found',
'description' => null,
])

<tr>
    <td colspan="{{ $colspan }}" class="px-6 py-14 text-center">
        <div class="mx-auto flex max-w-md flex-col items-center">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                <x-admin.icon name="image" class="h-7 w-7" />
            </div>

            <h3 class="mt-4 text-base font-bold text-slate-900">
                {{ $title }}
            </h3>

            @if ($description)
            <p class="mt-2 text-sm leading-6 text-slate-500">
                {{ $description }}
            </p>
            @endif
        </div>
    </td>
</tr>