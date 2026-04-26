<div
    x-data="{
        serviceRating: 5,
        courseRating: 5,
        selectedAspect: 'Layanan Admin',
        aspects: ['Layanan Admin', 'Mentor Profesional', 'Fasilitas Belajar', 'Materi Kurikulum'],
        submitTestimoni() {
            alert('Testimoni berhasil disimpan. Sertifikat akan terbuka setelah proses validasi.');
        }
    }"
    class="rounded-[24px] border border-slate-200 bg-white p-7 shadow-sm lg:p-9"
>
    {{-- Service Review --}}
    <div class="flex items-start gap-4">
        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-[var(--color-brand-blue)]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <rect x="4" y="5" width="16" height="14" rx="2" stroke-width="1.8" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 9h8M8 13h5" />
            </svg>
        </div>

        <div class="min-w-0 flex-1">
            <h2 class="text-2xl font-bold text-slate-900">
                Review Queens English Prestige
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Berikan rating untuk layanan keseluruhan kami (Admin, Mentor, & Fasilitas)
            </p>

            <div class="mt-7">
                <label class="block text-sm font-bold text-slate-700">
                    Rating Keseluruhan
                </label>

                <div class="mt-3 flex items-center gap-1 text-[32px] leading-none text-[var(--color-brand-gold)]">
                    <template x-for="star in 5" :key="star">
                        <button
                            type="button"
                            @click="serviceRating = star"
                            class="transition hover:scale-110"
                            :class="star <= serviceRating ? 'text-[var(--color-brand-gold)]' : 'text-slate-200'">
                            ★
                        </button>
                    </template>
                </div>
            </div>

            <div class="mt-7">
                <label class="block text-sm font-bold text-slate-700">
                    Aspek yang paling memuaskan?
                </label>

                <div class="mt-3 flex flex-wrap gap-3">
                    <template x-for="aspect in aspects" :key="aspect">
                        <button
                            type="button"
                            @click="selectedAspect = aspect"
                            class="rounded-full border px-5 py-2.5 text-sm font-semibold transition"
                            :class="selectedAspect === aspect
                                ? 'border-[var(--color-brand-blue)] bg-[var(--color-brand-blue)] text-white shadow-sm'
                                : 'border-slate-200 bg-white text-slate-600 hover:border-slate-300'">
                            <span x-text="aspect"></span>
                        </button>
                    </template>
                </div>
            </div>

            <div class="mt-7">
                <label class="block text-sm font-bold text-slate-700">
                    Ceritakan pengalaman Anda
                </label>

                <textarea
                    rows="5"
                    placeholder="Apa yang Anda sukai dari layanan kami?"
                    class="mt-3 w-full resize-none rounded-2xl border border-slate-200 bg-white px-4 py-4 text-base text-slate-900 placeholder:text-slate-400 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100"></textarea>
            </div>
        </div>
    </div>

    <div class="my-9 border-t border-slate-200"></div>

    {{-- Course Review --}}
    <div class="flex items-start gap-4">
        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-[var(--color-brand-blue)]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6.5A2.5 2.5 0 016.5 4H10v16H6.5A2.5 2.5 0 014 17.5v-11zM20 6.5A2.5 2.5 0 0017.5 4H14v16h3.5a2.5 2.5 0 002.5-2.5v-11z" />
            </svg>
        </div>

        <div class="min-w-0 flex-1">
            <h2 class="text-2xl font-bold text-slate-900">
                Review Kursus Spesifik
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Berikan detail evaluasi untuk kursus yang telah Anda selesaikan.
            </p>

            <div class="mt-7">
                <label class="block text-sm font-bold text-slate-700">
                    Pilih Kursus
                </label>

                <select class="mt-3 h-13 w-full rounded-xl border border-slate-200 bg-white px-4 text-base font-medium text-slate-700 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">
                    <option>Pilih salah satu...</option>
                    <option>IELTS Mastery: Band 7.5+</option>
                    <option>Business English Communication</option>
                    <option>TOEFL Preparation Course</option>
                </select>
            </div>

            <div class="mt-7">
                <label class="block text-sm font-bold text-slate-700">
                    Rating Kursus
                </label>

                <div class="mt-3 flex items-center gap-1 text-[32px] leading-none text-[var(--color-brand-gold)]">
                    <template x-for="star in 5" :key="star">
                        <button
                            type="button"
                            @click="courseRating = star"
                            class="transition hover:scale-110"
                            :class="star <= courseRating ? 'text-[var(--color-brand-gold)]' : 'text-slate-200'">
                            ★
                        </button>
                    </template>
                </div>
            </div>

            <div class="mt-7">
                <label class="block text-sm font-bold text-slate-700">
                    Apa pencapaian terbesar Anda di kursus ini?
                </label>

                <textarea
                    rows="5"
                    placeholder="Tuliskan pengalaman belajar Anda di sini..."
                    class="mt-3 w-full resize-none rounded-2xl border border-slate-200 bg-white px-4 py-4 text-base text-slate-900 placeholder:text-slate-400 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100"></textarea>
            </div>

            <div class="mt-8 flex justify-end">
                <button
                    type="button"
                    @click="submitTestimoni()"
                    class="inline-flex h-14 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-8 text-base font-bold text-white shadow-md transition hover:opacity-95">
                    Submit Testimoni & Buka Sertifikat
                </button>
            </div>
        </div>
    </div>
</div>