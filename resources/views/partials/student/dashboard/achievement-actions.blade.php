<section class="grid gap-5 lg:grid-cols-[0.9fr_1.1fr]">
    <div class="reveal rounded-[28px] border border-[#F1D7B5] bg-gradient-to-br from-white to-[#FFF7EA] p-6 shadow-sm">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.2em] text-[#AD6B10]">
                    Latest Achievement
                </p>

                <h2 class="mt-2 text-2xl font-black text-[#080D4D]">
                    Certificate
                </h2>
            </div>

            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#AD6B10]/10 text-[#AD6B10]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <rect x="5" y="3" width="14" height="18" rx="2" stroke-width="1.8" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 8h6M9 12h6M9 16h3" />
                </svg>
            </div>
        </div>

        @if ($latestIssuedCertificate)
        <div class="mt-5 rounded-[24px] border border-[#F1D7B5] bg-white/80 p-5">
            <p class="text-xs font-black uppercase tracking-[0.16em] text-[#AD6B10]">
                Certificate Available
            </p>

            <h3 class="mt-2 text-xl font-black text-[#080D4D]">
                {{ $latestIssuedCertificate->courseLevel?->name ?? 'Your Course' }}
            </h3>

            <p class="mt-2 text-sm font-semibold leading-6 text-slate-600">
                Your certificate is ready to view and download anytime.
            </p>

            <a
                href="{{ route('student.certificates.show', $latestIssuedCertificate) }}"
                class="motion-button mt-4 inline-flex h-10 items-center justify-center rounded-xl bg-[#080D4D] px-4 text-sm font-extrabold text-white transition hover:bg-[#AD6B10]">
                View Certificate
            </a>
        </div>
        @else
        <div class="mt-5 rounded-[24px] border border-[#DDE3FF] bg-white/80 p-5">
            <p class="text-xs font-black uppercase tracking-[0.16em] text-slate-400">
                No certificate yet
            </p>

            <h3 class="mt-2 text-xl font-black text-[#080D4D]">
                Keep Learning
            </h3>

            <p class="mt-2 text-sm font-semibold leading-6 text-slate-600">
                Complete your course and final exam to earn your digital certificate.
            </p>
        </div>
        @endif

        @if (($rejectedOrderCount ?? 0) > 0 && $latestRejectedOrder && $activeCourseCount === 0 && $pendingOrderCount === 0)
        <div class="mt-4 rounded-2xl border border-rose-100 bg-rose-50 px-5 py-4">
            <p class="text-sm font-black text-rose-700">
                Order Update
            </p>

            <p class="mt-1 text-sm font-semibold leading-6 text-rose-600">
                One previous order was not approved. You can browse other courses anytime.
            </p>

            <a
                href="{{ route('courses') }}"
                class="mt-3 inline-flex text-sm font-black text-rose-700 hover:underline">
                Browse Courses
            </a>
        </div>
        @endif
    </div>

    <div class="reveal rounded-[28px] border border-[#DDE3FF] bg-gradient-to-br from-white to-[#F4F6FF] p-6 shadow-sm">
        <p class="text-xs font-black uppercase tracking-[0.2em] text-[#AD6B10]">
            Quick Actions
        </p>

        <h2 class="mt-2 text-2xl font-black text-[#080D4D]">
            What do you want to do next?
        </h2>

        <div class="mt-5 grid gap-3 sm:grid-cols-2">
            <a href="{{ route('student.my-courses') }}" class="group rounded-[20px] border border-[#DDE3FF] bg-white/80 p-4 transition hover:-translate-y-1 hover:border-[#F1D7B5] hover:bg-[#FFF7EA] hover:shadow-md">
                <div class="flex items-start gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#080D4D]/10 text-[#080D4D]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <rect x="4" y="5" width="16" height="14" rx="2" stroke-width="1.8" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 9h8M8 13h5" />
                        </svg>
                    </div>

                    <div>
                        <p class="text-sm font-black text-[#080D4D]">My Courses</p>
                        <p class="mt-1 text-xs font-semibold leading-5 text-slate-500">Continue your learning path.</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('courses') }}" class="group rounded-[20px] border border-[#DDE3FF] bg-white/80 p-4 transition hover:-translate-y-1 hover:border-[#F1D7B5] hover:bg-[#FFF7EA] hover:shadow-md">
                <div class="flex items-start gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#AD6B10]/10 text-[#AD6B10]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v11.494m-5.747-8.62h11.494" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.5 5.25h15A2.25 2.25 0 0121.75 7.5v9A2.25 2.25 0 0119.5 18.75h-15A2.25 2.25 0 012.25 16.5v-9A2.25 2.25 0 014.5 5.25z" />
                        </svg>
                    </div>

                    <div>
                        <p class="text-sm font-black text-[#080D4D]">Browse Courses</p>
                        <p class="mt-1 text-xs font-semibold leading-5 text-slate-500">Explore more programs.</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('student.testimoni') }}" class="group rounded-[20px] border border-[#DDE3FF] bg-white/80 p-4 transition hover:-translate-y-1 hover:border-[#F1D7B5] hover:bg-[#FFF7EA] hover:shadow-md">
                <div class="flex items-start gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#080D4D]/10 text-[#080D4D]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-4l-4 4v-4z" />
                        </svg>
                    </div>

                    <div>
                        <p class="text-sm font-black text-[#080D4D]">Testimonial</p>
                        <p class="mt-1 text-xs font-semibold leading-5 text-slate-500">Share feedback.</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('student.profile') }}" class="group rounded-[20px] border border-[#DDE3FF] bg-white/80 p-4 transition hover:-translate-y-1 hover:border-[#F1D7B5] hover:bg-[#FFF7EA] hover:shadow-md">
                <div class="flex items-start gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20a4 4 0 00-10 0M12 12a4 4 0 100-8 4 4 0 000 8z" />
                        </svg>
                    </div>

                    <div>
                        <p class="text-sm font-black text-[#080D4D]">Profile</p>
                        <p class="mt-1 text-xs font-semibold leading-5 text-slate-500">Update your data.</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</section>