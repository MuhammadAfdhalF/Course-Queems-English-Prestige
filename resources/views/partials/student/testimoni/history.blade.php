<div>
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <h2 class="text-3xl font-bold text-slate-900">
            Riwayat Testimoni Saya
        </h2>

        <p class="text-sm font-semibold text-slate-400">
            {{ $testimonials->count() }} Total Submissions
        </p>
    </div>

    <div class="overflow-hidden rounded-[20px] border border-slate-200 bg-white shadow-sm">
        <div class="hidden grid-cols-[1.6fr_0.6fr_0.5fr_0.6fr] gap-4 border-b border-slate-100 bg-slate-50 px-6 py-4 text-xs font-bold uppercase tracking-[0.16em] text-slate-400 md:grid">
            <div>Kursus / Subjek</div>
            <div>Tanggal</div>
            <div>Rating</div>
            <div>Status</div>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse ($testimonials as $testimonial)
            <div class="grid gap-4 px-6 py-5 md:grid-cols-[1.6fr_0.6fr_0.5fr_0.6fr] md:items-center">
                <div>
                    <h3 class="text-base font-bold text-slate-900">
                        {{ $testimonial->courseLevel?->name ?? 'Queens English Prestige' }}
                    </h3>

                    <p class="mt-1 text-sm text-slate-400">
                        {{ $testimonial->type === 'course' ? 'Course Feedback' : 'Company Feedback' }}
                    </p>

                    <p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-500">
                        {{ $testimonial->testimonial }}
                    </p>
                </div>

                <div class="text-sm font-medium text-slate-500">
                    {{ $testimonial->created_at?->format('d M Y') }}
                </div>

                <div class="text-sm text-[var(--color-brand-gold)]">
                    @for ($i = 1; $i <= 5; $i++)
                        {{ $i <= (int) $testimonial->rating ? '★' : '☆' }}
                        @endfor
                        </div>

                        <div>
                            @if ($testimonial->is_active)
                            <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-600">
                                • Published
                            </span>
                            @else
                            <span class="inline-flex items-center rounded-full bg-yellow-50 px-3 py-1 text-xs font-bold text-[var(--color-brand-gold)]">
                                • Awaiting Publication
                            </span>
                            @endif
                        </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center">
                    <h3 class="text-xl font-extrabold text-slate-900">
                        No testimonial yet
                    </h3>

                    <p class="mx-auto mt-3 max-w-md text-sm leading-6 text-slate-500">
                        Your submitted testimonials will appear here.
                    </p>
                </div>
                @endforelse
            </div>
        </div>
    </div>