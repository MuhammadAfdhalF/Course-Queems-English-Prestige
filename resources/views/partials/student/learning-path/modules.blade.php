<div class="space-y-4">
    <h2 class="reveal text-2xl font-bold text-slate-900">
        Course Modules
    </h2>

    <div class="space-y-4">
        @foreach ($modules as $index => $module)
            @php
                $delayClass = match ($index) {
                    1 => 'reveal-delay-1',
                    2 => 'reveal-delay-2',
                    3 => 'reveal-delay-3',
                    4 => 'reveal-delay-4',
                    default => '',
                };
            @endphp

            <div class="reveal {{ $delayClass }}">
                <x-student.course-module-item
                    :number="$module['number']"
                    :title="$module['title']"
                    :status="$module['status']"
                    :button-text="$module['buttonText']"
                    :note="$module['note'] ?? ''"
                />
            </div>
        @endforeach
    </div>
</div>