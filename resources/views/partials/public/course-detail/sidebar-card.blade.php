@php
$learningModeLabel = match ($courseLevel->learning_mode) {
'offline' => 'Offline',
'hybrid' => 'Hybrid',
default => 'Online',
};

$accessLabel = $courseLevel->access_type === 'limited'
? ($courseLevel->access_duration_days . ' days access')
: 'Lifetime access';

$thumbnailUrl = $courseLevel->thumbnail_file
? asset('storage/' . $courseLevel->thumbnail_file)
: 'https://placehold.co/800x500/EEF3FF/2457E6?text=Queens+English';

$isGuest = auth()->guest();
$isStudent = auth()->check() && auth()->user()->isStudent();
$isAdmin = auth()->check() && auth()->user()->isAdmin();

$hasPendingOrder = $hasPendingOrder ?? false;
$hasActiveEnrollment = $hasActiveEnrollment ?? false;

$orderUrl = route('courses.order.create', $courseLevel);
$loginUrl = route('login');
$myCoursesUrl = route('student.my-courses');

if ($isGuest) {
$modalType = 'login';
$buttonLabel = 'Pesan Sekarang';
} elseif ($isAdmin) {
$modalType = 'admin';
$buttonLabel = 'Student Order Only';
} elseif ($hasActiveEnrollment) {
$modalType = 'active';
$buttonLabel = 'Go to My Courses';
} elseif ($hasPendingOrder) {
$modalType = 'pending';
$buttonLabel = 'Order Pending';
} else {
$modalType = 'confirm';
$buttonLabel = 'Pesan Sekarang';
}
@endphp

