<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? (($pageTitle ?? 'Admin Panel') . ' - Queens English Prestige') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    x-data="adminShell()"
    x-init="initShell()"
    @keydown.escape.window="closeMobile()"
    class="min-h-screen bg-slate-50 text-slate-900 antialiased font-sans flex flex-col">

    <div class="relative min-h-screen flex flex-col">
        {{-- Mobile Off-Canvas Backdrop Overlay --}}
        <div
            x-show="mobileOpen"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="closeMobile()"
            class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-xs lg:hidden">
        </div>

        {{-- Global Admin Collapsible & Off-Canvas Sidebar --}}
        @include('partials.admin.sidebar')

        {{-- Main Page Shell Wrapper --}}
        <div
            class="flex flex-1 flex-col min-w-0 transition-all duration-300 ease-in-out"
            :class="desktopCollapsed ? 'lg:ml-[80px]' : 'lg:ml-[272px]'">

            {{-- Sticky Topbar Header --}}
            @include('partials.admin.topbar', [
                'pageTitle' => $pageTitle ?? 'Dashboard',
                'pageSubtitle' => $pageSubtitle ?? 'Admin Panel',
            ])

            {{-- Main Page Content --}}
            <main class="flex-1 p-4 sm:p-6 lg:p-8 bg-slate-50">
                @yield('content')
            </main>

            {{-- Admin Footer --}}
            @include('partials.admin.footer')
        </div>
    </div>

    <script>
        function adminShell() {
            return {
                desktopCollapsed: false,
                mobileOpen: false,
                initShell() {
                    try {
                        this.desktopCollapsed = localStorage.getItem('admin.sidebar.collapsed') === 'true';
                    } catch (e) {
                        this.desktopCollapsed = false;
                    }
                    this.$watch('desktopCollapsed', (val) => {
                        try {
                            localStorage.setItem('admin.sidebar.collapsed', val ? 'true' : 'false');
                        } catch (e) {}
                    });
                    this.$watch('mobileOpen', (val) => {
                        if (val) {
                            document.body.classList.add('overflow-hidden');
                        } else {
                            document.body.classList.remove('overflow-hidden');
                        }
                    });
                },
                toggleDesktop() {
                    this.desktopCollapsed = !this.desktopCollapsed;
                },
                toggleMobile() {
                    this.mobileOpen = !this.mobileOpen;
                },
                closeMobile() {
                    this.mobileOpen = false;
                }
            }
        }
    </script>

    @stack('scripts')
</body>

</html>