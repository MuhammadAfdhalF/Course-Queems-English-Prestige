@php
$profilePhoto = $profile?->photo
? asset('storage/' . $profile->photo)
: 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($user->email);

$latestCourseName = $latestEnrollment?->courseLevel?->name ?? 'No active course';
$latestProgress = $latestEnrollment
? (float) $latestEnrollment->progress_percentage
: 0;

$latestProgress = max(0, min(100, $latestProgress));
@endphp

<div class="rounded-[24px] border border-slate-200 bg-white p-7 text-center shadow-sm">
    <div class="mx-auto h-36 w-36 overflow-hidden rounded-full bg-[#F6D49D] p-2 shadow-sm">
        <img
            src="{{ $profilePhoto }}"
            alt="{{ $user->name }}"
            class="h-full w-full rounded-full object-cover">
    </div>

    <h2 class="mt-6 text-3xl font-bold text-slate-900">
        {{ $user->name }}
    </h2>

    <div class="mt-3 flex flex-wrap items-center justify-center gap-2 text-xs font-semibold">
        @if ($user->is_active)
        <span class="rounded-md bg-blue-50 px-3 py-1 uppercase tracking-[0.08em] text-[var(--color-brand-blue)]">
            Active Status
        </span>
        @else
        <span class="rounded-md bg-rose-50 px-3 py-1 uppercase tracking-[0.08em] text-rose-600">
            Inactive
        </span>
        @endif

        <span class="text-slate-400">•</span>

        <span class="text-slate-500">
            Joined {{ $user->created_at?->format('M Y') ?? '-' }}
        </span>
    </div>

    <form
        action="{{ route('student.profile.update') }}"
        method="POST"
        enctype="multipart/form-data"
        class="mt-7">
        @csrf
        @method('PUT')

        <input type="hidden" name="_section" value="photo">

        <label class="inline-flex h-11 w-full cursor-pointer items-center justify-center rounded-xl bg-yellow-50 text-sm font-bold text-[var(--color-brand-gold)] transition hover:bg-yellow-100">
            Upload Photo
            <input
                type="file"
                name="photo"
                accept="image/*"
                class="hidden"
                onchange="this.form.submit()">
        </label>

        @error('photo')
        <p class="mt-2 text-left text-sm font-semibold text-rose-600">
            {{ $message }}
        </p>
        @enderror
    </form>

    @if ($profile?->photo)
    <form
        action="{{ route('student.profile.photo.destroy') }}"
        method="POST"
        class="mt-3">
        @csrf
        @method('DELETE')

        <button
            type="submit"
            class="inline-flex h-11 w-full items-center justify-center rounded-xl bg-slate-100 text-sm font-bold text-slate-600 transition hover:bg-slate-200"
            onclick="return confirm('Remove your profile photo?')">
            Remove Photo
        </button>
    </form>
    @endif

    <div class="my-8 border-t border-slate-100"></div>

    <div class="text-left">
        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
            Registration Email
        </p>

        <div class="mt-3 flex items-center justify-between gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-4 text-sm font-semibold text-slate-700">
            <span class="min-w-0 break-all">{{ $user->email }}</span>

            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <rect x="6" y="10" width="12" height="10" rx="2" stroke-width="1.8" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 10V8a4 4 0 118 0v2" />
            </svg>
        </div>

        <p class="mt-2 text-xs text-slate-400">
            Email is used for login and cannot be changed here.
        </p>
    </div>
</div>

<div class="relative overflow-hidden rounded-[20px] bg-[var(--color-brand-blue)] p-6 text-white shadow-lg">
    <p class="text-xs font-bold uppercase tracking-[0.18em] text-blue-100">
        Course Progress
    </p>

    <div class="mt-4 flex items-end justify-between gap-4">
        <p class="font-serif text-3xl italic">
            {{ $latestCourseName }}
        </p>

        <p class="text-3xl font-bold">
            {{ number_format($latestProgress, 0) }}%
        </p>
    </div>

    <div class="mt-5 h-2 overflow-hidden rounded-full bg-white/20">
        <div
            class="h-full rounded-full bg-[var(--color-brand-gold)]"
            style="width: {{ $latestProgress }}%"></div>
    </div>

    <div class="pointer-events-none absolute -bottom-8 -right-5 h-24 w-24 rotate-12 rounded-3xl border-[10px] border-white/10"></div>
</div>