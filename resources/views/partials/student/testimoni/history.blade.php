<div>
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-400">
                Submission History
            </p>

            <h2 class="mt-2 text-3xl font-black text-slate-900">
                Riwayat Testimoni Saya
            </h2>
        </div>

        <p class="text-sm font-bold text-slate-400">
            {{ $testimonials->count() }} Total Submissions
        </p>
    </div>

    <div class="overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-sm">
        <div class="hidden grid-cols-[1.5fr_0.55fr_0.45fr_0.55fr] gap-4 border-b border-slate-100 bg-slate-50 px-6 py-4 text-xs font-black uppercase tracking-[0.16em] text-slate-400 md:grid">
            <div>Subject</div>
            <div>Date</div>
            <div>Rating</div>
            <div>Status</div>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse ($testimonials as $testimonial)
            <div class="grid gap-4 px-6 py-5 md:grid-cols-[1.5fr_0.55fr_0.45fr_0.55fr] md:items-center">
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h3 class="text-base font-black text-slate-900">
                            {{ $testimonial->type === 'course'
                                    ? ($testimonial->courseLevel?->name ?? 'Unknown Course')
                                    : 'Queens English Prestige' }}
                        </h3>

                        @if ($testimonial->type === 'course')
                        <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-black text-blue-700">
                            Course Feedback
                        </span>
                        @else
                        <span class="inline-flex rounded-full bg-amber-50 px-3 py-1 text-xs font-black text-[var(--color-brand-gold)]">
                            Company Feedback
                        </span>
                        @endif
                    </div>

                    @if ($testimonial->type === 'course')
                    <p class="mt-1 text-sm font-semibold text-slate-400">
                        {{ $testimonial->courseLevel?->courseProgram?->name ?? 'Course Program' }}
                    </p>
                    @else
                    <p class="mt-1 text-sm font-semibold text-slate-400">
                        General feedback
                    </p>
                    @endif

                    <p class="mt-2 line-clamp-2 text-sm font-medium leading-6 text-slate-500">
                        {{ $testimonial->testimonial }}
                    </p>
                </div>

                <div class="text-sm font-semibold text-slate-500">
                    {{ $testimonial->created_at?->format('d M Y') }}
                </div>

                <div class="text-sm text-[var(--color-brand-gold)]">
                    @for ($i = 1; $i <= 5; $i++)
                        {{ $i <= (int) $testimonial->rating ? '★' : '☆' }}
                        @endfor
                        </div>

                        <div>
                            @if ($testimonial->is_active)
                            <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-black text-emerald-600">
                                • Published
                            </span>
                            @else
                            <span class="inline-flex items-center rounded-full bg-yellow-50 px-3 py-1 text-xs font-black text-[var(--color-brand-gold)]">
                                • Awaiting Publication
                            </span>
                            @endif
                        </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center">
                    <h3 class="text-xl font-black text-slate-900">
                        No testimonial yet
                    </h3>

                    <p class="mx-auto mt-3 max-w-md text-sm font-semibold leading-6 text-slate-500">
                        Your submitted course and general testimonials will appear here.
                    </p>
                </div>
                @endforelse
            </div>
        </div>
    </div>