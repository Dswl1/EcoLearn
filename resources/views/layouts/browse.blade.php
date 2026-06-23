<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'EcoLearn'))</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600&amp;family=JetBrains+Mono:wght@500&amp;family=Space+Grotesk:wght@600;700&amp;display=swap"
        rel="stylesheet">

    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-body-md text-on-surface antialiased min-h-screen bg-background">

    <nav class="bg-surface/60 backdrop-blur-xl border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between h-16 items-center">
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary-container" style="font-variation-settings: 'FILL' 1;">public</span>
                <span class="font-display-lg text-primary-container text-xl">EcoLearn</span>
            </a>
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}"
                    class="text-sm text-on-surface-variant hover:text-primary font-medium transition-colors">
                    {{ __('app.login') }}
                </a>
                <a href="{{ route('register') }}"
                    class="text-sm bg-primary-container text-on-primary-container px-4 py-2 rounded-full hover:bg-primary transition-colors">
                    {{ __('app.get_started') }}
                </a>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </main>

    @stack('scripts')

</body>

</html>
