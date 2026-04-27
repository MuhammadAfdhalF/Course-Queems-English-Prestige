<x-admin.page-header
    title="Students"
    description="Manage student accounts and view active enrollments across the prestige program."
>
    <x-slot:actions>
        <x-ui.button
            class="px-5 py-3"
            @click="addStudentModalOpen = true">
            <span class="inline-flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 5v14M5 12h14" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 21v-1a4 4 0 00-8 0v1M12 11a4 4 0 100-8 4 4 0 000 8z" />
                </svg>
                Add New Student
            </span>
        </x-ui.button>
    </x-slot:actions>
</x-admin.page-header>