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