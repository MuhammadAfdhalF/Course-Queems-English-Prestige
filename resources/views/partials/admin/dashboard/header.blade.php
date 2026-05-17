<div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
    <div>
        <h2 class="text-4xl font-bold text-slate-900">
            Dashboard
        </h2>

        <p class="mt-2 text-slate-600">
            Real-time overview: students, enrollments, orders, reviews, certificates, and revenue.
        </p>
    </div>

    <div class="flex flex-wrap gap-3">
        <a href="{{ route('admin.course-management.programs.index') }}">
            <x-ui.button variant="outline">
                Manage Courses
            </x-ui.button>
        </a>

        <a href="{{ route('admin.orders.index') }}">
            <x-ui.button>
                View Pending Orders
            </x-ui.button>
        </a>
    </div>
</div>