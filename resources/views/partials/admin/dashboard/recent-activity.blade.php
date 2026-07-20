<x-admin.table-card class="overflow-hidden">
    <div class="flex items-center justify-between border-b border-slate-200 bg-slate-50/70 px-6 py-5">
        <div>
            <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-400">
                Activity
            </p>

            <h3 class="mt-1 text-2xl font-black text-slate-900">
                Recent Activity
            </h3>

            <p class="mt-1 text-sm font-semibold text-slate-500">
                Latest course order activity.
            </p>
        </div>

        <a href="{{ route('admin.orders.index') }}" class="text-sm font-black text-[var(--color-brand-blue)] hover:underline">
            View all
        </a>
    </div>

    <div class="space-y-4 p-5">
        @forelse ($activities as $activity)
        <div class="rounded-3xl border border-slate-200 bg-white p-4 transition hover:bg-slate-50">
            <x-admin.activity-item
                :initials="$activity['initials']"
                :name="$activity['name']"
                :description="$activity['description']"
                :variant="$activity['variant']"
                :avatar="$activity['avatar']" />
        </div>
        @empty
        <div class="rounded-3xl border border-dashed border-slate-300 bg-slate-50 px-5 py-10 text-center">
            <h4 class="text-base font-black text-slate-900">
                No recent activity
            </h4>

            <p class="mx-auto mt-2 max-w-sm text-sm font-semibold leading-6 text-slate-500">
                New course orders will appear here.
            </p>
        </div>
        @endforelse
    </div>
</x-admin.table-card>