<div class="overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-sm">
    <div class="flex items-center justify-between border-b border-slate-100 px-7 py-5">
        <h2 class="flex items-center gap-3 text-2xl font-bold text-slate-900">
            <span class="text-[var(--color-brand-blue)]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19a4 4 0 00-8 0m8 0H5m10 0h4M12 12a4 4 0 100-8 4 4 0 000 8z" />
                </svg>
            </span>
            Personal Information
        </h2>

        <span class="rounded-md border border-slate-200 px-3 py-1 text-xs font-bold uppercase tracking-[0.12em] text-slate-400">
            Step 01
        </span>
    </div>

    <form action="{{ route('student.profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <input type="hidden" name="_section" value="personal">

        <div class="space-y-6 p-7">
            <div>
                <label for="name" class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                    Full Name
                </label>

                <input
                    id="name"
                    name="name"
                    value="{{ old('name', $user->name) }}"
                    class="h-13 w-full rounded-xl border border-slate-200 px-4 text-base font-semibold text-slate-700 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">

                @error('name')
                <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                    Email
                </label>

                <input
                    value="{{ $user->email }}"
                    readonly
                    class="h-13 w-full cursor-not-allowed rounded-xl border border-slate-200 bg-slate-50 px-4 text-base font-semibold text-slate-500">
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="whatsapp" class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                        Whatsapp Number
                    </label>

                    <input
                        id="whatsapp"
                        name="whatsapp"
                        value="{{ old('whatsapp', $profile->whatsapp) }}"
                        placeholder="Example: +6281234567890"
                        class="h-13 w-full rounded-xl border border-slate-200 px-4 text-base font-semibold text-slate-700 placeholder:text-slate-400 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">

                    @error('whatsapp')
                    <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="birth_place" class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                        Place of Birth
                    </label>

                    <input
                        id="birth_place"
                        name="birth_place"
                        value="{{ old('birth_place', $profile->birth_place) }}"
                        placeholder="Example: Jakarta"
                        class="h-13 w-full rounded-xl border border-slate-200 px-4 text-base font-semibold text-slate-700 placeholder:text-slate-400 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">

                    @error('birth_place')
                    <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="birth_date" class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                        Date of Birth
                    </label>

                    <input
                        id="birth_date"
                        name="birth_date"
                        type="date"
                        value="{{ old('birth_date', $profile->birth_date?->format('Y-m-d')) }}"
                        class="h-13 w-full rounded-xl border border-slate-200 px-4 text-base font-semibold text-slate-700 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">

                    @error('birth_date')
                    <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="gender" class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                        Gender
                    </label>

                    <select
                        id="gender"
                        name="gender"
                        class="h-13 w-full rounded-xl border border-slate-200 px-4 text-base font-semibold text-slate-700 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">
                        <option value="">Select gender</option>
                        <option value="male" @selected(old('gender', $profile->gender) === 'male')>Male</option>
                        <option value="female" @selected(old('gender', $profile->gender) === 'female')>Female</option>
                    </select>

                    @error('gender')
                    <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="address" class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                    Mailing Address
                </label>

                <textarea
                    id="address"
                    name="address"
                    rows="4"
                    placeholder="Write your full address..."
                    class="w-full resize-none rounded-xl border border-slate-200 px-4 py-4 text-base font-semibold text-slate-700 placeholder:text-slate-400 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('address', $profile->address) }}</textarea>

                @error('address')
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
                Save Changes
            </button>
        </div>
    </form>
</div>