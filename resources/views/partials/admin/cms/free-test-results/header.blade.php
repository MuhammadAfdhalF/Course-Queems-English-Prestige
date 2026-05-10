<x-admin.page-toolbar
    :back-url="route('admin.cms.free-tests.index')"
    back-label="Back to Free Tests">
    <x-slot:actions>
        <a
            href="{{ route('admin.cms.free-tests.index') }}"
            class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
            <x-admin.icon name="settings" class="h-5 w-5" />
            <span>Manage Free Tests</span>
        </a>
    </x-slot:actions>
</x-admin.page-toolbar>