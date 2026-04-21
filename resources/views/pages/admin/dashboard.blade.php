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
        <x-admin.metric-card
            title="Total Students"
            value="1,284"
            description="+12.5%"
            accent="blue"
            icon="users" />

        <x-admin.metric-card
            title="Total Courses"
            value="42"
            description="Stable"
            icon="book" />

        <x-admin.metric-card
            title="Pending Orders"
            value="12"
            description="Action required"
            accent="gold"
            icon="cart" />

        <x-admin.metric-card
            title="Issued Certificates"
            value="740"
            description="+8.1%"
            accent="blue"
            icon="certificate" />
    </div>

    <div class="grid gap-6 xl:grid-cols-[2fr_1fr]">
        <x-admin.table-card>
            <div class="border-b border-slate-200 px-6 py-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-slate-900">Revenue Analytics</h3>
                        <p class="mt-1 text-sm text-slate-500">Monthly breakdown of income streams</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <x-admin.status-badge variant="approved">Monthly</x-admin.status-badge>
                        <x-admin.status-badge>2024</x-admin.status-badge>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 p-6 lg:grid-cols-[2fr_1fr]">
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
                        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-400">Year to Date (YTD)</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">Rp 842,900,000</p>
                        <p class="mt-2 text-sm font-semibold text-slate-500">Targets: 84% reached</p>
                    </div>
                </div>
            </div>
        </x-admin.table-card>

        <x-admin.table-card>
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
                <h3 class="text-2xl font-bold text-slate-900">Recent Activity</h3>
                <a href="#" class="text-sm font-semibold text-[var(--color-brand-blue)]">View all orders</a>
            </div>

            <div class="space-y-5 p-6">
                <x-admin.activity-item
                    initials="SJ"
                    name="Sarah Johnson"
                    description="Purchased Business English Advanced"
                    variant="pending"
                    avatar="rose" />

                <x-admin.activity-item
                    initials="AW"
                    name="Ahmad Wijaya"
                    description="Purchased IELTS Foundation"
                    variant="pending"
                    avatar="blue" />

                <x-admin.activity-item
                    initials="CS"
                    name="Clara Smith"
                    description="Purchased Academic Writing"
                    variant="completed"
                    avatar="green" />
            </div>
        </x-admin.table-card>
    </div>

    <x-admin.table-card>
        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
            <h3 class="text-2xl font-bold text-slate-900">Recent Transactions</h3>
            <x-ui.button variant="outline">Export CSV</x-ui.button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-left">
                <thead class="border-b border-slate-200 bg-slate-50">
                    <tr class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">
                        <th class="px-6 py-4">Order ID</th>
                        <th class="px-6 py-4">Student</th>
                        <th class="px-6 py-4">Course</th>
                        <th class="px-6 py-4">Price</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200 bg-white">
                    <tr>
                        <td class="px-6 py-5 text-slate-500">#ORD-2024-8291</td>
                        <td class="px-6 py-5 font-semibold text-slate-900">Sarah Johnson</td>
                        <td class="px-6 py-5 text-slate-900">Business English Adv.</td>
                        <td class="px-6 py-5 font-semibold text-slate-900">Rp 2,500,000</td>
                        <td class="px-6 py-5"><x-admin.status-badge variant="pending">Pending</x-admin.status-badge></td>
                        <td class="px-6 py-5 text-slate-500">June 12, 2024</td>
                        <td class="px-6 py-5"><x-admin.table-action-button /></td>
                    </tr>

                    <tr>
                        <td class="px-6 py-5 text-slate-500">#ORD-2024-8290</td>
                        <td class="px-6 py-5 font-semibold text-slate-900">Ahmad Wijaya</td>
                        <td class="px-6 py-5 text-slate-900">IELTS Foundation</td>
                        <td class="px-6 py-5 font-semibold text-slate-900">Rp 3,200,000</td>
                        <td class="px-6 py-5"><x-admin.status-badge variant="pending">Pending</x-admin.status-badge></td>
                        <td class="px-6 py-5 text-slate-500">June 12, 2024</td>
                        <td class="px-6 py-5"><x-admin.table-action-button /></td>
                    </tr>

                    <tr>
                        <td class="px-6 py-5 text-slate-500">#ORD-2024-8289</td>
                        <td class="px-6 py-5 font-semibold text-slate-900">Clara Smith</td>
                        <td class="px-6 py-5 text-slate-900">Academic Writing</td>
                        <td class="px-6 py-5 font-semibold text-slate-900">Rp 1,800,000</td>
                        <td class="px-6 py-5"><x-admin.status-badge variant="completed">Completed</x-admin.status-badge></td>
                        <td class="px-6 py-5 text-slate-500">June 11, 2024</td>
                        <td class="px-6 py-5"><x-admin.table-action-button /></td>
                    </tr>

                    <tr>
                        <td class="px-6 py-5 text-slate-500">#ORD-2024-8288</td>
                        <td class="px-6 py-5 font-semibold text-slate-900">Mark Peterson</td>
                        <td class="px-6 py-5 text-slate-900">Intensive TOEFL Prep</td>
                        <td class="px-6 py-5 font-semibold text-slate-900">Rp 4,500,000</td>
                        <td class="px-6 py-5"><x-admin.status-badge variant="completed">Completed</x-admin.status-badge></td>
                        <td class="px-6 py-5 text-slate-500">June 11, 2024</td>
                        <td class="px-6 py-5"><x-admin.table-action-button /></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="flex flex-col gap-4 border-t border-slate-200 px-6 py-5 text-sm text-slate-500 lg:flex-row lg:items-center lg:justify-between">
            <p>Showing 4 of 856 transactions</p>

            <div class="flex items-center gap-3">
                <x-ui.button variant="outline" class="px-4 py-2">Previous</x-ui.button>
                <x-ui.button variant="outline" class="px-4 py-2">Next</x-ui.button>
            </div>
        </div>
    </x-admin.table-card>
</section>
@endsection