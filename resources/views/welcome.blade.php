<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>{{ __('app.app_name') }} - {{ __('app.master_future_plain') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600&family=JetBrains+Mono:wght@500&family=Space+Grotesk:wght@600;700&display=swap"
        rel="stylesheet">

    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
</head>

<body class="bg-background text-on-background antialiased min-h-screen flex flex-col overflow-x-hidden relative"
    x-data="{ mobileMenuOpen: false }">

    <div class="fixed inset-0 z-0 pointer-events-none opacity-30"
        style="background-image: radial-gradient(circle at 15% 50%, rgba(0, 242, 255, 0.15) 0%, transparent 50%), radial-gradient(circle at 85% 30%, rgba(2, 49, 222, 0.15) 0%, transparent 50%);">
    </div>

    <div class="flex justify-center">

        <nav
        class="fixed top-5 w-[80%] neon-shadow rounded-full z-50 flex justify-between items-center py-4 bg-surface/60 backdrop-blur-xl border-b border-white/10 shadow-[0_0_10px_rgba(0,219,231,0.1)] md:px-container-padding-desktop px-container-padding-mobile">
        <a href="{{ url('/') }}" class="flex items-center gap-2">
            <span class="font-display-lg text-primary-container neon-text md:text-4xl text-xl">{{ __('app.app_name') }}</span>
        </a>
        
        {{-- <div class="hidden md:flex items-center gap-8 font-body-md text-body-md">
            <a class="text-primary border-b-2 border-primary-container pb-1 hover:bg-primary/10 transition-all"
                href="#">Platform</a>
                <a class="text-on-surface-variant hover:text-primary hover:bg-primary/10 transition-all"
                href="#">Insights</a>
                <a class="text-on-surface-variant hover:text-primary hover:bg-primary/10 transition-all"
                href="#">Developer</a>
            </div> --}}
            
            <div class="flex items-center gap-4">
                @if (Route::has('login'))
                @auth
                <a href="{{ route('dashboard') }}"
                class="flex items-center gap-2 text-on-surface-variant hover:text-primary transition-colors">
                        <span class="material-symbols-outlined">person</span>
                        <span class="hidden md:inline font-label-sm">{{ Auth::user()->name ?? __('app.guest') }}</span>
                    </a>
                @else
                    <a href="{{ route('login') }}"
                    class="hidden md:inline-flex text-on-surface-variant hover:text-primary font-label-sm transition-colors">
                        {{ __('app.login') }}
                    </a>
                    <a href="{{ route('register') }}"
                    class="bg-primary-container hidden md:inline-flex text-on-primary-container font-label-sm px-6 py-2 rounded-full hover:bg-primary transition-colors shadow-[0_0_8px_rgba(0,242,255,0.2)]">
                    {{ __('app.get_started') }}
                </a>
                @endauth
                @endif
                
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                class="md:hidden flex items-center justify-center w-10 h-10 text-on-surface hover:text-primary transition-colors"
                aria-label="Toggle navigation menu">
                <span x-show="!mobileMenuOpen" class="material-symbols-outlined">menu</span>
                <span x-show="mobileMenuOpen" class="material-symbols-outlined" style="display: none;">close</span>
            </button>
        </div>
    </nav>
</div>

    <div x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false" x-cloak
        class="md:hidden fixed top-16 inset-x-0 z-40 bg-surface/95 backdrop-blur-xl border-b border-white/10 px-container-padding-mobile py-6 flex flex-col gap-4 shadow-xl">
       
        <a href="{{ route('login') }}" @click="mobileMenuOpen = false"
            class="text-on-surface-variant hover:text-primary font-body-md">{{ __('app.login') }}</a>
    </div>

    <main class="flex-grow z-10">
        <section
            class="min-h-screen flex flex-col md:flex-row items-center justify-center px-container-padding-mobile gap-6 md:gap-gutter py-section-gap relative pt-28 md:pt-24">
            <div class="flex flex-col items-center md:items-start gap-6 max-w-2xl z-20 order-2 md:order-1">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full glass-card border-primary/30 text-primary font-label-sm">
                    <span class="material-symbols-outlined text-sm"
                        style="font-variation-settings: 'FILL' 1;">public</span>
                    <span>{{ __('app.global_intelligence_network') }}</span>
                </div>

                <h1 class="font-display-lg text-display-lg leading-tight text-center md:text-left">
                    {!! __('app.master_future') !!}
                </h1>

                <p class="font-body-lg text-body-lg text-on-surface-variant max-w-xl text-center md:text-left">
                    {{ __('app.hero_subtitle') }}
                </p>

                @if (Route::has('login'))
                    @auth
                        <div class="flex flex-col sm:flex-row gap-4 mt-2 w-full sm:w-auto">
                            <a href="{{ route('dashboard') }}"
                                class="bg-primary-container text-on-primary-container font-label-sm px-8 py-4 rounded-lg shadow-[0_0_12px_rgba(0,242,255,0.3)] hover:bg-primary transition-all flex items-center justify-center gap-2">
                                {{ __('app.start_learning_auth') }}
                                <span class="material-symbols-outlined">arrow_forward</span>
                            </a>
                        </div>
                    @else
                        <div class="flex flex-col sm:flex-row gap-4 mt-2 w-full sm:w-auto">
                            <a href="{{ route('demo') }}"
                                class="bg-primary-container text-on-primary-container font-label-sm px-8 py-4 rounded-lg shadow-[0_0_12px_rgba(0,242,255,0.3)] hover:bg-primary transition-all flex items-center justify-center gap-2">
                                {{ __('app.start_learning_free') }}
                                <span class="material-symbols-outlined">arrow_forward</span>
                            </a>
                            <a href="#"
                                class="glass-card text-primary font-label-sm px-8 py-4 rounded-lg hover:border-primary-container transition-all flex items-center justify-center gap-2">
                                {{ __('app.explore_platform') }}
                            </a>
                        </div>
                    @endauth
                @endif
            </div>

            <div class="flex-1 relative w-full max-w-[280px] sm:max-w-sm md:max-w-md aspect-square z-10 order-1 md:order-2">
                <div class="absolute inset-0 rounded-full border border-primary/20 animate-[spin_60s_linear_infinite]">
                </div>
                <div
                    class="absolute inset-4 rounded-full border border-secondary-container/30 animate-[spin_40s_linear_infinite_reverse]">
                </div>
                <img alt="Glowing stylized globe network visualization showing global AI data flow and climate intelligence connections"
                    class="w-full h-full object-cover rounded-full mix-blend-screen opacity-90 neon-shadow p-8 animate-[spin_80s_linear_infinite]"
                    loading="lazy"
                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuDWsqN4397ikY0SDqrbtkSHXwbgs95hsEbjpAPjPwZZF9GGLiab_AWlMTIs3NdVteWF6vg1wktcLsRsVnJhRzc9NVyP1-QYgi4s-W4Cf0fePZzbwyIvwKbBF0MqIv9mbD3P_4jUg9pg_i7EGWloF1ceqwHJYeaX9YELpCTTUSkHej2XQeuDN6rcELqva6ELBDbB2RXEujMwJSsYUlK2dOALGlFbIti0idE4HGcmKD6anivrkTjW6nAV6ojyufBS7Z6IZae4JinXPbw">
            </div>
        </section>

        <section class="py-section-gap px-container-padding-mobile" x-data="sdgSlider()">
            <div class="mb-8 md:mb-12">
                <h2 class="font-headline-md text-headline-md text-on-surface mb-2">{{ __('app.the_17_goals') }}</h2>
                <p class="font-body-md text-body-md text-on-surface-variant">{{ __('app.goals_subtitle') }}</p>
            </div>

            {{-- Category Tabs --}}
            <div class="flex flex-wrap gap-2 md:gap-3 mb-8" role="tablist">
                @foreach (config('sdg.categories') as $catKey => $category)
                    <button @click="scrollToCategory('{{ $catKey }}')"
                        :class="activeCategory === '{{ $catKey }}' ? 'ring-2 ring-offset-2 ring-offset-surface' : ''"
                        class="relative px-4 md:px-5 py-2 md:py-2.5 rounded-full font-label-sm text-sm transition-all duration-300 flex items-center gap-2"
                        style="background: {{ $category['color'] }}15; color: {{ $category['color'] }}; border: 1px solid {{ $category['color'] }}30;"
                        :style="activeCategory === '{{ $catKey }}' ? 'background: {{ $category['color'] }}; color: #fff; border-color: {{ $category['color'] }}; box-shadow: 0 0 20px {{ $category['color'] }}40;' : ''"
                        role="tab">
                        <span class="w-2 h-2 rounded-full" style="background: {{ $category['color'] }}"></span>
                        {{ __("app.category_{$catKey}") }}
                    </button>
                @endforeach
            </div>

            {{-- Slider Container --}}
            <div class="relative group/slider">
                {{-- Left Arrow --}}
                <button @click="scrollSlider(-1)"
                    class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-3 z-20 w-10 h-10 rounded-full glass-card flex items-center justify-center text-on-surface-variant hover:text-primary hover:border-primary-container transition-all opacity-0 group-hover/slider:opacity-100"
                    aria-label="Scroll left">
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>

                {{-- Right Arrow --}}
                <button @click="scrollSlider(1)"
                    class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-3 z-20 w-10 h-10 rounded-full glass-card flex items-center justify-center text-on-surface-variant hover:text-primary hover:border-primary-container transition-all opacity-0 group-hover/slider:opacity-100"
                    aria-label="Scroll right">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>

                {{-- Slides --}}
                <div x-ref="slider"
                    class="flex gap-4 md:gap-6 overflow-x-auto pb-4 snap-x snap-mandatory scrollbar-hide -mx-container-padding-mobile px-container-padding-mobile scroll-smooth"
                    style="scrollbar-width: none; -ms-overflow-style: none;"
                    @scroll.throttle="onScroll()">
                    @php $catIndex = 0; @endphp
                    @foreach (config('sdg.categories') as $catKey => $category)
                        @php
                            $catColors = [$category['color'], $category['color_dim']];
                            $slidesPerView = count($category['sdgs']);
                        @endphp
                        <div x-ref="category-{{ $catKey }}" data-category="{{ $catKey }}"
                            class="flex gap-4 md:gap-6 snap-start flex-shrink-0 py-2">
                            @foreach ($category['sdgs'] as $num)
                                @php
                                    $sdg = config("sdg.all.{$num}");
                                    $numPad = str_pad($num, 2, '0', STR_PAD_LEFT);
                                @endphp
                                <div class="lazy-card w-[260px] md:w-[280px] glass-card rounded-xl p-6 hover:shadow-[0_0_25px_rgba(0,242,255,0.15)] transition-all duration-500 relative overflow-hidden flex-shrink-0 opacity-0 translate-y-4"
                                    style="border-color: {{ $category['color'] }}20; transition-delay: {{ ($num % 5) * 80 }}ms;">
                                    {{-- Category color accent bar --}}
                                    <div class="absolute top-0 left-0 w-full h-1" style="background: {{ $category['color'] }}"></div>
                                    {{-- Category color glow --}}
                                    <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full opacity-10 blur-2xl" style="background: {{ $category['color'] }}"></div>
                                    <div class="flex justify-between items-start mb-6 md:mb-8 relative">
                                        <span class="font-display-lg text-3xl md:text-4xl font-bold" style="color: {{ $category['color'] }}60">{{ $numPad }}</span>
                                        <span class="material-symbols-outlined text-3xl md:text-4xl"
                                            style="color: {{ $category['color'] }}; font-variation-settings: 'FILL' 1;">{{ $sdg['icon'] }}</span>
                                    </div>
                                    <h4 class="font-headline-md text-headline-md mb-2 text-lg md:text-xl relative">{{ __("app.{$sdg['key']}") }}</h4>
                                    <p class="font-body-md text-body-md text-on-surface-variant text-sm leading-relaxed relative">{{ __("app.{$sdg['key']}_desc") }}</p>
                                    {{-- Category badge --}}
                                    <div class="mt-4 pt-3 border-t border-white/5">
                                        <span class="text-[10px] font-label-sm px-2 py-0.5 rounded-full uppercase tracking-wider"
                                            style="background: {{ $category['color'] }}15; color: {{ $category['color'] }}">
                                            {{ __("app.category_{$catKey}") }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @php $catIndex++; @endphp
                    @endforeach
                </div>
            </div>

            {{-- Dot Indicators --}}
            <div class="flex justify-center gap-2 mt-6">
                @foreach (config('sdg.categories') as $catKey => $category)
                    <button @click="scrollToCategory('{{ $catKey }}')"
                        :class="activeCategory === '{{ $catKey }}' ? 'w-8' : 'w-2'"
                        class="h-2 rounded-full transition-all duration-300"
                        style="background: {{ $category['color'] }}"
                        :style="activeCategory === '{{ $catKey }}' ? 'background: {{ $category['color'] }}; width: 2rem;' : 'background: {{ $category['color'] }}40; width: 0.5rem;'"
                        :aria-label="'{{ __("app.category_{$catKey}") }}'">
                    </button>
                @endforeach
            </div>
        </section>

        <section class="py-section-gap px-container-padding-mobile" x-data="intelligenceLayer()">
            <div class="text-center mb-12 md:mb-16 max-w-3xl mx-auto">
                <span
                    class="font-label-sm text-label-sm text-primary uppercase tracking-widest mb-2 block">{{ __('app.intelligence_layer') }}</span>
                <h2 class="font-display-lg text-3xl md:text-5xl mb-4">{{ __('app.meet_visionary_ai') }}</h2>
                <p class="font-body-lg text-body-lg text-on-surface-variant">{{ __('app.ai_subtitle') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 max-w-7xl mx-auto auto-rows-auto md:auto-rows-[280px]">
                {{-- SDG Impact Dashboard --}}
                <div
                    class="md:col-span-2 glass-card rounded-xl p-6 md:p-8 flex flex-col overflow-hidden border-primary/20 min-h-[300px] md:min-h-0">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-headline-md text-headline-md text-primary flex items-center gap-2 text-lg md:text-2xl">
                            <span class="material-symbols-outlined">monitoring</span>
                            {{ __('app.sdg_impact_overview') }}
                        </h3>
                        <span class="font-label-sm text-label-sm text-on-surface-variant hidden md:inline">{{ __('app.engagement_by_category') }}</span>
                    </div>
                    <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-center justify-center">
                            <canvas id="sdgDoughnut" width="220" height="220"></canvas>
                        </div>
                        <div class="flex flex-col justify-center gap-3 md:gap-4">
                            @foreach(config('sdg.categories') as $catKey => $cat)
                            @php
                                $pct = match($catKey) {
                                    'sosial' => 35,
                                    'lingkungan' => 40,
                                    'ekonomi_inovasi' => 15,
                                    'tata_kelola' => 10,
                                };
                            @endphp
                            <div class="flex items-center gap-3">
                                <span class="w-3 h-3 rounded-full flex-shrink-0" style="background: {{ $cat['color'] }}; box-shadow: 0 0 6px {{ $cat['color'] }}80;"></span>
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="font-label-sm text-label-sm text-on-surface truncate">{{ __("app.category_{$catKey}") }}</span>
                                        <span class="font-label-sm text-label-sm text-on-surface-variant ml-2">{{ $pct }}%</span>
                                    </div>
                                    <div class="h-2 rounded-full bg-surface-container-high overflow-hidden">
                                        <div class="h-full rounded-full transition-all duration-1000 ease-out"
                                            style="width: 0%; background: {{ $cat['color'] }}; box-shadow: 0 0 8px {{ $cat['color'] }}80;"
                                            :style="`width: ${categoryWidths['{{ $catKey }}']}%; background: {{ $cat['color'] }}; box-shadow: 0 0 8px {{ $cat['color'] }}80;`">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Quick Stats --}}
                <div class="flex flex-col gap-4 md:gap-6 min-h-0">
                    <div class="glass-card rounded-xl p-6 flex flex-col items-center justify-center text-center flex-1">
                        <div class="relative w-20 h-20 md:w-24 md:h-24 mb-3">
                            <canvas id="accuracyRing" width="96" height="96"></canvas>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="font-display-lg text-lg md:text-xl text-primary" x-ref="accuracyLabel">0%</span>
                            </div>
                        </div>
                        <span class="font-label-sm text-label-sm text-on-surface-variant">{{ __('app.prediction_accuracy') }}</span>
                    </div>

                    <div class="glass-card rounded-xl p-6 flex flex-col justify-center flex-1">
                        <h4 class="font-label-sm text-label-sm text-on-surface-variant mb-3">{{ __('app.global_adoption_rate') }}</h4>
                        <div class="flex items-end gap-2 flex-1">
                            <div class="flex-1 flex flex-col items-center gap-1 justify-end">
                                <div class="w-full bg-secondary-container rounded-t transition-all duration-500" style="height: 20%"></div>
                                <span class="text-[10px] text-on-surface-variant/60">Q1</span>
                            </div>
                            <div class="flex-1 flex flex-col items-center gap-1 justify-end">
                                <div class="w-full bg-secondary-container rounded-t transition-all duration-500" style="height: 35%"></div>
                                <span class="text-[10px] text-on-surface-variant/60">Q2</span>
                            </div>
                            <div class="flex-1 flex flex-col items-center gap-1 justify-end">
                                <div class="w-full bg-secondary-container rounded-t transition-all duration-500" style="height: 55%"></div>
                                <span class="text-[10px] text-on-surface-variant/60">Q3</span>
                            </div>
                            <div class="flex-1 flex flex-col items-center gap-1 justify-end">
                                <div class="w-full bg-primary rounded-t transition-all duration-500 shadow-[0_0_6px_rgba(0,242,255,0.2)]" style="height: 75%"></div>
                                <span class="text-[10px] text-on-surface-variant/60">Q4</span>
                            </div>
                            <div class="flex-1 flex flex-col items-center gap-1 justify-end">
                                <div class="w-full bg-primary-container rounded-t transition-all duration-500 shadow-[0_0_8px_rgba(0,242,255,0.3)]" style="height: 90%"></div>
                                <span class="text-[10px] text-on-surface-variant/60">{{ __('app.active_learners') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- AI Live Synthesis --}}
                <div
                    class="md:col-span-3 glass-card rounded-xl p-6 md:p-8 flex flex-col md:flex-row gap-6 relative overflow-hidden border-primary/20 min-h-[200px]">
                    <div class="absolute top-0 right-0 p-6 opacity-5 hidden md:block pointer-events-none">
                        <span class="material-symbols-outlined text-9xl">neurology</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-headline-md text-headline-md text-primary mb-4 md:mb-6 flex items-center gap-2 text-lg md:text-2xl">
                            <span class="material-symbols-outlined">auto_awesome</span>
                            {{ __('app.live_synthesis') }}
                        </h3>
                        <div class="flex flex-col gap-4 z-10">
                            <div class="bg-surface-container-high p-3 md:p-4 rounded-lg max-w-[85%] md:max-w-[80%] border border-white/5">
                                <p class="font-body-md text-sm md:text-body-md text-on-surface-variant">{{ __('app.analyze_prompt') }}</p>
                            </div>
                            <div
                                class="bg-primary/10 p-3 md:p-4 rounded-lg max-w-[95%] md:max-w-[90%] self-end border border-primary/20 shadow-[0_0_10px_rgba(0,242,255,0.1)]">
                                <p class="font-body-md text-sm md:text-body-md text-primary-fixed mb-2 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm"
                                        style="font-variation-settings: 'FILL' 1;">robot_2</span> {{ __('app.meet_visionary_ai') }}
                                </p>
                                <p class="font-body-md text-sm md:text-body-md text-on-surface">
                                    {{ __('app.ai_response_demo') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-row md:flex-col items-center justify-center gap-3 min-w-[120px] border-t md:border-t-0 md:border-l border-white/10 pt-4 md:pt-0 md:pl-6">
                        <span class="flex items-center gap-2 text-primary font-label-sm">
                            <span class="relative flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-primary"></span>
                            </span>
                            Live
                        </span>
                        <span class="font-label-sm text-label-sm text-on-surface-variant text-center">{{ __('app.realtime_data_stream') }}</span>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer
        class="mt-auto border-t border-white/10 bg-surface/80 backdrop-blur-lg pt-12 md:pt-16 pb-8 px-container-padding-mobile z-20 relative">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-8 md:gap-12 mb-12 max-w-7xl mx-auto">
            {{-- Brand / Newsletter --}}
            <div class="sm:col-span-2 lg:col-span-5">
                <span class="font-display-lg text-primary-container text-xl md:text-2xl mb-4 block">{{ __('app.app_name') }}</span>
                <p class="font-body-md text-body-md text-on-surface-variant max-w-sm mb-6">
                    {{ __('app.empowering_description') }}
                </p>
                <div class="flex gap-2 max-w-sm">
                    <input type="email" placeholder="{{ __('app.subscribe_placeholder') }}"
                        class="flex-1 bg-surface-container-high border border-white/10 rounded-lg px-4 py-2.5 font-body-md text-body-md text-on-surface placeholder:text-on-surface-variant/40 focus:outline-none focus:border-primary transition-colors">
                    <button
                        class="neon-button rounded-lg px-5 py-2.5 text-surface font-label-sm whitespace-nowrap">
                        {{ __('app.subscribe_button') }}
                    </button>
                </div>
            </div>

            {{-- Platform Links --}}
            <div class="lg:col-span-2">
                <h4 class="font-label-sm text-label-sm text-on-surface mb-6 uppercase tracking-wider">{{ __('app.platform') }}</h4>
                <ul class="space-y-3 md:space-y-4 font-body-md text-body-md text-on-surface-variant">
                    <li><a class="hover:text-primary transition-colors" href="#">{{ __('app.dashboard') }}</a></li>
                    <li><a class="hover:text-primary transition-colors" href="#">{{ __('app.goals_explorer') }}</a></li>
                    <li><a class="hover:text-primary transition-colors" href="#">{{ __('app.meet_visionary_ai') }}</a></li>
                    <li><a class="hover:text-primary transition-colors" href="#">{{ __('app.materials') }}</a></li>
                </ul>
            </div>

            {{-- SDG Shortcuts --}}
            <div class="lg:col-span-3">
                <h4 class="font-label-sm text-label-sm text-on-surface mb-6 uppercase tracking-wider">{{ __('app.sdg_shortcuts') }}</h4>
                <ul class="space-y-3 md:space-y-4">
                    @foreach(config('sdg.categories') as $catKey => $cat)
                    <li>
                        <a class="flex items-center gap-3 font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors group" href="#">
                            <span class="w-2.5 h-2.5 rounded-full flex-shrink-0 transition-transform group-hover:scale-125"
                                style="background: {{ $cat['color'] }}; box-shadow: 0 0 6px {{ $cat['color'] }}80;"></span>
                            <span>{{ __("app.category_{$catKey}") }}</span>
                            <span class="font-label-sm text-label-sm text-on-surface-variant/40">(SDG {{ implode(', ', $cat['sdgs']) }})</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Connect --}}
            <div class="lg:col-span-2">
                <h4 class="font-label-sm text-label-sm text-on-surface mb-6 uppercase tracking-wider">{{ __('app.connect') }}</h4>
                <div class="flex gap-3 mb-6">
                    <a class="w-10 h-10 rounded-full glass-card flex items-center justify-center text-on-surface-variant hover:text-primary hover:border-primary transition-all"
                        href="#" aria-label="Contact us">
                        <span class="material-symbols-outlined">alternate_email</span>
                    </a>
                    <a class="w-10 h-10 rounded-full glass-card flex items-center justify-center text-on-surface-variant hover:text-primary hover:border-primary transition-all"
                        href="#" aria-label="Forum">
                        <span class="material-symbols-outlined">forum</span>
                    </a>
                    <a class="w-10 h-10 rounded-full glass-card flex items-center justify-center text-on-surface-variant hover:text-primary hover:border-primary transition-all"
                        href="#" aria-label="GitHub">
                        <span class="material-symbols-outlined">code</span>
                    </a>
                </div>
                <p class="font-label-sm text-label-sm text-on-surface-variant/60">contact@ecolearn.net</p>
            </div>
        </div>
        <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 max-w-7xl mx-auto">
            <p class="font-label-sm text-label-sm text-on-surface-variant/50">{!! __('app.copyright', ['year' => date('Y')]) !!}</p>
            <div class="flex gap-6 font-label-sm text-label-sm text-on-surface-variant/50">
                <a class="hover:text-on-surface transition-colors" href="#">{{ __('app.privacy') }}</a>
                <a class="hover:text-on-surface transition-colors" href="#">{{ __('app.terms') }}</a>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('sdgSlider', () => ({
                activeCategory: '{{ collect(config("sdg.categories"))->keys()->first() }}',
                categories: @json(collect(config('sdg.categories'))->keys()),

                init() {
                    this.$nextTick(() => {
                        this.setupLazyLoad();
                    });
                },

                setupLazyLoad() {
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                entry.target.classList.add('opacity-100', 'translate-y-0');
                                observer.unobserve(entry.target);
                            }
                        });
                    }, { threshold: 0.05, rootMargin: '100px' });

                    document.querySelectorAll('.lazy-card').forEach(card => {
                        observer.observe(card);
                    });
                },

                scrollToCategory(catKey) {
                    this.activeCategory = catKey;
                    const el = this.$refs['category-' + catKey];
                    if (el) {
                        el.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'start' });
                    }
                },

                scrollSlider(direction) {
                    const slider = this.$refs.slider;
                    const scrollAmount = slider.clientWidth * 0.8;
                    slider.scrollBy({ left: direction * scrollAmount, behavior: 'smooth' });
                },

                onScroll() {
                    const slider = this.$refs.slider;
                    let closestCat = this.categories[0];
                    let closestDist = Infinity;
                    this.categories.forEach(key => {
                        const el = this.$refs['category-' + key];
                        if (el) {
                            const rect = el.getBoundingClientRect();
                            const dist = Math.abs(rect.left);
                            if (dist < closestDist) {
                                closestDist = dist;
                                closestCat = key;
                            }
                        }
                    });
                    this.activeCategory = closestCat;
                },
            }));

            Alpine.data('intelligenceLayer', () => ({
                categoryWidths: {
                    sosial: 0,
                    lingkungan: 0,
                    ekonomi_inovasi: 0,
                    tata_kelola: 0,
                },

                init() {
                    this.$nextTick(() => {
                        this.animateBars();
                        this.initCharts();
                    });
                },

                animateBars() {
                    setTimeout(() => {
                        this.categoryWidths = {
                            sosial: 35,
                            lingkungan: 40,
                            ekonomi_inovasi: 15,
                            tata_kelola: 10,
                        };
                    }, 200);
                },

                initCharts() {
                    const catColors = @json(collect(config('sdg.categories'))->map(fn($c) => $c['color']));
                    const catLabels = [
                        '{{ __("app.category_sosial") }}',
                        '{{ __("app.category_lingkungan") }}',
                        '{{ __("app.category_ekonomi_inovasi") }}',
                        '{{ __("app.category_tata_kelola") }}',
                    ];
                    const catData = [35, 40, 15, 10];

                    const doughnutCtx = document.getElementById('sdgDoughnut');
                    if (doughnutCtx) {
                        new Chart(doughnutCtx, {
                            type: 'doughnut',
                            data: {
                                labels: catLabels,
                                datasets: [{
                                    data: catData,
                                    backgroundColor: catColors,
                                    borderColor: '#050816',
                                    borderWidth: 3,
                                    hoverOffset: 8,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: true,
                                cutout: '72%',
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        backgroundColor: 'rgba(11,17,32,0.9)',
                                        titleFont: { family: 'Geist' },
                                        bodyFont: { family: 'Geist' },
                                        borderColor: 'rgba(0,242,255,0.2)',
                                        borderWidth: 1,
                                        padding: 12,
                                        cornerRadius: 8,
                                    }
                                }
                            }
                        });
                    }

                    const ringCtx = document.getElementById('accuracyRing');
                    if (ringCtx) {
                        new Chart(ringCtx, {
                            type: 'doughnut',
                            data: {
                                datasets: [{
                                    data: [94, 6],
                                    backgroundColor: ['#00f2ff', 'rgba(255,255,255,0.05)'],
                                    borderWidth: 0,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: true,
                                cutout: '78%',
                                animation: {
                                    animateRotate: true,
                                    duration: 1500,
                                },
                                plugins: {
                                    legend: { display: false },
                                    tooltip: { enabled: false },
                                }
                            }
                        });

                        let count = 0;
                        const label = this.$refs.accuracyLabel;
                        const interval = setInterval(() => {
                            count++;
                            if (label) label.textContent = count + '%';
                            if (count >= 94) clearInterval(interval);
                        }, 16);
                    }
                },
            }));
        });
    </script>
</body>

</html>