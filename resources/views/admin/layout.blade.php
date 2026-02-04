<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - {{ config('app.name') }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @endif
</head>
<body class="min-h-screen bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <div class="flex">
        <aside class="w-64 p-4 border-r">
            <div class="flex items-center justify-between">
                <h2 class="font-medium mb-4">Admin</h2>
            </div>
            <nav class="flex flex-col gap-2 text-sm">
                <a href="{{ route('admin.dashboard') }}" class="underline">Dashboard</a>
                <a href="{{ route('admin.users') }}" class="underline">Users</a>
            </nav>
            @auth
                <div class="mt-6 text-sm">
                    <div>Signed in as <strong>{{ auth()->user()->name }}</strong></div>
                    <form method="POST" action="{{ route('logout') }}" class="mt-2">
                        @csrf
                        <button type="submit" class="text-red-600">Logout</button>
                    </form>
                </div>
            @endauth
        </aside>
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>
    @include('partials.toast')
    </body>
</html>