<div
    x-data="{
        orderModalOpen: false,
        modalType: @js($modalType),

        openOrderModal() {
            this.orderModalOpen = true;
            document.body.classList.add('overflow-hidden');
        },

        closeOrderModal() {
            this.orderModalOpen = false;
            document.body.classList.remove('overflow-hidden');
        }
    }"
    x-on:keydown.escape.window="closeOrderModal()">
    <aside class="reveal lg:sticky lg:top-24">
        <div class="motion-card rounded-[22px] border border-slate-200 bg-white p-4 shadow-sm">
            <div class="overflow-hidden rounded-[18px] bg-slate-100">
                @if ($courseLevel->thumbnail_file && $courseLevel->thumbnail_type === 'video')
                <video
                    src="{{ $thumbnailUrl }}"
                    controls
                    class="motion-image h-[165px] w-full bg-slate-900 object-cover">
                </video>
                @else
                <img
                    src="{{ $thumbnailUrl }}"
                    alt="{{ $courseLevel->name }}"
                    class="motion-image h-[165px] w-full object-cover">
                @endif
            </div>

            <div class="mt-4">
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">
                    Course Tuition
                </p>

                <div class="mt-2">
                    <p class="text-[34px] font-bold leading-[0.95] text-[#D4A017] lg:text-[38px]">
                        Rp {{ number_format((float) $courseLevel->price, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="mt-6 space-y-3.5">
                <div class="reveal flex items-center gap-3 text-slate-700">
                    <div class="flex h-4 w-4 items-center justify-center text-[#2457E6]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <rect x="5" y="3" width="14" height="18" rx="2" stroke-width="1.8" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7h8M8 11h8M8 15h5" />
                        </svg>
                    </div>

                    <span class="text-[14px] font-semibold">
                        {{ $accessLabel }}
                    </span>
                </div>

                <div class="reveal reveal-delay-1 flex items-center gap-3 text-slate-700">
                    <div class="flex h-4 w-4 items-center justify-center text-[#2457E6]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3l7 4v5c0 5-3.5 8-7 9-3.5-1-7-4-7-9V7l7-4z" />
                        </svg>
                    </div>

                    <span class="text-[14px] font-semibold">
                        Structured learning modules
                    </span>
                </div>

                <div class="reveal reveal-delay-2 flex items-center gap-3 text-slate-700">
                    <div class="flex h-4 w-4 items-center justify-center text-[#2457E6]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            @if ($courseLevel->learning_mode === 'offline')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.5 10.5h15M5.25 6.75h13.5A1.5 1.5 0 0120.25 8.25v7.5a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5v-7.5a1.5 1.5 0 011.5-1.5z" />
                            @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8.111 16.404a5 5 0 017.778 0M5.636 13.929a8.5 8.5 0 0112.728 0M3.161 11.454a12 12 0 0117.678 0M12 20h.01" />
                            @endif
                        </svg>
                    </div>

                    <span class="text-[14px] font-semibold">
                        {{ $learningModeLabel }} learning
                    </span>
                </div>
            </div>

            <div class="reveal reveal-delay-3 mt-6">
                <button
                    type="button"
                    @click="openOrderModal()"
                    class="motion-button inline-flex w-full items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-md transition hover:opacity-95">
                    {{ $buttonLabel }}
                </button>
            </div>

            @if (session('success'))
            <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold leading-6 text-emerald-700">
                {{ session('success') }}
            </div>
            @endif

            <p class="reveal reveal-delay-4 mx-auto mt-4 max-w-[250px] text-center text-xs leading-6 text-slate-500">
                *Our admin will contact you via WhatsApp for further process and schedule selection.
            </p>

            <div class="reveal reveal-delay-4 mt-5 border-t border-slate-200 pt-4">
                <div class="flex items-center justify-center gap-3 text-slate-400">
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 transition-colors duration-200 hover:bg-slate-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.325 4.317a1 1 0 011.35-.936l.94.47a1 1 0 00.894 0l.94-.47a1 1 0 011.35.936l.094 1.04a1 1 0 00.592.823l.95.475a1 1 0 01.376 1.527l-.665.83a1 1 0 000 1.25l.665.83a1 1 0 01-.376 1.527l-.95.475a1 1 0 00-.592.823l-.094 1.04a1 1 0 01-1.35.936l-.94-.47a1 1 0 00-.894 0l-.94.47a1 1 0 01-1.35-.936l-.094-1.04a1 1 0 00-.592-.823l-.95-.475a1 1 0 01-.376-1.527l.665-.83a1 1 0 000-1.25l-.665-.83a1 1 0 01.376-1.527l.95-.475a1 1 0 00.592-.823l.094-1.04z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </span>

                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 transition-colors duration-200 hover:bg-slate-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3l7 4v5c0 5-3.5 8-7 9-3.5-1-7-4-7-9V7l7-4z" />
                        </svg>
                    </span>

                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 transition-colors duration-200 hover:bg-slate-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <circle cx="12" cy="12" r="9" stroke-width="1.8" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4" />
                        </svg>
                    </span>
                </div>
            </div>
        </div>
    </aside>

    {{-- FULL PAGE ORDER MODAL --}}
    <div
        x-cloak
        x-show="orderModalOpen"
        x-transition.opacity
        class="fixed inset-0 z-[9999] overflow-y-auto bg-slate-950/75 px-4 py-8 backdrop-blur-sm"
        @click.self="closeOrderModal()">
        <div class="flex min-h-full items-center justify-center">
            <div
                x-show="orderModalOpen"
                x-transition.scale.origin.center
                class="relative w-full max-w-md rounded-[28px] border border-white/20 bg-white p-6 shadow-2xl">
                <button
                    type="button"
                    @click="closeOrderModal()"
                    class="absolute -right-2 -top-2 flex h-10 w-10 items-center justify-center rounded-full bg-white text-slate-500 shadow-lg ring-1 ring-slate-200 transition hover:text-slate-900"
                    aria-label="Close modal">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                {{-- GUEST LOGIN REQUIRED --}}
                <template x-if="modalType === 'login'">
                    <div>
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-blue-50 text-[var(--color-brand-blue)]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V9a4 4 0 10-8 0v2h8z" />
                            </svg>
                        </div>

                        <h3 class="mt-5 text-center text-2xl font-extrabold text-slate-900">
                            Login Required
                        </h3>

                        <p class="mt-3 text-center text-sm leading-6 text-slate-500">
                            Please login before placing an order. Your account is needed to connect this course to your student dashboard.
                        </p>

                        <div class="mt-6 grid gap-3 sm:grid-cols-2">
                            <button
                                type="button"
                                @click="closeOrderModal()"
                                class="inline-flex h-12 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-sm font-bold text-slate-600 transition hover:bg-slate-50">
                                Cancel
                            </button>

                            <a
                                href="{{ $loginUrl }}"
                                class="inline-flex h-12 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-4 text-sm font-bold text-white shadow-md transition hover:opacity-95">
                                Login to Continue
                            </a>
                        </div>
                    </div>
                </template>

                {{-- ADMIN WARNING --}}
                <template x-if="modalType === 'admin'">
                    <div>
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-amber-50 text-[var(--color-brand-gold)]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                            </svg>
                        </div>

                        <h3 class="mt-5 text-center text-2xl font-extrabold text-slate-900">
                            Student Account Required
                        </h3>

                        <p class="mt-3 text-center text-sm leading-6 text-slate-500">
                            Admin accounts cannot place course orders from the public website. Please use a student account to continue.
                        </p>

                        <div class="mt-6">
                            <button
                                type="button"
                                @click="closeOrderModal()"
                                class="inline-flex h-12 w-full items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-4 text-sm font-bold text-white shadow-md transition hover:opacity-95">
                                Got It
                            </button>
                        </div>
                    </div>
                </template>

                {{-- ACTIVE ENROLLMENT --}}
                <template x-if="modalType === 'active'">
                    <div>
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3l7 4v5c0 5-3.5 8-7 9-3.5-1-7-4-7-9V7l7-4z" />
                            </svg>
                        </div>

                        <h3 class="mt-5 text-center text-2xl font-extrabold text-slate-900">
                            Course Access Active
                        </h3>

                        <p class="mt-3 text-center text-sm leading-6 text-slate-500">
                            You already have access to this course. Please continue learning from your student dashboard.
                        </p>

                        <div class="mt-6 grid gap-3 sm:grid-cols-2">
                            <button
                                type="button"
                                @click="closeOrderModal()"
                                class="inline-flex h-12 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-sm font-bold text-slate-600 transition hover:bg-slate-50">
                                Cancel
                            </button>

                            <a
                                href="{{ $myCoursesUrl }}"
                                class="inline-flex h-12 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-4 text-sm font-bold text-white shadow-md transition hover:opacity-95">
                                Go to My Courses
                            </a>
                        </div>
                    </div>
                </template>

                {{-- PENDING ORDER --}}
                <template x-if="modalType === 'pending'">
                    <div>
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-yellow-50 text-[var(--color-brand-gold)]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3" />
                                <circle cx="12" cy="12" r="9" stroke-width="1.8" />
                            </svg>
                        </div>

                        <h3 class="mt-5 text-center text-2xl font-extrabold text-slate-900">
                            Order Already Submitted
                        </h3>

                        <p class="mt-3 text-center text-sm leading-6 text-slate-500">
                            You already have a pending order for this course. Please wait for our admin to contact you via WhatsApp.
                        </p>

                        <div class="mt-6">
                            <button
                                type="button"
                                @click="closeOrderModal()"
                                class="inline-flex h-12 w-full items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-4 text-sm font-bold text-white shadow-md transition hover:opacity-95">
                                Got It
                            </button>
                        </div>
                    </div>
                </template>

                {{-- CONFIRM ORDER --}}
                <template x-if="modalType === 'confirm'">
                    <div>
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-blue-50 text-[var(--color-brand-blue)]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 3h13M9 20a1 1 0 100-2 1 1 0 000 2zm9 0a1 1 0 100-2 1 1 0 000 2z" />
                            </svg>
                        </div>

                        <h3 class="mt-5 text-center text-2xl font-extrabold text-slate-900">
                            Confirm Course Order
                        </h3>

                        <p class="mt-3 text-center text-sm leading-6 text-slate-500">
                            You are about to place an order for this course. Our admin will contact you via WhatsApp for payment and schedule confirmation.
                        </p>

                        <div class="mt-6 grid gap-3 sm:grid-cols-2">
                            <button
                                type="button"
                                @click="closeOrderModal()"
                                class="inline-flex h-12 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-sm font-bold text-slate-600 transition hover:bg-slate-50">
                                Cancel
                            </button>

                            <a
                                href="{{ $orderUrl }}"
                                class="inline-flex h-12 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-4 text-sm font-bold text-white shadow-md transition hover:opacity-95">
                                Continue Order
                            </a>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>