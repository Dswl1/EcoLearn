@extends('app')
@section('title')
    {{ __('app.dashboard') }} | {{ __('app.app_name') }}
@endsection

@section('content')
    <div class="space-y-8 max-w-screen-md">
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
                        <span class="material-symbols-outlined text-primary-container">library_books</span>
                    </div>
                    <span class="text-xs font-label-sm text-green-400 bg-green-400/10 px-2 py-1 rounded">
                        {{ $materialsStudied }}
                    </span>
                </div>
                <p class="text-on-surface-variant font-label-sm text-[11px] uppercase tracking-wider">{{ __('app.materials_studied') }}</p>
                <h3 class="text-2xl md:text-3xl font-headline-md font-bold mt-1">{{ number_format($materialsStudied) }}</h3>
            </div>

            <div class="glass-card-static rounded-2xl p-4 md:p-6">
                <div class="flex justify-between items-start mb-3">
                    <div class="p-2 bg-primary-container/10 rounded-lg">
                        <span class="material-symbols-outlined text-primary-container">style</span>
                    </div>
                    <span class="text-xs font-label-sm text-on-surface-variant bg-white/5 px-2 py-1 rounded">
                        {{ $totalFlashcardAttempts }} {{ __('app.attempts') }}
                    </span>
                </div>
                <p class="text-on-surface-variant font-label-sm text-[11px] uppercase tracking-wider">{{ __('app.flashcards_mastered') }}</p>
                <h3 class="text-2xl md:text-3xl font-headline-md font-bold mt-1">{{ number_format($flashcardsMastered) }}</h3>
                <p class="text-xs text-on-surface-variant mt-1">
                    <span class="text-primary-container">{{ $flashcardMaterialsCount }}</span> {{ __('app.materials_with_flashcards') }}
                </p>
            </div>

            <div class="glass-card-static rounded-2xl p-4 md:p-6">
                <div class="flex justify-between items-start mb-3">
                    <div class="p-2 bg-primary-container/10 rounded-lg">
                        <span class="material-symbols-outlined text-primary-container">quiz</span>
                    </div>
                    <span class="text-xs font-label-sm text-primary-container bg-primary-container/10 px-2 py-1 rounded">
                        {{ $totalQuizAttempts }} {{ __('app.attempts') }}
                    </span>
                </div>
                <p class="text-on-surface-variant font-label-sm text-[11px] uppercase tracking-wider">{{ __('app.average_score') }}</p>
                <h3 class="text-2xl md:text-3xl font-headline-md font-bold mt-1">{{ $avgQuizScore }}<span class="text-on-surface-variant text-lg font-normal">%</span></h3>
                <p class="text-xs text-on-surface-variant mt-1">
                    {{ number_format($quizPointsEarned) }}/{{ $totalPossiblePoints }} {{ __('app.points_out_of') }}
                </p>
            </div>

            <div class="glass-card-static rounded-2xl p-4 md:p-6">
                <div class="flex justify-between items-start mb-3">
                    <div class="p-2 bg-primary-container/10 rounded-lg">
                        <span class="material-symbols-outlined text-primary-container">trending_up</span>
                    </div>
                    <span class="text-xs font-label-sm text-on-surface-variant bg-white/5 px-2 py-1 rounded">
                        {{ $totalFlashcardAttempts + $totalQuizAttempts }} total
                    </span>
                </div>
                <p class="text-on-surface-variant font-label-sm text-[11px] uppercase tracking-wider">{{ __('app.learning_sessions') }}</p>
                <h3 class="text-2xl md:text-3xl font-headline-md font-bold mt-1">
                    {{ number_format($totalFlashcardAttempts + $totalQuizAttempts) }}
                </h3>
            </div>
        </div>

        {{-- Activity Chart --}}
        <div class="glass-card-static rounded-2xl p-4 md:p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="font-headline-md text-xl font-bold">{{ __('app.your_activity') }}</h2>
                    <p class="text-sm text-on-surface-variant">{{ __('app.activity_last_30_days') }}</p>
                </div>
            </div>
            <div class="h-[200px] relative">
                <canvas id="activityChart"></canvas>
            </div>
        </div>

        {{-- Materials Progress --}}
        <div class="glass-card-static rounded-2xl p-4 md:p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="font-headline-md text-xl font-bold">{{ __('app.your_progress') }}</h2>
                    <p class="text-sm text-on-surface-variant">{{ __('app.progress_by_material') }}</p>
                </div>
                <a href="{{ route('materials.index') }}" class="text-primary-container text-xs font-label-sm hover:underline">{{ __('app.browse_materials') }}</a>
            </div>
            @if ($materialsProgress->isEmpty())
                <div class="text-center py-12 text-on-surface-variant">
                    <span class="material-symbols-outlined text-4xl block mb-2">school</span>
                    <p>{{ __('app.no_progress_yet') }}</p>
                    <a href="{{ route('materials.index') }}" class="mt-3 inline-block px-6 py-2 rounded-full bg-primary-container/10 border border-primary-container/30 text-primary-fixed text-sm hover:bg-primary-container/20 transition-all">
                        {{ __('app.start_learning') }}
                    </a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($materialsProgress as $mp)
                        <a href="{{ route('materials.show', $mp->slug) }}" class="block glass-card-static rounded-xl p-4 hover:border-primary-container/30 transition-all">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-medium text-sm text-on-surface truncate pr-4">{{ $mp->title }}</h4>
                                <span class="text-[10px] text-on-surface-variant whitespace-nowrap">
                                    {{ $mp->flashcards_total }}/{{ $mp->total_flashcards }} {{ __('app.flashcards') }}
                                </span>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <div class="flex justify-between text-[11px] text-on-surface-variant mb-1">
                                        <span>{{ __('app.flashcards') }}</span>
                                        <span class="text-primary-fixed">{{ $mp->flashcards_mastered }}/{{ $mp->flashcards_total }}</span>
                                    </div>
                                    <div class="w-full h-1 bg-surface-container-highest rounded-full overflow-hidden">
                                        <div class="h-full bg-primary-fixed rounded-full transition-all" style="width: {{ $mp->flashcards_total > 0 ? ($mp->flashcards_mastered / max($mp->flashcards_total, 1)) * 100 : 0 }}%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between text-[11px] text-on-surface-variant mb-1">
                                        <span>{{ __('app.quiz_points') }}</span>
                                        <span class="text-primary-fixed">{{ $mp->quiz_score }}/{{ $mp->quiz_possible }}</span>
                                    </div>
                                    <div class="w-full h-1 bg-surface-container-highest rounded-full overflow-hidden">
                                        <div class="h-full bg-primary-container rounded-full transition-all" style="width: {{ $mp->quiz_possible > 0 ? ($mp->quiz_score / max($mp->quiz_possible, 1)) * 100 : 0 }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Recent Activity --}}
        <div class="glass-card-static rounded-2xl p-4 md:p-6">
            <h2 class="font-headline-md text-xl font-bold mb-6">{{ __('app.recent_activity') }}</h2>
            <div class="space-y-4">
                @forelse ($recentProgress as $rp)
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm
                            {{ $rp->type === 'flashcard'
                                ? ($rp->status === 'mastered' ? 'bg-green-500/10 text-green-400' : 'bg-white/5 text-on-surface-variant')
                                : ($rp->status === 'passed' ? 'bg-green-500/10 text-green-400' : 'bg-error/10 text-error') }}">
                            <span class="material-symbols-outlined text-lg">
                                {{ $rp->type === 'flashcard' ? 'style' : 'quiz' }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate">
                                @if ($rp->type === 'flashcard')
                                    {{ $rp->status === 'mastered' ? __('app.mastered_flashcard') : __('app.reviewed_flashcard') }}
                                @else
                                    {{ $rp->status === 'passed' ? __('app.passed_quiz') : __('app.attempted_quiz') }}
                                    @if ($rp->score)
                                        <span class="text-primary-fixed">(+{{ $rp->score }})</span>
                                    @endif
                                @endif
                            </p>
                            <p class="text-xs text-on-surface-variant truncate">
                                {{ $rp->content?->title ?? __('app.unknown_material') }}
                                &middot;
                                {{ $rp->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-on-surface-variant text-center py-4">{{ __('app.no_activity_yet') }}</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('activityChart');
        if (!ctx) return;

        const textColor = '#b9cacb';
        const gridColor = 'rgba(255,255,255,0.05)';
        const cyan = '#00f2ff';

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! $progressLabels !!},
                datasets: [{
                    label: '{{ __("app.activity") }}',
                    data: {!! $progressData !!},
                    backgroundColor: 'rgba(0, 242, 255, 0.3)',
                    borderColor: cyan,
                    borderWidth: 1,
                    borderRadius: 4,
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
                        ticks: { color: textColor, font: { size: 10 }, maxTicksLimit: 10 },
                    },
                    y: {
                        grid: { color: gridColor },
                        ticks: {
                            color: textColor,
                            font: { size: 10 },
                            stepSize: 1,
                        },
                        beginAtZero: true,
                    },
                },
            }
        });
    });
</script>
@endpush
