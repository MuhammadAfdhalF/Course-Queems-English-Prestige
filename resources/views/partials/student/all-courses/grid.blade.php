<div class="mx-auto max-w-7xl px-4 py-12 lg:px-8">
    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
        @foreach ($courseItems as $index => $course)
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
                    :title="$course['title']"
                    :mode="$course['mode']"
                    :level="$course['level']"
                    :price="$course['price']"
                    :description="$course['description']"
                    :image="$course['image']"
                    :href="$course['href']" />
            </div>
        @endforeach
    </div>
</div>