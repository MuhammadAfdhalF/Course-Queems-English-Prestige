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