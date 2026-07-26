<div class="overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-2xs">
    <div class="flex items-center justify-between border-b border-slate-100 bg-white px-5 py-4 sm:px-6">
        <div>
            <h3 class="text-base font-bold text-slate-900">
                Recent Activity
            </h3>
            <p class="text-xs font-medium text-slate-500">
                Latest course order activity.
            </p>
        </div>

        <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-[#080D4D] hover:underline">
            View all
        </a>
    </div>

    <div class="divide-y divide-slate-100">
        @forelse ($activities as $activity)
        <div class="p-3.5 sm:p-4 transition hover:bg-slate-50/80">
            <x-admin.activity-item
                :initials="$activity['initials']"
                :name="$activity['name']"
                :description="$activity['description']"
                :variant="$activity['variant']"
                :avatar="$activity['avatar']" />
        </div>
        @empty
        <div class="px-5 py-7 text-center">
            <h4 class="text-xs font-bold text-slate-900">
                No recent activity
            </h4>
            <p class="mx-auto mt-0.5 max-w-xs text-[11px] font-medium text-slate-500">
                New course orders will appear here.
            </p>
        </div>
        @endforelse
    </div>
</div>