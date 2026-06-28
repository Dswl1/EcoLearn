@extends('app')
@section('title', __('app.profile') . ' | ' . __('app.app_name'))

@section('content')
<div class="relative">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_left,_var(--tw-gradient-stops))] from-primary/10 via-background to-background -z-10 blur-3xl opacity-50"></div>
    <div class="max-w-5xl mx-auto py-12 md:py-20 flex flex-col gap-section-gap">
        <!-- Profile Header -->
        <section class="flex flex-col md:flex-row items-center md:items-start gap-8 relative z-10">
            <!-- Avatar -->
            <div class="relative group">
                <div class="w-32 h-32 md:w-40 md:h-40 rounded-full overflow-hidden border-2 border-primary relative z-10 shadow-[0_0_30px_rgba(0,242,255,0.4)] transition-all duration-500 group-hover:shadow-[0_0_40px_rgba(0,242,255,0.6)] flex items-center justify-center bg-surface text-5xl md:text-6xl font-display-lg text-primary-container">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="absolute inset-0 rounded-full bg-primary/20 blur-[40px] -z-10 group-hover:bg-primary/40 transition-all duration-500"></div>
            </div>
            <!-- Info & Actions -->
            <div class="flex-1 flex flex-col items-center md:items-start text-center md:text-left gap-4">
                <div>
                    <h1 class="font-display-lg-mobile md:font-display-lg text-display-lg-mobile md:text-display-lg text-white mb-1 drop-shadow-[0_0_10px_rgba(255,255,255,0.2)]">{{ $user->name }}</h1>
                    <p class="font-body-lg text-body-lg text-on-surface-variant">{{ $user->is_admin ? __('app.admin') : __('app.explorer') }}</p>
                    <p class="font-label-sm text-label-sm text-on-surface-variant/60 mt-1">{{ __('app.member_since') }} {{ $user->created_at->format('M Y') }}</p>
                </div>
                <div class="flex flex-wrap justify-center md:justify-start gap-4 mt-2">
                    <a href="{{ route('profile.edit') }}" class="bg-primary text-on-primary px-6 py-3 rounded-lg font-label-sm text-label-sm hover:bg-primary-fixed transition-all shadow-[0_0_20px_rgba(0,242,255,0.4)] hover:shadow-[0_0_30px_rgba(0,242,255,0.6)] flex items-center gap-2 font-bold">
                        <span class="material-symbols-outlined text-sm">edit</span>
                        {{ __('app.edit_profile') }}
                    </a>
                    <a href="{{ route('dashboard') }}" class="glass-card text-on-surface px-6 py-3 rounded-lg font-label-sm text-label-sm hover:text-primary transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">dashboard</span>
                        {{ __('app.my_dashboard') }}
                    </a>
                </div>
            </div>
        </section>
        <!-- Quick Stats Bento Grid -->
        <section class="grid grid-cols-1 md:grid-cols-3 gap-6 relative z-10">
            <div class="glass-card rounded-xl p-6 flex flex-col items-center justify-center gap-2 relative overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-br from-primary/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <span class="material-symbols-outlined text-3xl text-primary mb-2 drop-shadow-[0_0_8px_rgba(0,242,255,0.6)]">library_books</span>
                <h3 class="font-display-lg-mobile text-display-lg-mobile text-white drop-shadow-[0_0_5px_rgba(255,255,255,0.3)]">{{ number_format($materialsStudied) }}</h3>
                <p class="font-label-sm text-label-sm text-on-surface-variant">{{ __('app.materials_studied') }}</p>
            </div>
            <div class="glass-card rounded-xl p-6 flex flex-col items-center justify-center gap-2 relative overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-br from-tertiary-fixed-dim/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <span class="material-symbols-outlined text-3xl text-tertiary-fixed-dim mb-2 drop-shadow-[0_0_8px_rgba(248,172,255,0.6)]">style</span>
                <h3 class="font-display-lg-mobile text-display-lg-mobile text-white drop-shadow-[0_0_5px_rgba(255,255,255,0.3)]">{{ number_format($flashcardsMastered) }}</h3>
                <p class="font-label-sm text-label-sm text-on-surface-variant">{{ __('app.flashcards_mastered') }}</p>
            </div>
            <div class="glass-card rounded-xl p-6 flex flex-col items-center justify-center gap-2 relative overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-br from-secondary-fixed/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <span class="material-symbols-outlined text-3xl text-secondary-fixed mb-2 drop-shadow-[0_0_8px_rgba(222,224,255,0.6)]">quiz</span>
                <h3 class="font-display-lg-mobile text-display-lg-mobile text-white drop-shadow-[0_0_5px_rgba(255,255,255,0.3)]">{{ $avgQuizScore }}<span class="text-on-surface-variant text-display-lg-mobile md:text-display-lg">%</span></h3>
                <p class="font-label-sm text-label-sm text-on-surface-variant">{{ __('app.average_score') }}</p>
            </div>
        </section>
        <!-- Recent Activity -->
        <section class="glass-card rounded-xl p-8 relative z-10">
            <h2 class="font-headline-md text-headline-md text-white mb-8 border-b border-white/10 pb-4 drop-shadow-[0_0_8px_rgba(255,255,255,0.2)]">{{ __('app.recent_activity') }}</h2>
            <div class="flex flex-col gap-6">
                @forelse ($recentProgress as $rp)
                <div class="flex gap-4 group">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full bg-[#050816] border border-primary/50 flex items-center justify-center shrink-0 relative z-10 shadow-[0_0_10px_rgba(0,242,255,0.2)] group-hover:shadow-[0_0_15px_rgba(0,242,255,0.5)] group-hover:border-primary transition-all {{ $rp->type === 'flashcard' ? '' : ($rp->status === 'passed' ? 'border-tertiary-fixed-dim/50 shadow-[0_0_10px_rgba(248,172,255,0.2)] group-hover:shadow-[0_0_15px_rgba(248,172,255,0.5)] group-hover:border-tertiary-fixed-dim' : 'border-secondary-fixed/50 shadow-[0_0_10px_rgba(222,224,255,0.2)] group-hover:shadow-[0_0_15px_rgba(222,224,255,0.5)] group-hover:border-secondary-fixed') }}">
                            <span class="material-symbols-outlined text-sm {{ $rp->type === 'flashcard' ? 'text-primary' : ($rp->status === 'passed' ? 'text-tertiary-fixed-dim' : 'text-secondary-fixed') }}">
                                {{ $rp->type === 'flashcard' ? 'style' : 'quiz' }}
                            </span>
                        </div>
                        @if (!$loop->last)
                        <div class="w-px h-full bg-white/10 mt-2 group-hover:bg-primary/30 transition-colors"></div>
                        @endif
                    </div>
                    <div class="{{ $loop->last ? '' : 'pb-6' }}">
                        <p class="font-body-md text-body-md text-on-surface mb-1">
                            @if ($rp->type === 'flashcard')
                                {{ $rp->status === 'mastered' ? __('app.mastered_flashcard') : __('app.reviewed_flashcard') }}
                            @else
                                {{ $rp->status === 'passed' ? __('app.passed_quiz') : __('app.attempted_quiz') }}
                                @if ($rp->score)
                                    <span class="text-primary-fixed drop-shadow-[0_0_5px_rgba(0,242,255,0.4)]">(+{{ $rp->score }})</span>
                                @endif
                            @endif
                        </p>
                        <p class="font-label-sm text-label-sm text-on-surface-variant">
                            {{ $rp->content?->title ?? __('app.unknown_material') }}
                            &middot;
                            {{ $rp->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
                @empty
                <div class="text-center py-12 text-on-surface-variant">
                    <span class="material-symbols-outlined text-4xl block mb-2">timeline</span>
                    <p>{{ __('app.no_activity_yet') }}</p>
                    <a href="{{ route('materials.index') }}" class="mt-3 inline-block px-6 py-2 rounded-full bg-primary-container/10 border border-primary-container/30 text-primary-fixed text-sm hover:bg-primary-container/20 transition-all">
                        {{ __('app.start_learning') }}
                    </a>
                </div>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection