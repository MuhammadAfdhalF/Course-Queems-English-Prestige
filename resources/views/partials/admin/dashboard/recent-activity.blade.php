<x-admin.table-card>
    <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
        <h3 class="text-2xl font-bold text-slate-900">
            Recent Activity
        </h3>

        <a href="#" class="text-sm font-semibold text-[var(--color-brand-blue)]">
            View all orders
        </a>
    </div>

    <div class="space-y-5 p-6">
        @foreach ($activities as $activity)
            <x-admin.activity-item
                :initials="$activity['initials']"
                :name="$activity['name']"
                :description="$activity['description']"
                :variant="$activity['variant']"
                :avatar="$activity['avatar']" />
        @endforeach
    </div>
</x-admin.table-card>