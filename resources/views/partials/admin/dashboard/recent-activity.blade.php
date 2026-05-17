<x-admin.table-card>
    <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
        <div>
            <h3 class="text-2xl font-bold text-slate-900">
                Recent Activity
            </h3>

            <p class="mt-1 text-sm text-slate-500">
                Latest course order activity.
            </p>
        </div>

        <a href="{{ route('admin.orders.index') }}" class="text-sm font-semibold text-[var(--color-brand-blue)]">
            View all orders
        </a>
    </div>

    <div class="space-y-5 p-6">
        @forelse ($activities as $activity)
        <x-admin.activity-item
            :initials="$activity['initials']"
            :name="$activity['name']"
            :description="$activity['description']"
            :variant="$activity['variant']"
            :avatar="$activity['avatar']" />
        @empty
        <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-5 py-8 text-center">
            <h4 class="text-base font-extrabold text-slate-900">
                No recent activity
            </h4>

            <p class="mt-2 text-sm leading-6 text-slate-500">
                New course orders will appear here.
            </p>
        </div>
        @endforelse
    </div>
</x-admin.table-card>