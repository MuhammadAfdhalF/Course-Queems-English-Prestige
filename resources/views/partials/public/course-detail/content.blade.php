<section class="pt-6">
    <div class="space-y-12">
        <div class="reveal">
            <h2 class="text-3xl font-bold text-slate-900">
                Course Overview
            </h2>

            @if (filled($courseLevel->description))
            <div class="rich-text-content mt-5 text-base leading-8 text-slate-600">
                {!! $courseLevel->description !!}
            </div>
            @else
            <div class="mt-5 rounded-2xl border border-slate-200 bg-slate-50 p-5">
                <p class="text-base leading-8 text-slate-500">
                    Course overview will be available soon.
                </p>
            </div>
            @endif
        </div>

        <div class="reveal reveal-delay-1">
            <h2 class="text-3xl font-bold text-slate-900">
                Course Syllabus
            </h2>

            @if ($courseLevel->modules->isNotEmpty())
            <div class="mt-6 space-y-4">
                @foreach ($courseLevel->modules as $module)
                @php
                $delayClass = match ($loop->index % 4) {
                1 => 'reveal-delay-1',
                2 => 'reveal-delay-2',
                3 => 'reveal-delay-3',
                default => '',
                };
                @endphp

                <div class="reveal {{ $delayClass }}">
                    <x-public.syllabus-item
                        :title="$module->title"
                        :content="$module->short_description ?: 'Module description will be available soon.'"
                        :open="$loop->first"
                        :is-preview="$module->is_preview" />
                </div>
                @endforeach
            </div>
            @else
            <div class="mt-6 rounded-2xl border border-slate-200 bg-slate-50 p-5">
                <p class="text-base leading-8 text-slate-500">
                    Course syllabus will be available soon.
                </p>
            </div>
            @endif
        </div>
    </div>
</section>