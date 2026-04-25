<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Auth - Queens English Prestige' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f6f6f4] text-slate-900">
    <div class="flex min-h-screen flex-col">
        <main class="flex-1">
            @yield('content')
        </main>

        <footer class="pb-6">
            <div class="mx-auto max-w-7xl px-4 text-center">
                <p class="text-xs font-medium text-slate-400">
                    © 2003 Queens English Prestige. All rights reserved.
                </p>
            </div>
        </footer>
    </div>
</body>
</html>