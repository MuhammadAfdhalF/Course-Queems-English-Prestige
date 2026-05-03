free-test-categories/index.blade.php<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Student Area - Queens English Prestige' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-900">
    @include('partials.student.navbar')

    <main class="px-4 py-6 lg:px-8">
        @yield('content')
    </main>

    @include('partials.student.footer')
</body>

</html>