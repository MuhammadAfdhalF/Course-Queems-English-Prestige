@extends('layouts.admin', [
'pageTitle' => 'Certificate Settings',
'pageSubtitle' => 'Certificate Management',
])

@section('content')
<section class="mx-auto max-w-5xl space-y-6">
    <x-admin.page-toolbar
        :back-url="route('admin.course-management.certificates.index')"
        back-label="Back to Certificates" />

    <x-admin.flash-message />

    <x-admin.table-card class="p-6">
        <div class="grid gap-6 lg:grid-cols-[1fr_320px] lg:items-start">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                    Global Signature
                </p>

                <h1 class="mt-2 text-2xl font-extrabold text-slate-900">
                    Certificate Settings
                </h1>

                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
                    Manage the global signature used on every issued certificate. Certificate backgrounds are managed separately through Certificate Templates.
                </p>
            </div>

            <div class="rounded-2xl border border-blue-100 bg-blue-50 px-4 py-3">
                <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-blue-500">
                    One Signature
                </p>

                <p class="mt-2 text-sm leading-6 text-blue-700">
                    This signer information will be used for all certificate PDF files and certificate previews.
                </p>
            </div>
        </div>
    </x-admin.table-card>

    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_360px]">
        <x-admin.table-card class="p-6">
            <form
                id="certificateSettingsForm"
                action="{{ route('admin.course-management.certificate-settings.update') }}"
                method="POST"
                enctype="multipart/form-data"
                class="space-y-6">
                @csrf
                @method('PUT')

                <x-admin.form.input
                    label="Signer Name"
                    name="signer_name"
                    id="signer_name"
                    :value="old('signer_name', $setting->signerName())"
                    placeholder="Example: Queens English Prestige" />

                <x-admin.form.input
                    label="Signer Title"
                    name="signer_title"
                    id="signer_title"
                    :value="old('signer_title', $setting->signerTitle())"
                    placeholder="Example: Authorized Signature" />

                @if ($setting->signature_image)
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">
                        Current Signature Image
                    </label>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                        <img
                            src="{{ asset('storage/' . $setting->signature_image) }}"
                            alt="{{ $setting->signerName() }}"
                            class="mx-auto h-28 w-full object-contain">
                    </div>
                </div>
                @endif

                <x-admin.form.file
                    label="{{ $setting->signature_image ? 'Replace Signature Image' : 'Signature Image' }}"
                    name="signature_image"
                    id="signature_image"
                    accept="image/*"
                    hint="Upload a transparent PNG signature if available. Max 2MB." />
            </form>

            <div class="mt-6 flex justify-end">
                <button
                    type="submit"
                    form="certificateSettingsForm"
                    class="inline-flex items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
                    Save Settings
                </button>
            </div>
        </x-admin.table-card>

        <x-admin.table-card class="p-6">
            <h2 class="text-lg font-extrabold text-slate-900">
                Signature Preview
            </h2>

            <p class="mt-2 text-sm leading-6 text-slate-500">
                This is how the signer area will appear conceptually on certificates.
            </p>

            <div class="mt-6 rounded-[24px] border border-slate-200 bg-[#fffdf6] p-6 text-center">
                @if ($setting->signature_image)
                <img
                    src="{{ asset('storage/' . $setting->signature_image) }}"
                    alt="{{ $setting->signerName() }}"
                    class="mx-auto h-20 w-full object-contain">
                @else
                <div class="mx-auto h-20 max-w-[240px]"></div>
                @endif

                <div class="mx-auto mt-3 h-[2px] max-w-[240px] bg-slate-500"></div>

                <p class="mt-3 text-sm font-black text-slate-900">
                    {{ $setting->signerName() }}
                </p>

                <p class="mt-1 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">
                    {{ $setting->signerTitle() }}
                </p>
            </div>

            <div class="mt-5 rounded-2xl border border-amber-100 bg-amber-50 px-4 py-3">
                <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-amber-500">
                    Fallback
                </p>

                <p class="mt-2 text-sm leading-6 text-amber-700">
                    If no image is uploaded, certificates will still show the signature line, signer name, and signer title.
                </p>
            </div>
        </x-admin.table-card>
    </div>
</section>
@endsection