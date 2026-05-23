<x-admin.data-table>
    <x-slot:head>
        <th class="px-6 py-4">Student</th>
        <th class="px-6 py-4">Subject</th>
        <th class="px-6 py-4">Testimonial</th>
        <th class="px-6 py-4">Rating</th>
        <th class="px-6 py-4">Type</th>
        <th class="px-6 py-4">Homepage</th>
        <th class="w-[190px] px-6 py-4 text-center">Action</th>
    </x-slot:head>

    @forelse ($testimonials as $testimonial)
    @php
    $deletePayload = [
    'id' => $testimonial->id,
    'title' => $testimonial->name . ' - ' . Str::limit($testimonial->testimonial, 40),
    'delete_url' => route('admin.cms.testimonials.destroy', $testimonial),
    ];

    $studentName = $testimonial->student?->name ?? $testimonial->name;
    $studentEmail = $testimonial->student?->email;
    $courseName = $testimonial->courseLevel?->name;
    $programName = $testimonial->courseLevel?->courseProgram?->name;

    $isCourse = $testimonial->type === 'course';
    $isVisibleOnHome = $testimonial->is_active && $testimonial->is_featured;

    $typeLabel = $isCourse ? 'Course Feedback' : 'Company Feedback';
    $typeClass = $isCourse
    ? 'bg-blue-50 text-blue-700'
    : 'bg-purple-50 text-purple-700';
    @endphp

    <tr class="text-sm text-slate-700 transition hover:bg-slate-50/80">
        <td class="min-w-[220px] px-6 py-5">
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-slate-100 text-sm font-black text-slate-700">
                    {{ strtoupper(substr($studentName, 0, 1)) }}
                </div>

                <div class="min-w-0">
                    <p class="truncate font-black text-slate-900">
                        {{ $studentName }}
                    </p>

                    @if ($studentEmail)
                    <p class="mt-1 truncate text-xs font-semibold text-slate-400">
                        {{ $studentEmail }}
                    </p>
                    @endif

                    <p class="mt-1 text-xs font-semibold text-slate-400">
                        {{ $testimonial->created_at?->format('d M Y, H:i') }}
                    </p>
                </div>
            </div>
        </td>

        <td class="min-w-[220px] px-6 py-5">
            @if ($isCourse)
            <p class="font-black text-slate-900">
                {{ $courseName ?? 'Unknown Course' }}
            </p>

            <p class="mt-1 text-xs font-semibold text-slate-400">
                {{ $programName ?? 'Course Program' }}
            </p>
            @else
            <p class="font-black text-slate-900">
                Queens English Prestige
            </p>

            <p class="mt-1 text-xs font-semibold text-slate-400">
                General company feedback
            </p>
            @endif
        </td>

        <td class="max-w-md px-6 py-5">
            <p class="line-clamp-3 leading-6 text-slate-600">
                {{ $testimonial->testimonial }}
            </p>
        </td>

        <td class="whitespace-nowrap px-6 py-5">
            <div class="text-sm text-[var(--color-brand-gold)]">
                @for ($i = 1; $i <= 5; $i++)
                    {{ $i <= (int) $testimonial->rating ? '★' : '☆' }}
                    @endfor
                    </div>

                    <p class="mt-1 text-xs font-black text-slate-400">
                        {{ (int) $testimonial->rating }}/5
                    </p>
        </td>

        <td class="whitespace-nowrap px-6 py-5">
            <span class="inline-flex rounded-full px-3 py-1 text-xs font-black {{ $typeClass }}">
                {{ $typeLabel }}
            </span>
        </td>

        <td class="whitespace-nowrap px-6 py-5">
            @if ($isVisibleOnHome)
            <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-black text-emerald-700">
                Visible on Home
            </span>
            @else
            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-black text-slate-500">
                Hidden
            </span>
            @endif
        </td>

        <td class="px-6 py-5">
            <div class="flex min-w-[170px] flex-wrap justify-center gap-2">
                @if ($isVisibleOnHome)
                <form
                    action="{{ route('admin.cms.testimonials.hide-from-home', $testimonial) }}"
                    method="POST">
                    @csrf
                    @method('PUT')

                    <button
                        type="submit"
                        title="Hide from Home"
                        class="inline-flex h-10 items-center justify-center rounded-xl bg-slate-100 px-3 text-xs font-black text-slate-700 transition hover:bg-slate-200">
                        Hide
                    </button>
                </form>
                @else
                <form
                    action="{{ route('admin.cms.testimonials.show-on-home', $testimonial) }}"
                    method="POST">
                    @csrf
                    @method('PUT')

                    <button
                        type="submit"
                        title="Show on Home"
                        class="inline-flex h-10 items-center justify-center rounded-xl bg-emerald-50 px-3 text-xs font-black text-emerald-700 transition hover:bg-emerald-100">
                        Show on Home
                    </button>
                </form>
                @endif

                <button
                    type="button"
                    title="Delete"
                    @click='openDeleteModal(@json($deletePayload))'
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-rose-50 text-rose-600 transition hover:bg-rose-100">
                    <x-admin.icon name="trash" class="h-4 w-4" />
                </button>
            </div>
        </td>
    </tr>
    @empty
    <x-admin.empty-state
        colspan="7"
        title="No testimonials found"
        description="Course and company testimonials submitted by students will appear here." />
    @endforelse
</x-admin.data-table>