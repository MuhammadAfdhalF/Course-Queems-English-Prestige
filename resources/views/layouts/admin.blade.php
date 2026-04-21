<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Panel - Queens English Prestige' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <div class="flex min-h-screen">
        @include('partials.admin.sidebar')

        <div class="flex-1">
            @include('partials.admin.topbar')

            <main class="p-4 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>