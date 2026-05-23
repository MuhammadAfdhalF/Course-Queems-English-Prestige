@if ($pendingOrderCount > 0 || ($lockedCertificateCount ?? 0) > 0)
<section class="space-y-3">
    @if (($lockedCertificateCount ?? 0) > 0)
    @php
    $lockedCourseName = $latestLockedCertificate?->courseLevel?->name ?? 'your completed course';
    @endphp

    <div class="reveal rounded-[24px] border border-[#F1D7B5] bg-[#FFF7EA] px-5 py-4 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-start gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-[#AD6B10]/10 text-[#AD6B10]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15l-3.5 2 1-4-3-2.5 4-.3L12 6l1.5 4.2 4 .3-3 2.5 1 4z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 19h10" />
                    </svg>
                </div>

                <div>
                    <h3 class="text-base font-black text-[#080D4D]">
                        Certificate Waiting to Unlock
                    </h3>

                    <p class="mt-1 text-sm font-semibold leading-6 text-slate-600">
                        Submit your testimonial for {{ $lockedCourseName }} to unlock your digital certificate.
                    </p>
                </div>
            </div>

            <a
                href="{{ route('student.testimoni') }}"
                class="motion-button inline-flex h-10 items-center justify-center rounded-xl bg-[#080D4D] px-5 text-sm font-extrabold text-white shadow-sm transition hover:bg-[#AD6B10]">
                Submit Testimonial
            </a>
        </div>
    </div>
    @endif

    @if ($pendingOrderCount > 0)
    @php
    $latestPendingCourse = $latestPendingOrder?->courseLevel?->name ?? 'your selected course';
    @endphp

    <div class="reveal rounded-[24px] border border-[#DDE3FF] bg-[#F4F6FF] px-5 py-4 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-start gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-[#080D4D]/10 text-[#080D4D]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 9V7a5 5 0 00-10 0v2m-2 0h14l-1 10H6L5 9zm4 4h6" />
                    </svg>
                </div>

                <div>
                    <h3 class="text-base font-black text-[#080D4D]">
                        Order Waiting Confirmation
                    </h3>

                    <p class="mt-1 text-sm font-semibold leading-6 text-slate-600">
                        Your order for {{ $latestPendingCourse }} is waiting for admin confirmation.
                    </p>
                </div>
            </div>

            <a
                href="{{ route('student.my-courses') }}"
                class="motion-button inline-flex h-10 items-center justify-center rounded-xl bg-[#080D4D] px-5 text-sm font-extrabold text-white shadow-sm transition hover:bg-[#AD6B10]">
                View My Courses
            </a>
        </div>
    </div>
    @endif
</section>
@endif