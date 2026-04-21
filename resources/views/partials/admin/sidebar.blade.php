<aside
    x-cloak
    :class="adminSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col border-r border-slate-200 bg-white transition-transform duration-300 lg:static lg:translate-x-0">
    <div class="border-b border-slate-200 px-6 py-6">
        <div class="flex items-center gap-3">
            <img
                src="{{ asset('images/logo-queens-english.png') }}"
                alt="Queens English Prestige Logo"
                class="h-10 w-auto object-contain">
            <div>
                <p class="text-lg font-bold leading-tight text-[var(--color-brand-blue)]">Queens English Prestige</p>
                <p class="mt-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Admin Panel</p>
            </div>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto px-4 py-6">
        <div class="space-y-8">
            <div>
                <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Main</p>
                <nav class="space-y-1">
                    <x-admin.sidebar-item :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 13h6V4H4v9zm0 7h6v-5H4v5zm10 0h6V11h-6v9zm0-18v7h6V2h-6z" />
                            </svg>
                        </x-slot:icon>
                        Dashboard
                    </x-admin.sidebar-item>

                    <x-admin.sidebar-item href="#">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3h2l.4 2M7 13h10l4-8H5.4m1.6 8L5.4 5M7 13l-1.5 3m1.5-3l1.5 3m7-3l1.5 3M9 19.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm10 0a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                            </svg>
                        </x-slot:icon>
                        Course Orders
                    </x-admin.sidebar-item>
                </nav>
            </div>

            <div>
                <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Learning Management</p>
                <nav class="space-y-1">
                    <x-admin.sidebar-item href="#">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v11.494m-5.747-8.62h11.494" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.5 5.25h15A2.25 2.25 0 0121.75 7.5v9A2.25 2.25 0 0119.5 18.75h-15A2.25 2.25 0 012.25 16.5v-9A2.25 2.25 0 014.5 5.25z" />
                            </svg>
                        </x-slot:icon>
                        Courses
                    </x-admin.sidebar-item>

                    <x-admin.sidebar-item href="#">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <rect x="4" y="4" width="16" height="16" rx="2" stroke-width="1.8" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 10h16M10 4v16" />
                            </svg>
                        </x-slot:icon>
                        Modules
                    </x-admin.sidebar-item>

                    <x-admin.sidebar-item href="#">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7h10M4 12h16M4 17h8" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 5l2 2-5 5H12v-2l5-5z" />
                            </svg>
                        </x-slot:icon>
                        Module Practice
                    </x-admin.sidebar-item>

                    <x-admin.sidebar-item href="#">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <rect x="5" y="3" width="14" height="18" rx="2" stroke-width="1.8" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 7h6M9 12h6M9 17h3" />
                            </svg>
                        </x-slot:icon>
                        Final Exam
                    </x-admin.sidebar-item>

                    <x-admin.sidebar-item href="#">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15l-3.5 2 1-4-3-2.5 4-.3L12 6l1.5 4.2 4 .3-3 2.5 1 4z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 19h10" />
                            </svg>
                        </x-slot:icon>
                        Certificates
                    </x-admin.sidebar-item>
                </nav>
            </div>

            <div>
                <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">User Management</p>
                <nav class="space-y-1">
                    <x-admin.sidebar-item href="#">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20a4 4 0 00-8 0M9 7a4 4 0 118 0 4 4 0 01-8 0zM3 20a4 4 0 014-4" />
                            </svg>
                        </x-slot:icon>
                        Students
                    </x-admin.sidebar-item>
                </nav>
            </div>

            <div>
                <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Website CMS</p>
                <nav class="space-y-1">
                    <x-admin.sidebar-item href="#">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <rect x="3" y="5" width="18" height="14" rx="2" stroke-width="1.8" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 9h18" />
                            </svg>
                        </x-slot:icon>
                        Hero Section
                    </x-admin.sidebar-item>

                    <x-admin.sidebar-item href="#">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <circle cx="12" cy="12" r="9" stroke-width="1.8" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 12l2.5 2.5L16 9" />
                            </svg>
                        </x-slot:icon>
                        Why Choose Us
                    </x-admin.sidebar-item>

                    <x-admin.sidebar-item href="#">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 5c-5 0-9 3.5-9 7s4 7 9 7 9-3.5 9-7-4-7-9-7z" />
                                <circle cx="12" cy="12" r="3" stroke-width="1.8" />
                            </svg>
                        </x-slot:icon>
                        Vision & Mission
                    </x-admin.sidebar-item>

                    <x-admin.sidebar-item href="#">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 14a4 4 0 10-8 0M12 12a4 4 0 100-8 4 4 0 000 8zm8 8a8 8 0 10-16 0" />
                            </svg>
                        </x-slot:icon>
                        Mentors
                    </x-admin.sidebar-item>

                    <x-admin.sidebar-item href="#">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <rect x="5" y="3" width="14" height="18" rx="2" stroke-width="1.8" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7h8M8 11h8M8 15h5" />
                            </svg>
                        </x-slot:icon>
                        Free Tests
                    </x-admin.sidebar-item>

                    <x-admin.sidebar-item href="#">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 17.25l-5.227 2.748 1-5.823L3.546 10.5l5.846-.849L12 4.5l2.608 5.151 5.846.849-4.227 3.675 1 5.823z" />
                            </svg>
                        </x-slot:icon>
                        Testimonials
                    </x-admin.sidebar-item>

                    <x-admin.sidebar-item href="#">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8m-18 8h18V8H3v8z" />
                            </svg>
                        </x-slot:icon>
                        Contact
                    </x-admin.sidebar-item>

                    <x-admin.sidebar-item href="#">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <circle cx="12" cy="12" r="9" stroke-width="1.8" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.09 9a3 3 0 115.82 1c0 2-3 2-3 4" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 17h.01" />
                            </svg>
                        </x-slot:icon>
                        FAQ
                    </x-admin.sidebar-item>

                    <x-admin.sidebar-item href="#">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <rect x="3" y="5" width="18" height="14" rx="2" stroke-width="1.8" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 9h10M7 13h6" />
                            </svg>
                        </x-slot:icon>
                        News
                    </x-admin.sidebar-item>
                </nav>
            </div>
        </div>
    </div>

    <div class="border-t border-slate-200 p-4">
        <div class="space-y-1">
            <x-admin.sidebar-item href="#">
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.325 4.317a1 1 0 011.35-.936l.94.47a1 1 0 00.894 0l.94-.47a1 1 0 011.35.936l.094 1.04a1 1 0 00.592.823l.95.475a1 1 0 01.376 1.527l-.665.83a1 1 0 000 1.25l.665.83a1 1 0 01-.376 1.527l-.95.475a1 1 0 00-.592.823l-.094 1.04a1 1 0 01-1.35.936l-.94-.47a1 1 0 00-.894 0l-.94.47a1 1 0 01-1.35-.936l-.094-1.04a1 1 0 00-.592-.823l-.95-.475a1 1 0 01-.376-1.527l.665-.83a1 1 0 000-1.25l-.665-.83a1 1 0 01.376-1.527l.95-.475a1 1 0 00.592-.823l.094-1.04z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </x-slot:icon>
                Settings
            </x-admin.sidebar-item>

            <x-admin.sidebar-item href="#" class="text-rose-600 hover:bg-rose-50 hover:text-rose-600">
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H9" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 20H6a2 2 0 01-2-2V6a2 2 0 012-2h7" />
                    </svg>
                </x-slot:icon>
                Logout
            </x-admin.sidebar-item>
        </div>
    </div>
</aside>