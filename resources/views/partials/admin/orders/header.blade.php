<x-admin.page-header
    title="Course Orders"
    description="Review and process student enrollment requests for prestige courses.">
    <x-slot:actions>
        <x-ui.button
            type="button"
            variant="outline"
            class="px-5 py-3"
            @click="activeTab = 'pending'">
            View Pending Orders
        </x-ui.button>
    </x-slot:actions>
</x-admin.page-header>