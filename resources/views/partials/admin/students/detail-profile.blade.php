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

<x-admin.table-card class="overflow-hidden">
    <div class="grid gap-0 lg:grid-cols-[340px_minmax(0,1fr)]">
        <div class="relative overflow-hidden border-b border-slate-200 bg-gradient-to-br from-slate-950 via-[var(--color-brand-blue)] to-slate-800 p-7 text-white lg:border-b-0 lg:border-r">
            <div class="pointer-events-none absolute -right-16 -top-16 h-40 w-40 rounded-full bg-white/10 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-20 left-10 h-40 w-40 rounded-full bg-yellow-400/20 blur-3xl"></div>

            <div class="relative text-center">
                <div class="mx-auto flex h-28 w-28 items-center justify-center overflow-hidden rounded-[32px] border border-white/15 bg-white/10 text-3xl font-black text-white shadow-xl">
                    @if ($avatarUrl)
                    <img
                        src="{{ $avatarUrl }}"
                        alt="{{ $student->name }}"
                        class="h-full w-full object-cover">
                    @else
                    {{ $initials }}
                    @endif
                </div>

                <h1 class="mt-5 text-2xl font-black leading-tight">
                    {{ $student->name }}
                </h1>

                <p class="mt-2 break-all text-sm font-semibold text-white/70">
                    {{ $student->email }}
                </p>

                <div class="mt-4">
                    @if ($student->is_active)
                    <span class="inline-flex items-center rounded-full bg-emerald-400/15 px-4 py-2 text-xs font-black uppercase tracking-[0.14em] text-emerald-200">
                        Active Account
                    </span>
                    @else
                    <span class="inline-flex items-center rounded-full bg-rose-400/15 px-4 py-2 text-xs font-black uppercase tracking-[0.14em] text-rose-200">
                        Inactive Account
                    </span>
                    @endif
                </div>

                <div class="mt-6 rounded-[24px] border border-white/10 bg-white/10 p-4 text-left">
                    <p class="text-xs font-black uppercase tracking-[0.16em] text-white/50">
                        Account Summary
                    </p>

                    <div class="mt-4 space-y-3">
                        <div class="flex items-center justify-between gap-4">
                            <span class="text-sm font-semibold text-white/60">Joined</span>
                            <span class="text-sm font-black text-white">{{ $student->created_at?->format('d M Y') ?? '-' }}</span>
                        </div>

                        <div class="flex items-center justify-between gap-4">
                            <span class="text-sm font-semibold text-white/60">WhatsApp</span>
                            <span class="break-all text-right text-sm font-black text-white">{{ $profile?->whatsapp ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 rounded-[24px] border border-white/10 bg-white/10 p-4 text-left">
                    <p class="text-xs font-black uppercase tracking-[0.16em] text-white/50">
                        Account Control
                    </p>

                    <p class="mt-2 text-xs font-semibold leading-5 text-white/60">
                        Manage whether this student can access the student area.
                    </p>

                    <div class="mt-4">
                        @if ($student->is_active)
                        <form
                            action="{{ route('admin.students.deactivate', $student) }}"
                            method="POST"
                            onsubmit="return confirm('Are you sure you want to deactivate this student account? The student will not be able to access the student area.');">
                            @csrf
                            @method('PUT')

                            <button
                                type="submit"
                                class="inline-flex h-11 w-full items-center justify-center rounded-2xl bg-rose-500 px-5 text-sm font-black text-white shadow-sm transition hover:bg-rose-600">
                                Deactivate Student
                            </button>
                        </form>
                        @else
                        <form
                            action="{{ route('admin.students.activate', $student) }}"
                            method="POST"
                            onsubmit="return confirm('Are you sure you want to activate this student account?');">
                            @csrf
                            @method('PUT')

                            <button
                                type="submit"
                                class="inline-flex h-11 w-full items-center justify-center rounded-2xl bg-emerald-500 px-5 text-sm font-black text-white shadow-sm transition hover:bg-emerald-600">
                                Activate Student
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="p-7">
            <div class="flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-400">
                        Student Profile
                    </p>

                    <h2 class="mt-2 text-2xl font-black text-slate-900">
                        Personal Information
                    </h2>

                    <p class="mt-2 max-w-2xl text-sm font-semibold leading-6 text-slate-500">
                        Complete student identity, contact information, and academic background.
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                    <a
                        href="{{ route('admin.course-access.index') }}"
                        class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-center transition hover:bg-white hover:shadow-sm">
                        <p class="text-xs font-black uppercase tracking-[0.14em] text-slate-400">Access</p>
                        <p class="mt-1 text-sm font-black text-[var(--color-brand-blue)]">Course Access</p>
                    </a>

                    <a
                        href="{{ route('admin.orders.index') }}"
                        class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-center transition hover:bg-white hover:shadow-sm">
                        <p class="text-xs font-black uppercase tracking-[0.14em] text-slate-400">Orders</p>
                        <p class="mt-1 text-sm font-black text-[var(--color-brand-blue)]">Order List</p>
                    </a>

                    <a
                        href="{{ route('admin.cms.testimonials.index') }}"
                        class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-center transition hover:bg-white hover:shadow-sm">
                        <p class="text-xs font-black uppercase tracking-[0.14em] text-slate-400">Feedback</p>
                        <p class="mt-1 text-sm font-black text-[var(--color-brand-blue)]">Testimonials</p>
                    </a>
                </div>
            </div>

            <div class="mt-7 grid gap-4 md:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                    <p class="text-[11px] font-black uppercase tracking-[0.14em] text-slate-400">WhatsApp</p>
                    <p class="mt-2 break-all text-sm font-black text-slate-900">{{ $profile?->whatsapp ?? '-' }}</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                    <p class="text-[11px] font-black uppercase tracking-[0.14em] text-slate-400">Gender</p>
                    <p class="mt-2 text-sm font-black text-slate-900">
                        {{ $profile?->gender ? ucfirst($profile->gender) : '-' }}
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                    <p class="text-[11px] font-black uppercase tracking-[0.14em] text-slate-400">Birth Place</p>
                    <p class="mt-2 text-sm font-black text-slate-900">{{ $profile?->birth_place ?? '-' }}</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                    <p class="text-[11px] font-black uppercase tracking-[0.14em] text-slate-400">Birth Date</p>
                    <p class="mt-2 text-sm font-black text-slate-900">
                        {{ $profile?->birth_date?->format('d F Y') ?? '-' }}
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                    <p class="text-[11px] font-black uppercase tracking-[0.14em] text-slate-400">Profession</p>
                    <p class="mt-2 text-sm font-black text-slate-900">{{ $profile?->profession ?? '-' }}</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                    <p class="text-[11px] font-black uppercase tracking-[0.14em] text-slate-400">Institution</p>
                    <p class="mt-2 text-sm font-black text-slate-900">{{ $profile?->institution ?? '-' }}</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 md:col-span-2">
                    <p class="text-[11px] font-black uppercase tracking-[0.14em] text-slate-400">Social Media</p>
                    <p class="mt-2 break-all text-sm font-black text-slate-900">{{ $profile?->social_media ?? '-' }}</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 md:col-span-2">
                    <p class="text-[11px] font-black uppercase tracking-[0.14em] text-slate-400">Address</p>
                    <p class="mt-2 text-sm font-black leading-6 text-slate-900">{{ $profile?->address ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
</x-admin.table-card>