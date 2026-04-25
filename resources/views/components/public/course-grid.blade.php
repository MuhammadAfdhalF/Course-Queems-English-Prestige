@props([
'courses' => [],
'columns' => 'xl:grid-cols-4',
])

<div {{ $attributes->merge(['class' => 'grid gap-6 md:grid-cols-2 ' . $columns]) }}>
    @foreach ($courses as $index => $course)
    @php
    $delayClass = match ($index % 4) {
    1 => 'reveal-delay-1',
    2 => 'reveal-delay-2',
    3 => 'reveal-delay-3',
    default => '',
    };
    @endphp

    <div class="reveal {{ $delayClass }}">
        <x-ui.course-card
            :title="$course['title'] ?? 'Course Title'"
            :level="$course['level'] ?? 'Intermediate'"
            :mode="$course['mode'] ?? 'Online'"
            :price="$course['price'] ?? 'Rp. 100.000'"
            :description="$course['description'] ?? 'Short description about this course.'"
            :image="$course['image'] ?? 'https://placehold.co/800x500'"
            :buttonText="$course['buttonText'] ?? 'View Detail'"
            :href="$course['href'] ?? '#'" />
    </div>
    @endforeach
</div>