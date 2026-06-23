<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard | EcoLearn')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600&amp;family=JetBrains+Mono:wght@500&amp;family=Space+Grotesk:wght@600;700&amp;display=swap"
        rel="stylesheet">

    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js" defer></script>
</head>

<body x-data="{
    sidebarOpen: window.innerWidth >= 768,
    get isDark() { return document.documentElement.classList.contains('dark') },
    toggleDark() {
        document.documentElement.classList.toggle('dark');
        localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
    },
}" class="bg-background  text-on-surface font-body-md antialiased min-h-screen flex">

    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-30 md:hidden">
    </div>

    @include('partials.sidebar')

    @include('partials.header')

    <main :class="sidebarOpen ? 'md:ml-64' : 'md:ml-0'"
        class="flex-1 ml-0 px-container-padding-mobile md:px-container-padding-desktop pb-container-padding-mobile md:pb-container-padding-desktop pt-[72px] max-w-7xl mx-auto space-y-gutter transition-all duration-300">
        <div class="py-5">
            @yield('content')
        </div>

    </main>

    @stack('scripts')

    @include('partials.flash-data')
</body>

</html>
