@props([
    'duration' => 3500,
])

@if (session('success') || session('error') || $errors->any())
    <div
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, {{ $duration }})"
        x-show="show"
        x-transition.opacity.duration.300ms
        class="space-y-3">

        @if (session('success'))
            <div class="flex items-start justify-between gap-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">
                <div class="flex items-start gap-3">
                    <span class="mt-0.5 inline-flex h-5 w-5 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                        <x-admin.icon name="check" class="h-3.5 w-3.5" />
                    </span>

                    <p>
                        {{ session('success') }}
                    </p>
                </div>

                <button
                    type="button"
                    @click="show = false"
                    class="rounded-lg p-1 text-emerald-600 transition hover:bg-emerald-100">
                    <x-admin.icon name="x" class="h-4 w-4" />
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="flex items-start justify-between gap-4 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-semibold text-rose-700">
                <div class="flex items-start gap-3">
                    <span class="mt-0.5 inline-flex h-5 w-5 items-center justify-center rounded-full bg-rose-100 text-rose-700">
                        <x-admin.icon name="x" class="h-3.5 w-3.5" />
                    </span>

                    <p>
                        {{ session('error') }}
                    </p>
                </div>

                <button
                    type="button"
                    @click="show = false"
                    class="rounded-lg p-1 text-rose-600 transition hover:bg-rose-100">
                    <x-admin.icon name="x" class="h-4 w-4" />
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div class="flex items-start justify-between gap-4 rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm font-semibold text-amber-700">
                <div class="flex items-start gap-3">
                    <span class="mt-0.5 inline-flex h-5 w-5 items-center justify-center rounded-full bg-amber-100 text-amber-700">
                        !
                    </span>

                    <p>
                        Periksa kembali form yang kamu isi.
                    </p>
                </div>

                <button
                    type="button"
                    @click="show = false"
                    class="rounded-lg p-1 text-amber-600 transition hover:bg-amber-100">
                    <x-admin.icon name="x" class="h-4 w-4" />
                </button>
            </div>
        @endif
    </div>
@endif