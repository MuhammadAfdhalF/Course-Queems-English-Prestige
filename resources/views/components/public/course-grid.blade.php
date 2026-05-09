@props([
'courses' => [],
])

@php
$courseItems = collect($courses);
@endphp

<div {{ $attributes->merge(['class' => 'grid items-stretch gap-6 md:grid-cols-2 xl:grid-cols-4']) }}>
    @forelse ($courseItems as $course)
    @php
    $isArray = is_array($course);

    $title = $isArray ? ($course['title'] ?? 'Course Title') : ($course->name ?? 'Course Title');
    $level = $isArray ? ($course['level'] ?? 'Program') : ($course->courseProgram?->name ?? 'Program');
    $mode = $isArray ? ($course['mode'] ?? 'Online') : ucfirst($course->learning_mode ?? 'Online');
    $price = $isArray ? ($course['price'] ?? 'Rp 0') : 'Rp ' . number_format((float) ($course->price ?? 0), 0, ',', '.');
    $description = $isArray ? ($course['description'] ?? '') : ($course->short_description ?? '');

    $image = $isArray
    ? ($course['image'] ?? 'https://placehold.co/800x500/e8eef8/1e293b?text=Course')
    : (($course->thumbnail_file ?? null)
    ? asset('storage/' . $course->thumbnail_file)
    : 'https://placehold.co/800x500/e8eef8/1e293b?text=' . urlencode($title));

    $href = $isArray ? ($course['href'] ?? '#') : route('courses.show', $course);

    $modeLabel = strtoupper($mode);
    $levelLabel = strtoupper($level);
    @endphp

    <article class="group motion-card flex h-full flex-col overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-md">
        <a href="{{ $href }}" class="block">
            <div class="relative aspect-[16/10] overflow-hidden bg-slate-100">
                <img
                    src="{{ $image }}"
                    alt="{{ $title }}"
                    class="motion-image h-full w-full object-cover"
                    onerror="this.src='https://placehold.co/800x500/e8eef8/1e293b?text=Course';">

                <div class="absolute left-3 top-3 flex max-w-[calc(100%-1.5rem)] flex-wrap gap-2">
                    <span class="inline-flex items-center rounded-lg bg-emerald-400 px-3 py-1.5 text-[9px] font-extrabold uppercase tracking-[0.14em] text-slate-900">
                        {{ $modeLabel }}
                    </span>

                    <span class="inline-flex max-w-[170px] items-center truncate rounded-lg bg-[var(--color-brand-gold)] px-3 py-1.5 text-[9px] font-extrabold uppercase tracking-[0.14em] text-white">
                        {{ $levelLabel }}
                    </span>
                </div>
            </div>
        </a>

        <div class="flex flex-1 flex-col p-5">
            <a href="{{ $href }}">
                <h3 class="line-clamp-2 min-h-[2.9rem] text-[21px] font-bold leading-tight text-[var(--color-brand-blue)] transition hover:text-[var(--color-brand-gold)]">
                    {{ $title }}
                </h3>
            </a>

            <p class="mt-3 line-clamp-3 min-h-[4.5rem] text-sm leading-6 text-slate-500">
                {{ $description ?: 'Explore this program and start building your English skills with Queens English Prestige.' }}
            </p>

            <div class="mt-auto pt-5">
                <p class="text-[10px] font-bold uppercase tracking-[0.14em] text-slate-400">
                    Tuition Fee
                </p>

                <div class="mt-3 flex items-end justify-between gap-3">
                    <p class="max-w-[50%] break-words text-[18px] font-bold leading-tight text-[var(--color-brand-blue)]">
                        {{ $price }}
                    </p>

                    <a
                        href="{{ $href }}"
                        class="motion-button inline-flex h-10 shrink-0 items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-4 text-[10px] font-extrabold uppercase tracking-[0.12em] text-white transition hover:opacity-90">
                        View Detail
                    </a>
                </div>
            </div>
        </div>
    </article>
    @empty
    <div class="col-span-full rounded-[24px] border border-slate-200 bg-white px-6 py-12 text-center shadow-sm">
        <h3 class="text-2xl font-bold text-slate-900">
            No programs available yet.
        </h3>

        <p class="mx-auto mt-3 max-w-xl text-sm leading-7 text-slate-500">
            Please check back later. Our team is preparing learning programs for you.
        </p>
    </div>
    @endforelse
</div>