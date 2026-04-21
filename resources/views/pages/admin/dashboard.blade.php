@extends('layouts.admin')

@section('content')
<section class="space-y-8">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h2 class="text-4xl font-bold text-slate-900">Dashboard</h2>
            <p class="mt-2 text-slate-600">
                System overview: students, courses, orders, certificates, and revenue.
            </p>
        </div>

        <div class="flex flex-wrap gap-3">
            <x-ui.button variant="outline">Add New Course</x-ui.button>
            <x-ui.button>View Pending Orders</x-ui.button>
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
        <x-ui.stat-card
            title="Total Students"
            value="1,284"
            description="+12.5%"
            descriptionClass="text-emerald-600" />

        <x-ui.stat-card
            title="Total Courses"
            value="42"
            description="Stable"
            descriptionClass="text-slate-500" />

        <x-ui.stat-card
            title="Pending Orders"
            value="12"
            valueClass="text-amber-500"
            description="Action required"
            descriptionClass="text-amber-600" />

        <x-ui.stat-card
            title="Issued Certificates"
            value="740"
            description="+8.1%"
            descriptionClass="text-emerald-600" />
    </div>

    <div class="grid gap-6 xl:grid-cols-3">
        <x-ui.card class="xl:col-span-2">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold text-slate-900">Revenue Analytics</h3>
                    <p class="mt-1 text-sm text-slate-500">Monthly breakdown of income streams</p>
                </div>
                <x-ui.badge variant="info">Monthly</x-ui.badge>
            </div>

            <div class="mt-8 grid gap-6 lg:grid-cols-[2fr_1fr]">
                <div class="flex h-72 items-end gap-4 rounded-2xl bg-slate-50 p-6">
                    <div class="h-24 w-full rounded-t-xl bg-blue-100"></div>
                    <div class="h-32 w-full rounded-t-xl bg-blue-100"></div>
                    <div class="h-44 w-full rounded-t-xl bg-blue-100"></div>
                    <div class="h-28 w-full rounded-t-xl bg-blue-100"></div>
                    <div class="h-40 w-full rounded-t-xl bg-blue-100"></div>
                    <div class="h-56 w-full rounded-t-xl bg-[var(--color-brand-blue)]"></div>
                    <div class="h-10 w-full rounded-t-xl bg-blue-100"></div>
                </div>

                <div class="space-y-6">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-400">This Month</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">Rp 128,450,000</p>
                        <p class="mt-2 text-sm font-semibold text-emerald-600">↑14% vs last month</p>
                    </div>

                    <div class="border-t border-slate-200 pt-6">
                        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-400">Year to Date</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">Rp 842,900,000</p>
                        <p class="mt-2 text-sm font-semibold text-slate-500">Targets: 84% reached</p>
                    </div>
                </div>
            </div>
        </x-ui.card>

        <x-ui.card>
            <div class="flex items-center justify-between">
                <h3 class="text-2xl font-bold text-slate-900">Recent Activity</h3>
                <a href="#" class="text-sm font-semibold text-[var(--color-brand-blue)]">View all orders</a>
            </div>

            <div class="mt-6 space-y-5">
                <div class="flex items-start gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-rose-100 text-sm font-semibold text-rose-700">
                        SJ
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-slate-900">Sarah Johnson</p>
                        <p class="text-sm text-slate-500">Purchased Business English Advanced</p>
                        <div class="mt-2">
                            <x-ui.badge variant="warning">Pending</x-ui.badge>
                        </div>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-blue-100 text-sm font-semibold text-blue-700">
                        AW
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-slate-900">Ahmad Wijaya</p>
                        <p class="text-sm text-slate-500">Purchased IELTS Foundation</p>
                        <div class="mt-2">
                            <x-ui.badge variant="warning">Pending</x-ui.badge>
                        </div>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-emerald-100 text-sm font-semibold text-emerald-700">
                        CS
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-slate-900">Clara Smith</p>
                        <p class="text-sm text-slate-500">Purchased Academic Writing</p>
                        <div class="mt-2">
                            <x-ui.badge variant="success">Completed</x-ui.badge>
                        </div>
                    </div>
                </div>
            </div>
        </x-ui.card>
    </div>
</section>
@endsection