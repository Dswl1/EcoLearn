@extends('app')

@section('title')
    {{ __('app.explorer') }} | {{ __('app.app_name') }}
@endsection

@section('content')
    {{-- Hero Section --}}
    <section class="relative mb-section-gap py-12 flex flex-col items-center text-center overflow-hidden">
        <div class="absolute inset-0 z-[-1] hologram-effect opacity-30"></div>
        <div class="inline-block px-4 py-1 rounded-full border border-primary/30 bg-primary/5 text-primary-fixed-dim font-label-sm text-label-sm mb-6 animate-pulse">
            {{ __('app.now_live') }}
        </div>
        <h1 class="font-display-lg text-display-lg max-w-4xl mb-6 bg-gradient-to-r from-primary via-white to-primary-fixed-dim bg-clip-text text-transparent">
            {{ __('app.welcome_future_sdg') }}
        </h1>
        <p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl mb-10">
            {{ __('app.hero_subtitle') }}
        </p>
        <div class="flex gap-4 sm:gap-6 flex-wrap justify-center">
            <a href="{{ route('register') }}"
                class="px-8 py-3 rounded-full font-label-sm text-label-sm bg-primary-container text-on-primary-container neon-glow font-bold hover:scale-105 transition-transform">
                {{ __('app.get_started') }}
            </a>
            <a href="{{ route('materials.index') }}"
                class="px-8 py-3 rounded-full font-label-sm text-label-sm border border-white/20 bg-white/5 backdrop-blur hover:bg-white/10 transition-all">
                {{ __('app.browse_materials') }}
            </a>
        </div>
    </section>

    {{-- SDG Category Preview (Bento Grid) --}}
    <section class="mb-section-gap">
        <div class="flex items-end justify-between mb-8">
            <div>
                <h2 class="font-display-lg-mobile text-display-lg-mobile text-primary mb-2">{{ __('app.global_objectives') }}</h2>
                <p class="text-on-surface-variant">{{ __('app.goals_subtitle') }}</p>
            </div>
            <div class="font-label-sm text-label-sm text-primary-container flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-primary-container"></span>
                {{ __('app.active_channels') }}
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @php $count = 0; @endphp
            @foreach (config('sdg.categories') as $catKey => $category)
                @foreach ($category['sdgs'] as $num)
                    @php
                        $sdg = config("sdg.all.{$num}");
                        $catColor = $category['color'];
                        $count++;
                    @endphp
                    <div class="glass-card p-4 sm:p-6 rounded-2xl flex flex-col items-center justify-center text-center group cursor-pointer hover:border-{{ $catKey === 'sosial' ? 'error' : ($catKey === 'lingkungan' ? 'primary' : ($catKey === 'ekonomi_inovasi' ? 'secondary' : 'primary-fixed-dim')) }}/40 transition-all duration-300">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform"
                            style="background-color: {{ $catColor }}15; border: 1px solid {{ $catColor }}40; color: {{ $catColor }}">
                            <span class="material-symbols-outlined">{{ $sdg['icon'] }}</span>
                        </div>
                        <div class="font-label-sm text-[10px] text-on-surface-variant">{{ __('app.sdg') }} {{ $num }}</div>
                        <div class="font-label-sm text-label-sm leading-tight mt-0.5">{{ __("app.sdg_{$num}") }}</div>
                    </div>
                @endforeach
            @endforeach
        </div>
    </section>

    {{-- Limited Material Preview --}}
    <section class="mb-section-gap">
        <div class="flex items-end justify-between mb-8">
            <div>
                <h2 class="font-display-lg-mobile text-display-lg-mobile text-primary mb-2">{{ __('app.curated_paths') }}</h2>
                <p class="text-on-surface-variant">{{ __('app.curated_paths_desc') }}</p>
            </div>
            <a href="{{ route('materials.index') }}" class="font-label-sm text-label-sm text-primary-container flex items-center gap-1 hover:underline">
                {{ __('app.view_all') }}
                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </a>
        </div>

        <div class="grid md:grid-cols-3 gap-gutter">
            @forelse ($materials as $material)
                <div class="glass-card rounded-3xl overflow-hidden group relative">
                    <div class="h-48 relative">
                        @if ($material->thumbnail)
                            <img class="w-full h-full object-cover" src="{{ Storage::url($material->thumbnail) }}" alt="{{ $material->title }}" />
                        @else
                            <div class="w-full h-full bg-surface-container-high flex items-center justify-center">
                                <span class="material-symbols-outlined text-5xl text-on-surface-variant/30">image</span>
                            </div>
                        @endif
                        <div class="absolute top-4 right-4 bg-surface/80 backdrop-blur px-3 py-1 rounded-full font-label-sm text-[10px] flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">lock</span>
                            {{ __('app.login_to_view') }}
                        </div>
                        @if ($material->sdg_category)
                            <div class="absolute bottom-4 left-4 bg-surface/80 backdrop-blur px-3 py-1 rounded-full font-label-sm text-[10px]">
                                {{ $material->sdg_category }}
                            </div>
                        @endif
                    </div>
                    <div class="p-6">
                        @if ($material->difficulty)
                            <div class="text-primary-fixed-dim font-label-sm text-[10px] mb-2 uppercase">{{ __("app.{$material->difficulty}") }}</div>
                        @endif
                        <h4 class="font-headline-md text-headline-md mb-3">{{ $material->title }}</h4>
                        <p class="text-on-surface-variant font-body-md text-sm mb-4 line-clamp-2">{{ $material->description ?? __('app.no_description') }}</p>
                        <a href="{{ route('materials.show', $material) }}"
                            class="inline-flex items-center gap-1 text-primary-container font-label-sm text-xs hover:underline">
                            {{ __('app.guest_login_to_read') }}
                            <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 glass-card rounded-3xl p-12 text-center">
                    <span class="material-symbols-outlined text-5xl text-on-surface-variant/20 mb-4">inventory_2</span>
                    <p class="text-on-surface-variant">{{ __('app.no_published_materials') }}</p>
                </div>
            @endforelse
        </div>
    </section>

    {{-- Demo Quiz & Flashcard Section --}}
    <section class="mb-section-gap grid lg:grid-cols-2 gap-gutter">
        <div class="glass-card p-6 sm:p-8 rounded-3xl relative overflow-hidden">
            <div class="lock-overlay absolute inset-0 z-10 flex flex-col items-center justify-center text-center p-8 bg-surface/60 backdrop-blur-sm">
                <span class="material-symbols-outlined text-4xl text-primary mb-4 animate-bounce">lock</span>
                <h5 class="font-headline-md text-headline-md mb-2">{{ __('app.quiz_locked_title') }}</h5>
                <p class="text-on-surface-variant mb-6 text-sm max-w-sm">{{ __('app.quiz_locked_desc') }}</p>
                <a href="{{ route('register') }}"
                    class="px-6 py-2 rounded-lg bg-primary-container text-on-primary-container font-label-sm hover:brightness-110 transition-all">
                    {{ __('app.get_started') }}
                </a>
            </div>
            <div class="opacity-30 blur-sm pointer-events-none select-none">
                <div class="font-label-sm text-primary mb-4">{{ __('app.question_of') }} 1/15</div>
                <h4 class="font-headline-md text-headline-md mb-6">{{ __('app.sample_quiz_question') }}</h4>
                <div class="space-y-3">
                    <div class="p-4 rounded-xl border border-white/10 bg-white/5">{{ __('app.sample_answer_a') }}</div>
                    <div class="p-4 rounded-xl border border-primary/40 bg-primary/10">{{ __('app.sample_answer_b') }}</div>
                    <div class="p-4 rounded-xl border border-white/10 bg-white/5">{{ __('app.sample_answer_c') }}</div>
                </div>
            </div>
        </div>
        <div class="glass-card p-6 sm:p-8 rounded-3xl relative overflow-hidden">
            <div class="lock-overlay absolute inset-0 z-10 flex flex-col items-center justify-center text-center p-8 bg-surface/60 backdrop-blur-sm">
                <span class="material-symbols-outlined text-4xl text-primary mb-4 animate-bounce">lock</span>
                <h5 class="font-headline-md text-headline-md mb-2">{{ __('app.flashcard_locked_title') }}</h5>
                <p class="text-on-surface-variant mb-6 text-sm max-w-sm">{{ __('app.flashcard_locked_desc') }}</p>
                <a href="{{ route('login') }}"
                    class="px-6 py-2 rounded-lg bg-primary/10 border border-primary/30 text-primary font-label-sm hover:bg-primary/20 transition-all">
                    {{ __('app.login') }}
                </a>
            </div>
            <div class="opacity-30 blur-sm pointer-events-none select-none flex flex-col items-center justify-center h-full min-h-[280px]">
                <div class="w-56 sm:w-64 glass-card rounded-2xl flex flex-col items-center justify-center p-8 transform rotate-3">
                    <span class="material-symbols-outlined text-5xl mb-4 text-primary-fixed-dim">lightbulb</span>
                    <div class="text-center font-headline-md text-sm">{{ __('app.sample_flashcard_question') }}</div>
                    <div class="mt-6 font-label-sm text-on-surface-variant text-xs">{{ __('app.tap_to_flip') }}</div>
                </div>
            </div>
        </div>
    </section>

    {{-- AI Assistant Preview --}}
    {{-- <section class="glass-card p-8 sm:p-12 rounded-[40px] relative overflow-hidden border-primary/30 bg-primary/5">
        <div class="absolute right-0 top-0 w-1/2 h-full hologram-effect opacity-40 hidden lg:block">
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-56 sm:w-64 h-56 sm:h-64 rounded-full border-2 border-primary/40 border-dashed animate-[spin_20s_linear_infinite]"></div>
                <div class="absolute w-40 sm:w-48 h-40 sm:h-48 rounded-full border-4 border-primary/20 border-dotted animate-[spin_10s_linear_infinite_reverse]"></div>
                <div class="absolute w-28 sm:w-32 h-28 sm:h-32 bg-primary/20 rounded-full blur-3xl animate-pulse"></div>
                <span class="material-symbols-outlined text-5xl sm:text-6xl text-primary-fixed-dim" style="font-variation-settings: 'FILL' 1;">fluid</span>
            </div>
        </div>
        <div class="lg:w-1/2 relative z-20">
            <div class="font-label-sm text-primary mb-4">{{ __('app.neural_core') }}</div>
            <h2 class="font-display-lg text-3xl sm:text-4xl md:text-5xl mb-6">{{ __('app.ai_guided_title') }}</h2>
            <ul class="space-y-6 mb-10">
                <li class="flex gap-4">
                    <span class="material-symbols-outlined text-primary">auto_awesome</span>
                    <div>
                        <div class="font-headline-md text-sm mb-1">{{ __('app.ai_feature_personalized') }}</div>
                        <p class="text-on-surface-variant text-sm">{{ __('app.ai_feature_personalized_desc') }}</p>
                    </div>
                </li>
                <li class="flex gap-4">
                    <span class="material-symbols-outlined text-primary">query_stats</span>
                    <div>
                        <div class="font-headline-md text-sm mb-1">{{ __('app.ai_feature_realtime') }}</div>
                        <p class="text-on-surface-variant text-sm">{{ __('app.ai_feature_realtime_desc') }}</p>
                    </div>
                </li>
                <li class="flex gap-4">
                    <span class="material-symbols-outlined text-primary">forum</span>
                    <div>
                        <div class="font-headline-md text-sm mb-1">{{ __('app.ai_feature_support') }}</div>
                        <p class="text-on-surface-variant text-sm">{{ __('app.ai_feature_support_desc') }}</p>
                    </div>
                </li>
            </ul>
            <a href="{{ route('register') }}"
                class="inline-flex items-center gap-3 px-10 py-4 rounded-full bg-primary text-on-primary font-bold neon-glow hover:scale-105 transition-transform">
                {{ __('app.connect_neural') }}
                <span class="material-symbols-outlined">arrow_forward</span>
            </a>
        </div>
    </section> --}}
@endsection
