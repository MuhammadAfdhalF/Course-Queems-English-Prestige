@extends('layouts.student')

@section('content')
@php
$courses = [
[
'title' => 'IELTS Preparation Intensive',
'level' => 'B2 Upper Intermediate',
'status' => 'active',
'statusLabel' => 'Active',
'meta' => 'Updated 2 days ago',
'progress' => 65,
'progressLabel' => 'Course Progress',
'badge' => 'GOLD LEVEL',
'image' => 'https://placehold.co/800x600/F3F4F6/1E293B?text=IELTS',
'primaryButton' => 'Continue Learning',
'secondaryButton' => 'Chat Admin',
],
[
'title' => 'Business English Mastery',
'level' => 'C1 Advanced',
'status' => 'completed',
'statusLabel' => 'Completed',
'meta' => 'Finished Oct 12, 2023',
'progress' => 100,
'progressLabel' => 'Course Completed',
'badge' => 'GOLD LEVEL',
'image' => 'https://placehold.co/800x600/6AA3B8/FFFFFF?text=Business',
'primaryButton' => 'Go to Certificate',
'secondaryButton' => 'View Modules',
],
[
'title' => 'Academic Writing Workshop',
'level' => 'Pending Enrollment',
'status' => 'pending',
'statusLabel' => 'Pending Enrollment',
'meta' => 'Not yet started',
'progress' => 0,
'progressLabel' => 'Waiting for administration approval. Expected within 24 hours.',
'badge' => '',
'image' => 'https://placehold.co/800x600/C8D3C1/1E293B?text=Workshop',
'primaryButton' => 'Module Locked',
'secondaryButton' => 'Chat Support',
],
];

$certificates = [
[
'title' => 'Business English Mastery',
'id' => 'CERT-12903-ABC',
'locked' => false,
],
[
'title' => 'IELTS Grammar Core',
'id' => 'CERT-10294-XYZ',
'locked' => false,
],
[
'title' => 'IELTS Preparation Intensive',
'id' => 'Locked Milestone',
'locked' => true,
'note' => 'Write Testimonial to Unlock',
],
[
'title' => 'Advanced Public Speaking',
'id' => 'Course in Progress',
'locked' => true,
],
];
@endphp

<section
    x-data="{
        activeTab: 'all',
        search: '',
        matches(status, title) {
            const tabMatch = this.activeTab === 'all' || this.activeTab === status;
            const searchMatch = title.toLowerCase().includes(this.search.toLowerCase());
            return tabMatch && searchMatch;
        }
    }"
    class="mx-auto max-w-7xl space-y-8">
    <div class="grid gap-8 xl:grid-cols-[minmax(0,1.9fr)_360px]">
        <div class="min-w-0">
            <div class="reveal flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <h1 class="text-4xl font-bold leading-tight text-slate-900">My Courses</h1>
                    <p class="mt-2 text-lg text-slate-500">You have 3 active courses this semester.</p>
                </div>

                <div class="w-full lg:max-w-[290px]">
                    <x-student.course-search-box />
                </div>
            </div>

            <div class="reveal reveal-delay-1 mt-6">
                <x-student.course-status-tabs />
            </div>

            <div class="mt-8 space-y-6">
                @foreach ($courses as $index => $course)
                @php
                $delayClass = match ($index) {
                1 => 'reveal-delay-1',
                2 => 'reveal-delay-2',
                default => '',
                };
                @endphp

                <div
                    x-show="matches('{{ $course['status'] }}', '{{ strtolower($course['title']) }}')"
                    x-transition.opacity.duration.250ms
                    class="reveal {{ $delayClass }}">
                    <x-student.my-course-card
                        :title="$course['title']"
                        :level="$course['level']"
                        :status="$course['status']"
                        :status-label="$course['statusLabel']"
                        :meta="$course['meta']"
                        :progress="$course['progress']"
                        :progress-label="$course['progressLabel']"
                        :badge="$course['badge']"
                        :image="$course['image']"
                        :primary-button="$course['primaryButton']"
                        :secondary-button="$course['secondaryButton']" />
                </div>
                @endforeach
            </div>
        </div>

        <div class="min-w-0 space-y-6">
            <div class="reveal reveal-delay-1">
                <x-student.certificate-list-panel :items="$certificates" />
            </div>

            <div class="reveal reveal-delay-2">
                <x-student.promo-panel />
            </div>
        </div>
    </div>
</section>
@endsection