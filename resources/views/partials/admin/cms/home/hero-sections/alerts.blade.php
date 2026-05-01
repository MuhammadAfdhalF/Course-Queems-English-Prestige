@if (session('success'))
    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-semibold text-rose-700">
        Periksa kembali form yang kamu isi.
    </div>
@endif