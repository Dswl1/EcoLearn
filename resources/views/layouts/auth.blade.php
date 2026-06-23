<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>@yield('title')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600&amp;family=JetBrains+Mono:wght@500&amp;family=Space+Grotesk:wght@600;700&amp;display=swap"
        rel="stylesheet">

    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Design System Theme Config -->
</head>

<body class="font-body-md text-on-surface overflow-y-auto overflow-x-hidden">

    <!-- Background -->
    <div class="fixed inset-0 z-0">

        <div class="grid-background absolute inset-0"></div>

        <div class="scanline"></div>

        <div class="absolute inset-0" id="particle-container"></div>

        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-primary-container/10 rounded-full blur-[120px]">
        </div>

        <div
            class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-secondary-container/10 rounded-full blur-[120px]">
        </div>

    </div>

    <!-- Main -->
    <main class="relative z-10 flex min-h-screen items-center justify-center py-10 px-container-padding-mobile">

            @yield('auth')


    </main>

    @include('partials.flash-data')

</body>

</html>
