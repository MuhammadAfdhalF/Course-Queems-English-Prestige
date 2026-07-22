<div class="mx-auto max-w-7xl px-4 py-12 lg:px-8">
    @if (! empty($courseItems))
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
            <x-student.course-card
                :title="$course['title']"
                :mode="$course['mode']"
                :level="$course['level']"
                :price="$course['price']"
                :description="$course['description']"
                :image="$course['image']"
                :poster="$course['poster'] ?? null"
                :thumbnail-type="$course['thumbnailType'] ?? 'image'"
                :href="$course['href']"
                :button-text="$course['buttonText']"
                :status-label="$course['statusLabel']"
                :status-class="$course['statusClass']"
                :button-class="$course['buttonClass']"
                :disabled="$course['disabled']" />
        </div>
        @endforeach
    </div>
    @else
    <div class="reveal rounded-[28px] border border-slate-200 bg-[#F7FAFD] px-6 py-16 text-center">
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-white text-[var(--color-brand-blue)] shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v11.494m-5.747-8.62h11.494" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.5 5.25h15A2.25 2.25 0 0121.75 7.5v9A2.25 2.25 0 0119.5 18.75h-15A2.25 2.25 0 012.25 16.5v-9A2.25 2.25 0 014.5 5.25z" />
            </svg>
        </div>

        <h2 class="mt-5 text-2xl font-bold text-slate-900">
            No courses found.
        </h2>

        <p class="mx-auto mt-3 max-w-xl text-sm leading-7 text-slate-500">
            Please try another filter or browse all available programs.
        </p>

        <div class="mt-7">
            <a href="{{ route('student.all-courses') }}">
                <x-ui.button class="px-6 py-3">
                    Reset Filter
                </x-ui.button>
            </a>
        </div>
    </div>
    @endif
</div>