@extends('app')
@section('title')
    {{ __('app.dashboard') }} | {{ __('app.app_name') }}
@endsection

@section('content')
    <div class="space-y-8">
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="font-display-lg text-display-lg-mobile md:text-display-lg text-primary-fixed tracking-tight">
                    {{ __('app.dashboard') }}
                </h1>
                <p class="text-on-surface-variant text-sm mt-1">{{ __('app.welcome_back', ['name' => Auth::user()->name]) }}</p>
            </div>
        </header>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            <div class="glass-card-static rounded-2xl p-4 md:p-6">
                <div class="flex justify-between items-start mb-3">
                    <div class="p-2 bg-primary-container/10 rounded-lg">
                        <span class="material-symbols-outlined text-primary-container">group</span>
                    </div>
                    <span class="text-xs font-label-sm text-green-400 bg-green-400/10 px-2 py-1 rounded">
                        +{{ $activeUsers }} {{ __('app.this_month') }}
                    </span>
                </div>
                <p class="text-on-surface-variant font-label-sm text-[11px] uppercase tracking-wider">{{ __('app.total_users') }}</p>
                <h3 class="text-2xl md:text-3xl font-headline-md font-bold mt-1">{{ number_format($totalUsers) }}</h3>
            </div>

            <div class="glass-card-static rounded-2xl p-4 md:p-6">
                <div class="flex justify-between items-start mb-3">
                    <div class="p-2 bg-secondary/10 rounded-lg">
                        <span class="material-symbols-outlined text-secondary">inventory_2</span>
                    </div>
                    <span class="text-xs font-label-sm text-on-surface-variant bg-white/5 px-2 py-1 rounded">
                        {{ $publishedThisMonth }} {{ __('app.published') }}
                    </span>
                </div>
                <p class="text-on-surface-variant font-label-sm text-[11px] uppercase tracking-wider">{{ __('app.materials') }}</p>
                <h3 class="text-2xl md:text-3xl font-headline-md font-bold mt-1">{{ number_format($totalContents) }}</h3>
            </div>

            <div class="glass-card-static rounded-2xl p-4 md:p-6">
                <div class="flex justify-between items-start mb-3">
                    <div class="p-2 bg-primary-container/10 rounded-lg">
                        <span class="material-symbols-outlined text-primary-container">style</span>
                    </div>
                    <span class="text-xs font-label-sm text-primary-container bg-primary-container/10 px-2 py-1 rounded">
                        {{ number_format($totalFlashcards) }}
                    </span>
                </div>
                <p class="text-on-surface-variant font-label-sm text-[11px] uppercase tracking-wider">{{ __('app.flashcards') }}</p>
                <h3 class="text-2xl md:text-3xl font-headline-md font-bold mt-1">{{ number_format($totalFlashcards) }}</h3>
            </div>

            <div class="glass-card-static rounded-2xl p-4 md:p-6">
                <div class="flex justify-between items-start mb-3">
                    <div class="p-2 bg-tertiary-container/10 rounded-lg">
                        <span class="material-symbols-outlined text-tertiary-fixed-dim">quiz</span>
                    </div>
                    <span class="text-xs font-label-sm text-on-surface-variant bg-white/5 px-2 py-1 rounded">
                        {{ number_format($totalQuizzes) }}
                    </span>
                </div>
                <p class="text-on-surface-variant font-label-sm text-[11px] uppercase tracking-wider">{{ __('app.quizzes') }}</p>
                <h3 class="text-2xl md:text-3xl font-headline-md font-bold mt-1">{{ number_format($totalQuizzes) }}</h3>
            </div>
        </div>

        {{-- Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
            {{-- User Growth Line Chart --}}
            <div class="lg:col-span-2 glass-card-static rounded-2xl p-4 md:p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="font-headline-md text-xl font-bold">{{ __('app.user_growth') }}</h2>
                        <p class="text-sm text-on-surface-variant">{{ __('app.user_growth_desc') }}</p>
                    </div>
                </div>
                <div class="h-[280px] relative">
                    <canvas id="userGrowthChart"></canvas>
                </div>
            </div>

            {{-- SDG Distribution Doughnut --}}
            <div class="glass-card-static rounded-2xl p-4 md:p-6">
                <h2 class="font-headline-md text-xl font-bold mb-2">{{ __('app.learning_distribution') }}</h2>
                <p class="text-sm text-on-surface-variant mb-6">{{ __('app.learning_distribution_desc') }}</p>
                <div class="h-[220px] relative flex items-center justify-center">
                    <canvas id="sdgChart"></canvas>
                </div>
                <div class="mt-6 space-y-2" id="sdgLegend">
                    @foreach ($sdgDistribution as $label => $count)
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-on-surface-variant">{{ $label }}</span>
                            <span class="font-bold text-on-surface">{{ $count }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Activity --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
            {{-- Recent Users --}}
            <div class="glass-card-static rounded-2xl p-4 md:p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="font-headline-md text-xl font-bold">{{ __('app.recent_users') }}</h2>
                    <a href="{{ route('admin.user.index') }}" class="text-primary-container text-xs font-label-sm hover:underline">{{ __('app.view_all') }}</a>
                </div>
                <div class="space-y-4">
                    @forelse ($recentUsers as $u)
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-primary-container/20 flex items-center justify-center text-primary-container font-bold text-sm">
                                {{ strtoupper(substr($u->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium truncate">{{ $u->name }}</p>
                                <p class="text-xs text-on-surface-variant truncate">{{ $u->email }}</p>
                            </div>
                            <span class="text-[10px] text-on-surface-variant/60 whitespace-nowrap">{{ $u->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-on-surface-variant text-center py-4">{{ __('app.no_users_found') }}</p>
                    @endforelse
                </div>
            </div>

            {{-- Recent Materials --}}
            <div class="glass-card-static rounded-2xl p-4 md:p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="font-headline-md text-xl font-bold">{{ __('app.recent_materials') }}</h2>
                    <a href="{{ route('admin.content.index') }}" class="text-primary-container text-xs font-label-sm hover:underline">{{ __('app.view_all') }}</a>
                </div>
                <div class="space-y-4">
                    @forelse ($recentContents as $c)
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-secondary/10 flex items-center justify-center text-secondary">
                                <span class="material-symbols-outlined text-lg">article</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium truncate">{{ $c->title }}</p>
                                <p class="text-xs text-on-surface-variant truncate">{{ $c->user->name }} &middot; {{ $c->status }}</p>
                            </div>
                            <span class="text-[10px] text-on-surface-variant/60 whitespace-nowrap">{{ $c->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-on-surface-variant text-center py-4">{{ __('app.no_materials') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const isDark = document.documentElement.classList.contains('dark');
        const textColor = getComputedStyle(document.documentElement).getPropertyValue('--color-on-surface-variant').trim() || '#b9cacb';
        const gridColor = 'rgba(255,255,255,0.05)';
        const cyan = '#00f2ff';
        const purple = '#bbc3ff';

        // User Growth
        const growthCtx = document.getElementById('userGrowthChart');
        if (growthCtx) {
            new Chart(growthCtx, {
                type: 'line',
                data: {
                    labels: {!! $userGrowthLabels !!},
                    datasets: [{
                        label: '{{ __("app.total_users") }}',
                        data: {!! $userGrowthData !!},
                        borderColor: cyan,
                        backgroundColor: (ctx) => {
                            const g = ctx.chart.ctx.createLinearGradient(0, 0, 0, 280);
                            g.addColorStop(0, 'rgba(0, 242, 255, 0.3)');
                            g.addColorStop(1, 'rgba(0, 242, 255, 0)');
                            return g;
                        },
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: cyan,
                        pointBorderColor: '#050816',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                    },
                    scales: {
                        x: {
                            grid: { color: gridColor },
                            ticks: { color: textColor, font: { size: 11 } },
                        },
                        y: {
                            grid: { color: gridColor },
                            ticks: {
                                color: textColor,
                                font: { size: 11 },
                                stepSize: 1,
                            },
                            beginAtZero: true,
                        },
                    },
                }
            });
        }

        // SDG Distribution
        const sdgCtx = document.getElementById('sdgChart');
        if (sdgCtx) {
            const colors = [
                '#00f2ff', '#bbc3ff', '#ffd6ff', '#f8acff',
                '#74f5ff', '#dee0ff', '#0231de', '#00dbe7',
                '#00696f', '#00363a', '#002022', '#350040',
                '#570067', '#7b0090', '#690005', '#93000a',
                '#ffb4ab',
            ];
            new Chart(sdgCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! $sdgLabels !!},
                    datasets: [{
                        data: {!! $sdgData !!},
                        backgroundColor: colors.slice(0, {!! $sdgDistribution->count() !!}),
                        borderColor: '#050816',
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            display: false,
                        },
                    },
                },
            });
        }
    });
</script>
@endpush
