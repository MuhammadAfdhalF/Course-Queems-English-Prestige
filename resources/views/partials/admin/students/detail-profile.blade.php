@php
$initials = collect(explode(' ', trim($student->name)))
->filter()
->take(2)
->map(fn ($word) => strtoupper(substr($word, 0, 1)))
->implode('');

$initials = $initials ?: 'S';

$avatarUrl = $profile?->photo
? asset('storage/' . $profile->photo)
: null;
@endphp

<x-admin.table-card class="p-6">
    <div class="grid gap-6 lg:grid-cols-[320px_minmax(0,1fr)]">
        <div class="rounded-[24px] border border-slate-200 bg-slate-50 p-6 text-center">
            <div class="mx-auto flex h-28 w-28 items-center justify-center overflow-hidden rounded-full bg-blue-50 text-3xl font-black text-[var(--color-brand-blue)]">
                @if ($avatarUrl)
                <img
                    src="{{ $avatarUrl }}"
                    alt="{{ $student->name }}"
                    class="h-full w-full object-cover">
                @else
                {{ $initials }}
                @endif
            </div>

            <h1 class="mt-5 text-2xl font-black text-slate-900">
                {{ $student->name }}
            </h1>

            <p class="mt-2 break-all text-sm font-semibold text-slate-500">
                {{ $student->email }}
            </p>

            <div class="mt-4">
                @if ($student->is_active)
                <x-admin.status-badge variant="completed">
                    Active Account
                </x-admin.status-badge>
                @else
                <x-admin.status-badge variant="rejected">
                    Inactive Account
                </x-admin.status-badge>
                @endif
            </div>

            <p class="mt-4 text-xs font-semibold text-slate-400">
                Joined {{ $student->created_at?->format('d F Y') ?? '-' }}
            </p>

            <div class="mt-6 border-t border-slate-200 pt-5">
                @if ($student->is_active)
                <form
                    action="{{ route('admin.students.deactivate', $student) }}"
                    method="POST"
                    onsubmit="return confirm('Are you sure you want to deactivate this student account? The student will not be able to access the student area.');">
                    @csrf
                    @method('PUT')

                    <button
                        type="submit"
                        class="inline-flex h-11 w-full items-center justify-center rounded-xl bg-rose-600 px-5 text-sm font-extrabold text-white shadow-sm transition hover:bg-rose-700">
                        Deactivate Student
                    </button>
                </form>

                <p class="mt-3 text-xs font-semibold leading-5 text-slate-400">
                    Deactivated students cannot access the student area, but their learning data will remain safe.
                </p>
                @else
                <form
                    action="{{ route('admin.students.activate', $student) }}"
                    method="POST"
                    onsubmit="return confirm('Are you sure you want to activate this student account?');">
                    @csrf
                    @method('PUT')

                    <button
                        type="submit"
                        class="inline-flex h-11 w-full items-center justify-center rounded-xl bg-emerald-600 px-5 text-sm font-extrabold text-white shadow-sm transition hover:bg-emerald-700">
                        Activate Student
                    </button>
                </form>

                <p class="mt-3 text-xs font-semibold leading-5 text-slate-400">
                    Activated students can login and continue accessing their learning area.
                </p>
                @endif
            </div>
        </div>

        <div>
            <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                        Student Profile
                    </p>

                    <h2 class="mt-2 text-2xl font-black text-slate-900">
                        Personal Information
                    </h2>
                </div>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-white px-5 py-4">
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">WhatsApp</p>
                    <p class="mt-2 text-sm font-bold text-slate-900">{{ $profile?->whatsapp ?? '-' }}</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white px-5 py-4">
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">Gender</p>
                    <p class="mt-2 text-sm font-bold text-slate-900">
                        {{ $profile?->gender ? ucfirst($profile->gender) : '-' }}
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white px-5 py-4">
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">Birth Place</p>
                    <p class="mt-2 text-sm font-bold text-slate-900">{{ $profile?->birth_place ?? '-' }}</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white px-5 py-4">
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">Birth Date</p>
                    <p class="mt-2 text-sm font-bold text-slate-900">
                        {{ $profile?->birth_date?->format('d F Y') ?? '-' }}
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white px-5 py-4">
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">Profession</p>
                    <p class="mt-2 text-sm font-bold text-slate-900">{{ $profile?->profession ?? '-' }}</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white px-5 py-4">
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">Institution</p>
                    <p class="mt-2 text-sm font-bold text-slate-900">{{ $profile?->institution ?? '-' }}</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white px-5 py-4 md:col-span-2">
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">Social Media</p>
                    <p class="mt-2 break-all text-sm font-bold text-slate-900">{{ $profile?->social_media ?? '-' }}</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white px-5 py-4 md:col-span-2">
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">Address</p>
                    <p class="mt-2 text-sm font-bold leading-6 text-slate-900">{{ $profile?->address ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
</x-admin.table-card>