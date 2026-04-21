<aside class="hidden min-h-screen w-72 border-r border-slate-200 bg-white lg:flex lg:flex-col">
    <div class="border-b border-slate-200 px-6 py-6">
        <div class="flex items-center gap-3">
            <img
                src="{{ asset('images/logo-queens-english.png') }}"
                alt="Queens English Prestige Logo"
                class="h-12 w-auto object-contain">
            <div>
                <p class="text-lg font-bold leading-tight text-slate-900">Queens English Prestige</p>
                <p class="mt-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Admin Panel</p>
            </div>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto px-4 py-6">
        <div class="space-y-8">
            <div>
                <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Main</p>
                <nav class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-xl bg-blue-50 px-4 py-3 text-sm font-semibold text-[var(--color-brand-blue)]">
                        <span>Dashboard</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">
                        <span>Course Orders</span>
                    </a>
                </nav>
            </div>

            <div>
                <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Learning Management</p>
                <nav class="space-y-1">
                    <a href="#" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">Courses</a>
                    <a href="#" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">Modules</a>
                    <a href="#" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">Module Practice</a>
                    <a href="#" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">Final Exam</a>
                    <a href="#" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">Certificates</a>
                </nav>
            </div>

            <div>
                <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">User Management</p>
                <nav class="space-y-1">
                    <a href="#" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">Students</a>
                </nav>
            </div>

            <div>
                <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Website CMS</p>
                <nav class="space-y-1">
                    <a href="#" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">Hero Section</a>
                    <a href="#" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">Why Choose Us</a>
                    <a href="#" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">Vision &amp; Mission</a>
                    <a href="#" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">Mentors</a>
                    <a href="#" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">Free Tests</a>
                    <a href="#" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">Testimonials</a>
                    <a href="#" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">Contact</a>
                    <a href="#" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">FAQ</a>
                    <a href="#" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">News</a>
                </nav>
            </div>
        </div>
    </div>

    <div class="border-t border-slate-200 p-4">
        <div class="space-y-1">
            <a href="#" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">
                <span>Settings</span>
            </a>
            <a href="#" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-rose-600 hover:bg-rose-50">
                <span>Logout</span>
            </a>
        </div>
    </div>
</aside>