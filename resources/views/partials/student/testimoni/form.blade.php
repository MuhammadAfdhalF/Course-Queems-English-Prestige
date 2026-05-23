<div class="grid gap-6 lg:grid-cols-2">
    <div>
        @if ($eligibleCertificates->count() > 0)
        <form
            x-data="{ rating: {{ (int) old('rating', 5) }} }"
            action="{{ route('student.testimoni.course.store') }}"
            method="POST"
            class="h-full rounded-[24px] border border-slate-200 bg-white p-7 shadow-sm lg:p-8">
            @csrf

            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-[var(--color-brand-blue)]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3l7 4v5c0 5-3.5 8-7 9-3.5-1-7-4-7-9V7l7-4z" />
                </svg>
            </div>

            <h2 class="mt-5 text-2xl font-black text-slate-900">
                Course Testimonial
            </h2>

            <p class="mt-2 text-sm font-semibold leading-6 text-slate-500">
                Submit your course testimonial to unlock your locked digital certificate.
            </p>

            <div class="mt-7">
                <label for="certificate_id" class="block text-sm font-bold text-slate-700">
                    Select Certificate
                </label>

                <select
                    id="certificate_id"
                    name="certificate_id"
                    class="mt-3 h-13 w-full rounded-xl border border-slate-200 bg-white px-4 text-base font-medium text-slate-700 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">
                    <option value="">Choose your completed course...</option>

                    @foreach ($eligibleCertificates as $certificate)
                    <option
                        value="{{ $certificate->id }}"
                        @selected((string) old('certificate_id')===(string) $certificate->id)>
                        {{ $certificate->courseLevel?->name ?? 'Unknown Course' }}
                        —
                        {{ $certificate->certificate_number }}
                    </option>
                    @endforeach
                </select>

                @error('certificate_id')
                <p class="mt-2 text-sm font-semibold text-rose-600">
                    {{ $message }}
                </p>
                @enderror
            </div>

            <div class="mt-7">
                <label class="block text-sm font-bold text-slate-700">
                    Rating
                </label>

                <input type="hidden" name="rating" x-model="rating">

                <div class="mt-3 flex items-center gap-1 text-[34px] leading-none">
                    <template x-for="star in 5" :key="star">
                        <button
                            type="button"
                            @click="rating = star"
                            class="transition hover:scale-110"
                            :class="star <= rating ? 'text-[var(--color-brand-gold)]' : 'text-slate-200'">
                            ★
                        </button>
                    </template>
                </div>

                @error('rating')
                <p class="mt-2 text-sm font-semibold text-rose-600">
                    {{ $message }}
                </p>
                @enderror
            </div>

            <div class="mt-7">
                <label for="testimonial" class="block text-sm font-bold text-slate-700">
                    Your Learning Experience
                </label>

                <textarea
                    id="testimonial"
                    name="testimonial"
                    rows="6"
                    placeholder="Tell us about your learning experience in this course..."
                    class="mt-3 w-full resize-y rounded-2xl border border-slate-200 bg-white px-4 py-4 text-base text-slate-900 placeholder:text-slate-400 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('testimonial') }}</textarea>

                @error('testimonial')
                <p class="mt-2 text-sm font-semibold text-rose-600">
                    {{ $message }}
                </p>
                @enderror
            </div>

            <div class="mt-8">
                <button
                    type="submit"
                    class="inline-flex h-14 w-full items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-8 text-base font-bold text-white shadow-md transition hover:opacity-95">
                    Submit & Unlock Certificate
                </button>
            </div>
        </form>
        @else
        <div class="h-full rounded-[24px] border border-dashed border-slate-300 bg-white px-6 py-10 text-center shadow-sm">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <rect x="6" y="10" width="12" height="10" rx="2" stroke-width="1.8" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 10V8a4 4 0 118 0v2" />
                </svg>
            </div>

            <h2 class="mt-5 text-2xl font-black text-slate-900">
                No Locked Certificate
            </h2>

            <p class="mx-auto mt-3 max-w-md text-sm font-semibold leading-6 text-slate-500">
                Locked certificates will appear here after you pass a final exam.
            </p>

            <a
                href="{{ route('student.my-courses') }}"
                class="mt-6 inline-flex h-12 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-6 text-sm font-bold text-white shadow-md transition hover:opacity-95">
                Back to My Courses
            </a>
        </div>
        @endif
    </div>

    <div>
        @if ($canSubmitCompanyTestimonial)
        <form
            x-data="{ companyRating: {{ (int) old('company_rating', 5) }} }"
            action="{{ route('student.testimoni.company.store') }}"
            method="POST"
            class="h-full rounded-[24px] border border-slate-200 bg-white p-7 shadow-sm lg:p-8">
            @csrf

            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-[var(--color-brand-gold)]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-4l-4 4v-4z" />
                </svg>
            </div>

            <h2 class="mt-5 text-2xl font-black text-slate-900">
                General Testimonial
            </h2>

            <p class="mt-2 text-sm font-semibold leading-6 text-slate-500">
                Share your overall experience with Queens English Prestige. This feedback does not unlock a certificate.
            </p>

            <div class="mt-7">
                <label class="block text-sm font-bold text-slate-700">
                    Rating
                </label>

                <input type="hidden" name="company_rating" x-model="companyRating">

                <div class="mt-3 flex items-center gap-1 text-[34px] leading-none">
                    <template x-for="star in 5" :key="star">
                        <button
                            type="button"
                            @click="companyRating = star"
                            class="transition hover:scale-110"
                            :class="star <= companyRating ? 'text-[var(--color-brand-gold)]' : 'text-slate-200'">
                            ★
                        </button>
                    </template>
                </div>

                @error('company_rating')
                <p class="mt-2 text-sm font-semibold text-rose-600">
                    {{ $message }}
                </p>
                @enderror
            </div>

            <div class="mt-7">
                <label for="company_testimonial" class="block text-sm font-bold text-slate-700">
                    Your General Experience
                </label>

                <textarea
                    id="company_testimonial"
                    name="company_testimonial"
                    rows="8"
                    placeholder="Tell us about your overall experience with Queens English Prestige..."
                    class="mt-3 w-full resize-y rounded-2xl border border-slate-200 bg-white px-4 py-4 text-base text-slate-900 placeholder:text-slate-400 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('company_testimonial') }}</textarea>

                @error('company_testimonial')
                <p class="mt-2 text-sm font-semibold text-rose-600">
                    {{ $message }}
                </p>
                @enderror
            </div>

            <div class="mt-8">
                <button
                    type="submit"
                    class="inline-flex h-14 w-full items-center justify-center rounded-xl bg-[var(--color-brand-gold)] px-8 text-base font-bold text-white shadow-md transition hover:opacity-95">
                    Submit General Testimonial
                </button>
            </div>
        </form>
        @else
        <div class="h-full rounded-[24px] border border-dashed border-slate-300 bg-white px-6 py-10 text-center shadow-sm">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6v6l4 2M12 22a10 10 0 110-20 10 10 0 010 20z" />
                </svg>
            </div>

            <h2 class="mt-5 text-2xl font-black text-slate-900">
                General Testimonial Locked
            </h2>

            <p class="mx-auto mt-3 max-w-md text-sm font-semibold leading-6 text-slate-500">
                You can submit a general testimonial after you have an active or completed course.
            </p>
        </div>
        @endif
    </div>
</div>