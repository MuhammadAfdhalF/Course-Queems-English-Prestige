<aside
    :class="{
        'translate-x-0': mobileOpen,
        '-translate-x-full lg:translate-x-0': !mobileOpen,
        'lg:w-[80px]': desktopCollapsed,
        'lg:w-[272px]': !desktopCollapsed
    }"
    class="fixed inset-y-0 left-0 z-40 flex w-[280px] sm:w-[300px] flex-col border-r border-slate-200/90 bg-white transition-all duration-300 ease-in-out shadow-lg lg:shadow-none">

    {{-- Sidebar Header / Logo --}}
    <div class="flex h-[76px] shrink-0 items-center justify-between border-b border-slate-100 px-4">
        <a href="{{ route('admin.dashboard') }}" class="group relative flex items-center gap-3 overflow-hidden focus:outline-none">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#080D4D]/5 p-1.5 ring-1 ring-[#080D4D]/10">
                <img
                    src="{{ asset('images/logo-queens-english.png') }}"
                    alt="Queens English Prestige Logo"
                    class="h-full w-full object-contain">
            </div>

            <div x-show="!desktopCollapsed" x-cloak class="min-w-0 transition-opacity duration-200 flex flex-col justify-center">
                <p class="text-[15px] font-bold leading-tight text-[#080D4D] truncate">
                    Queens English
                </p>
                <p class="text-[15px] font-bold leading-tight text-[#080D4D] truncate">
                    Prestige
                </p>
                <p class="mt-0.5 text-[10px] font-bold uppercase tracking-[0.18em] text-[#AD6B10] leading-tight truncate">
                    ADMIN
                </p>
            </div>

            {{-- Collapsed Brand Tooltip --}}
            <div
                x-show="desktopCollapsed"
                x-cloak
                class="pointer-events-none absolute left-full ml-3 z-50 hidden whitespace-nowrap rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white shadow-xl opacity-0 group-hover:opacity-100 group-hover:block transition-opacity duration-200">
                Queens English Prestige — Admin
            </div>
        </a>

        {{-- Mobile Close Button --}}
        <button
            @click="closeMobile()"
            type="button"
            class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600 lg:hidden"
            aria-label="Close sidebar">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- Navigation Menu Container --}}
    <div class="flex-1 overflow-y-auto overflow-x-hidden px-3 py-4">
        <div class="space-y-6">

            {{-- MAIN SECTION --}}
            <div>
                <p
                    x-show="!desktopCollapsed"
                    x-cloak
                    class="mb-2 px-3 text-[11px] font-bold uppercase tracking-widest text-slate-400 transition-all">
                    Main
                </p>

                <nav class="space-y-1">
                    <x-admin.sidebar-item
                        :href="route('admin.dashboard')"
                        :active="request()->routeIs('admin.dashboard')"
                        title="Dashboard">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 13h6V4H4v9zm0 7h6v-5H4v5zm10 0h6V11h-6v9zm0-18v7h6V2h-6z" />
                            </svg>
                        </x-slot:icon>
                        Dashboard
                    </x-admin.sidebar-item>

                    <x-admin.sidebar-item
                        :href="route('home')"
                        :active="false"
                        title="View Website"
                        target="_blank">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </x-slot:icon>
                        View Website
                    </x-admin.sidebar-item>

                    <x-admin.sidebar-item
                        :href="route('admin.notifications.index')"
                        :active="request()->routeIs('admin.notifications.*')"
                        title="Notifications">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0m6 0H9" />
                            </svg>
                        </x-slot:icon>
                        Notifications
                    </x-admin.sidebar-item>
                </nav>
            </div>

            {{-- COURSE MANAGEMENT SECTION --}}
            <div>
                <p
                    x-show="!desktopCollapsed"
                    x-cloak
                    class="mb-2 px-3 text-[11px] font-bold uppercase tracking-widest text-slate-400 transition-all">
                    Course Management
                </p>

                <nav class="space-y-1">
                    <x-admin.sidebar-item
                        :href="route('admin.course-management.programs.index')"
                        :active="request()->routeIs('admin.course-management.programs.*')"
                        title="Course Programs">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </x-slot:icon>
                        Course Programs
                    </x-admin.sidebar-item>
                </nav>
            </div>

            {{-- ORDER & PAYMENT SECTION --}}
            <div>
                <p
                    x-show="!desktopCollapsed"
                    x-cloak
                    class="mb-2 px-3 text-[11px] font-bold uppercase tracking-widest text-slate-400 transition-all">
                    Order & Payment
                </p>

                <nav class="space-y-1">
                    <x-admin.sidebar-item
                        :href="route('admin.orders.index')"
                        :active="request()->routeIs('admin.orders.*')"
                        title="Course Orders">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </x-slot:icon>
                        Course Orders
                    </x-admin.sidebar-item>

                    <x-admin.sidebar-item
                        :href="route('admin.payments.index')"
                        :active="request()->routeIs('admin.payments.*')"
                        title="Payments">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </x-slot:icon>
                        Payments
                    </x-admin.sidebar-item>

                    <x-admin.sidebar-item
                        :href="route('admin.revenue.index')"
                        :active="request()->routeIs('admin.revenue.*')"
                        title="Revenue Report">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </x-slot:icon>
                        Revenue Report
                    </x-admin.sidebar-item>
                </nav>
            </div>

            {{-- STUDENT MANAGEMENT SECTION --}}
            <div>
                <p
                    x-show="!desktopCollapsed"
                    x-cloak
                    class="mb-2 px-3 text-[11px] font-bold uppercase tracking-widest text-slate-400 transition-all">
                    Student Management
                </p>

                <nav class="space-y-1">
                    <x-admin.sidebar-item
                        :href="route('admin.students.index')"
                        :active="request()->routeIs('admin.students.*')"
                        title="Students">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </x-slot:icon>
                        Students
                    </x-admin.sidebar-item>

                    <x-admin.sidebar-item
                        :href="route('admin.course-access.index')"
                        :active="request()->routeIs('admin.course-access.*')"
                        title="Course Access">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                        </x-slot:icon>
                        Course Access
                    </x-admin.sidebar-item>
                </nav>
            </div>

            {{-- CERTIFICATES SECTION --}}
            <div>
                <p
                    x-show="!desktopCollapsed"
                    x-cloak
                    class="mb-2 px-3 text-[11px] font-bold uppercase tracking-widest text-slate-400 transition-all">
                    Certificates
                </p>

                <nav class="space-y-1">
                    <x-admin.sidebar-item
                        :href="route('admin.course-management.certificate-templates.index')"
                        :active="request()->routeIs('admin.course-management.certificate-templates.*')"
                        title="Certificate Templates">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </x-slot:icon>
                        Certificate Templates
                    </x-admin.sidebar-item>

                    <x-admin.sidebar-item
                        :href="route('admin.course-management.certificates.index')"
                        :active="request()->routeIs('admin.course-management.certificates.*')"
                        title="Issued Certificates">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                            </svg>
                        </x-slot:icon>
                        Issued Certificates
                    </x-admin.sidebar-item>

                    <x-admin.sidebar-item
                        :href="route('admin.course-management.certificate-settings.edit')"
                        :active="request()->routeIs('admin.course-management.certificate-settings.*')"
                        title="Certificate Settings">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </x-slot:icon>
                        Certificate Settings
                    </x-admin.sidebar-item>
                </nav>
            </div>

            {{-- WEBSITE CMS SECTION --}}
            <div>
                <p
                    x-show="!desktopCollapsed"
                    x-cloak
                    class="mb-2 px-3 text-[11px] font-bold uppercase tracking-widest text-slate-400 transition-all">
                    Website CMS
                </p>

                <nav class="space-y-1">
                    <x-admin.sidebar-item
                        :href="route('admin.cms.home.index')"
                        :active="request()->routeIs('admin.cms.home.*') || request()->routeIs('admin.cms.hero-sections.*')"
                        title="Home Page">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </x-slot:icon>
                        Home Page
                    </x-admin.sidebar-item>

                    <x-admin.sidebar-item
                        :href="route('admin.cms.about.index')"
                        :active="request()->routeIs('admin.cms.about.*') || request()->routeIs('admin.cms.why-choose-us.*')"
                        title="About Page">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </x-slot:icon>
                        About Page
                    </x-admin.sidebar-item>

                    <x-admin.sidebar-item
                        :href="route('admin.cms.contact.index')"
                        :active="request()->routeIs('admin.cms.contact.*')"
                        title="Contact Page">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8m-18 8h18V8H3v8z" />
                            </svg>
                        </x-slot:icon>
                        Contact Page
                    </x-admin.sidebar-item>

                    <x-admin.sidebar-item
                        :href="route('admin.cms.news-gallery.index')"
                        :active="request()->routeIs('admin.cms.news-gallery.*')"
                        title="News & Gallery">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                        </x-slot:icon>
                        News & Gallery
                    </x-admin.sidebar-item>

                    <x-admin.sidebar-item
                        :href="route('admin.cms.testimonials.index')"
                        :active="request()->routeIs('admin.cms.testimonials.*')"
                        title="Testimonials">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                        </x-slot:icon>
                        Testimonials
                    </x-admin.sidebar-item>

                    <x-admin.sidebar-item
                        :href="route('admin.cms.free-tests.index')"
                        :active="request()->routeIs('admin.cms.free-tests.*')"
                        title="Free Tests">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </x-slot:icon>
                        Free Tests
                    </x-admin.sidebar-item>

                    <x-admin.sidebar-item
                        :href="route('admin.cms.free-test-results.index')"
                        :active="request()->routeIs('admin.cms.free-test-results.*')"
                        title="Free Test Results">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </x-slot:icon>
                        Free Test Results
                    </x-admin.sidebar-item>
                </nav>
            </div>

        </div>
    </div>
</aside>