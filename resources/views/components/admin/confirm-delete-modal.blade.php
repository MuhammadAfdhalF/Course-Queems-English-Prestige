@props([
'model' => 'deleteModalOpen',
'title' => 'Delete Data',
'subtitle' => 'This action cannot be undone.',
'itemName' => 'selectedItem.title',
'formId' => 'deleteForm',
'formAction' => 'selectedItem.delete_url',
'message' => 'Are you sure you want to delete this data?',
])

<x-admin.modal
    :model="$model"
    :title="$title"
    :subtitle="$subtitle"
    size="sm">
    <div class="space-y-4">
        <p class="text-sm leading-6 text-slate-600">
            {{ $message }}
        </p>

        <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-rose-400">
                Selected Data
            </p>

            <p
                class="mt-1 text-sm font-bold text-rose-700"
                x-text="{{ $itemName }}">
            </p>
        </div>

        <p class="text-sm leading-6 text-slate-500">
            This will permanently remove the selected record from the system.
        </p>

        <form
            id="{{ $formId }}"
            :action="{{ $formAction }}"
            method="POST">
            @csrf
            @method('DELETE')
        </form>
    </div>

    <x-slot:footer>
        <button
            type="button"
            @click="{{ $model }} = false"
            class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
            Cancel
        </button>

        <button
            type="submit"
            form="{{ $formId }}"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-rose-600 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-rose-700">
            <x-admin.icon name="trash" class="h-4 w-4" />
            <span>Delete</span>
        </button>
    </x-slot:footer>
</x-admin.modal>