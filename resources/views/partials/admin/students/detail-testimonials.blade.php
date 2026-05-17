<x-admin.table-card>
    <div class="border-b border-slate-200 px-6 py-5">
        <h2 class="text-xl font-black text-slate-900">
            Testimonial History
        </h2>
        <p class="mt-1 text-sm text-slate-500">
            Testimonials submitted by this student.
        </p>
    </div>

    <div class="divide-y divide-slate-100">
        @forelse ($testimonials as $testimonial)
        <div class="p-6">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="font-black text-slate-900">
                        {{ $testimonial->courseLevel?->name ?? 'Unknown Course' }}
                    </p>

                    <p class="mt-1 text-xs font-semibold text-slate-400">
                        Rating: {{ $testimonial->rating }}/5
                    </p>
                </div>

                <div class="flex flex-wrap gap-2">
                    @if ($testimonial->is_active)
                    <x-admin.status-badge variant="completed">
                        Published
                    </x-admin.status-badge>
                    @else
                    <x-admin.status-badge variant="pending">
                        Unpublished
                    </x-admin.status-badge>
                    @endif

                    @if ($testimonial->is_featured)
                    <x-admin.status-badge variant="approved">
                        Featured
                    </x-admin.status-badge>
                    @endif
                </div>
            </div>

            <p class="mt-4 text-sm leading-7 text-slate-600">
                {{ $testimonial->testimonial }}
            </p>
        </div>
        @empty
        <div class="px-6 py-10 text-center text-sm text-slate-500">
            No testimonials yet.
        </div>
        @endforelse
    </div>
</x-admin.table-card>