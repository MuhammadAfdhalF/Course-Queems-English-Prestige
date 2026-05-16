<div class="overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-100 px-7 py-5">
        <h2 class="flex items-center gap-3 text-2xl font-bold text-slate-900">
            <span class="text-[var(--color-brand-blue)]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <rect x="4" y="7" width="16" height="13" rx="2" stroke-width="1.8" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 7V5a2 2 0 012-2h2a2 2 0 012 2v2" />
                </svg>
            </span>
            Additional Information
        </h2>
    </div>

    <form action="{{ route('student.profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <input type="hidden" name="_section" value="additional">

        <div class="grid gap-6 p-7 md:grid-cols-2">
            <div>
                <label for="profession" class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                    Current Profession
                </label>

                <input
                    id="profession"
                    name="profession"
                    value="{{ old('profession', $profile->profession) }}"
                    placeholder="Example: Software Engineer"
                    class="h-13 w-full rounded-xl border border-slate-200 px-4 text-base font-semibold text-slate-700 placeholder:text-slate-400 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">

                @error('profession')
                <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="institution" class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                    Educational Institution
                </label>

                <input
                    id="institution"
                    name="institution"
                    value="{{ old('institution', $profile->institution) }}"
                    placeholder="Example: University of Indonesia"
                    class="h-13 w-full rounded-xl border border-slate-200 px-4 text-base font-semibold text-slate-700 placeholder:text-slate-400 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">

                @error('institution')
                <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="social_media" class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                    Social Media
                </label>

                <input
                    id="social_media"
                    name="social_media"
                    value="{{ old('social_media', $profile->social_media) }}"
                    placeholder="Example: @username or linkedin.com/in/username"
                    class="h-13 w-full rounded-xl border border-slate-200 px-4 text-base font-semibold text-slate-700 placeholder:text-slate-400 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">

                @error('social_media')
                <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex justify-end gap-4 border-t border-slate-100 bg-slate-50 px-7 py-5">
            <a
                href="{{ route('student.profile') }}"
                class="inline-flex h-12 items-center justify-center rounded-xl px-6 text-sm font-bold text-slate-500 hover:text-slate-700">
                Cancel
            </a>

            <button
                type="submit"
                class="h-12 rounded-xl bg-[var(--color-brand-blue)] px-7 text-sm font-bold text-white shadow-md hover:opacity-95">
                Save Additional Info
            </button>
        </div>
    </form>
</div>