@if ($pendingOrderCount > 0)
@php
$latestPendingCourse = $latestPendingOrder?->courseLevel?->name ?? 'your selected course';

$bannerTitle = $pendingOrderCount === 1
? 'You have 1 pending course order'
: 'You have ' . $pendingOrderCount . ' pending course orders';

$bannerDescription = 'Your order for ' . $latestPendingCourse . ' is waiting for admin confirmation. Our admin will contact you via WhatsApp soon.';
@endphp

<div class="reveal">
    <x-student.pending-banner
        :title="$bannerTitle"
        :description="$bannerDescription"
        button-text="View My Courses"
        :href="route('student.my-courses')" />
</div>
@endif

@if (($lockedCertificateCount ?? 0) > 0)
@php
$lockedCourseName = $latestLockedCertificate?->courseLevel?->name ?? 'your completed course';

$lockedTitle = $lockedCertificateCount === 1
? 'Your certificate is almost ready'
: 'You have ' . $lockedCertificateCount . ' certificates waiting to be unlocked';

$lockedDescription = 'Submit your testimonial for ' . $lockedCourseName . ' to unlock your digital certificate.';
@endphp

<div class="reveal">
    <div class="rounded-[24px] border border-yellow-200 bg-yellow-50 px-6 py-5 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-yellow-100 text-[var(--color-brand-gold)]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15l-3.5 2 1-4-3-2.5 4-.3L12 6l1.5 4.2 4 .3-3 2.5 1 4z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 19h10" />
                    </svg>
                </div>

                <div>
                    <h3 class="text-lg font-extrabold text-slate-900">
                        {{ $lockedTitle }}
                    </h3>

                    <p class="mt-1 text-sm font-medium leading-6 text-slate-600">
                        {{ $lockedDescription }}
                    </p>
                </div>
            </div>

            <a
                href="{{ route('student.testimoni') }}"
                class="motion-button inline-flex h-11 items-center justify-center rounded-xl bg-[var(--color-brand-gold)] px-5 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
                Submit Testimonial
            </a>
        </div>
    </div>
</div>
@endif

@if (($issuedCertificateCount ?? 0) > 0 && $latestIssuedCertificate)
<div class="reveal">
    <div class="rounded-[24px] border border-emerald-200 bg-emerald-50 px-6 py-5 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <rect x="5" y="3" width="14" height="18" rx="2" stroke-width="1.8" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 8h6M9 12h6M9 16h3" />
                    </svg>
                </div>

                <div>
                    <h3 class="text-lg font-extrabold text-slate-900">
                        Your certificate is available
                    </h3>

                    <p class="mt-1 text-sm font-medium leading-6 text-slate-600">
                        You can view or download your latest certificate for {{ $latestIssuedCertificate->courseLevel?->name ?? 'your course' }} anytime.
                    </p>
                </div>
            </div>

            <a
                href="{{ route('student.certificates.show', $latestIssuedCertificate) }}"
                class="motion-button inline-flex h-11 items-center justify-center rounded-xl bg-emerald-600 px-5 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-700">
                View Certificate
            </a>
        </div>
    </div>
</div>
@endif

@if (($rejectedOrderCount ?? 0) > 0 && $latestRejectedOrder)
<div class="reveal">
    <div class="rounded-[24px] border border-rose-200 bg-rose-50 px-6 py-5 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-rose-100 text-rose-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>

                <div>
                    <h3 class="text-lg font-extrabold text-slate-900">
                        Some orders were not approved
                    </h3>

                    <p class="mt-1 text-sm font-medium leading-6 text-slate-600">
                        Your order for {{ $latestRejectedOrder->courseLevel?->name ?? 'a selected course' }} was not approved. You can browse courses and place a new order anytime.
                    </p>
                </div>
            </div>

            <a
                href="{{ route('courses') }}"
                class="motion-button inline-flex h-11 items-center justify-center rounded-xl bg-rose-600 px-5 text-sm font-bold text-white shadow-sm transition hover:bg-rose-700">
                Browse Courses
            </a>
        </div>
    </div>
</div>
@endif