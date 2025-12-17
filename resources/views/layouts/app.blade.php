<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarOpen: false, darkMode: false }" :class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'HR Management System'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine Plugins -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>

    @stack('styles')
</head>

<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900">
    <div class="flex h-screen">
        <!-- Sidebar -->
        @include('layouts.includes.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            @include('layouts.includes.header')

            <!-- Mobile Menu -->
            @include('layouts.includes.mobile-menu')

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-4 md:p-6">
                <!-- Page Heading -->
                @if (isset($header))
                    <div class="mb-6">
                        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                            {{ $header }}
                        </h1>
                        @if (isset($subheader))
                            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $subheader }}</p>
                        @endif
                    </div>
                @endif

                <!-- Page Content -->
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Scripts -->
    @stack('scripts')
    <script>
        // Initialize Alpine
        document.addEventListener('alpine:init', () => {
            Alpine.store('sidebar', {
                open: false
            });

            // Load dark mode preference
            if (localStorage.getItem('darkMode') === 'true') {
                document.documentElement.classList.add('dark');
                Alpine.$data.darkMode = true;
            }
        });
    </script>
</body>

</html>
