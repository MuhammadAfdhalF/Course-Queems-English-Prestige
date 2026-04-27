<x-admin.filter-bar>
    <div class="lg:col-span-2">
        <x-admin.filter-search
            x-model="search"
            placeholder="Search by Order ID, student, or course..." />
    </div>

    <x-ui.select>
        <option>Date Range</option>
        <option>Today</option>
        <option>This Week</option>
        <option>This Month</option>
    </x-ui.select>

    <x-ui.select>
        <option>All Courses</option>
        <option>Advanced IELTS Prep</option>
        <option>Business English Elite</option>
        <option>General English 101</option>
    </x-ui.select>
</x-admin.filter-bar>