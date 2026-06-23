@extends('app')
@section('title')
    {{ $content->title }} | {{ __('app.app_name') }}
@endsection

@section('content')
    <div class="flex flex-col md:flex-row md:items-start justify-between mb-6 md:mb-8 gap-4">
        <div class="min-w-0">
            <nav class="flex gap-2 text-label-sm font-label-sm text-on-surface-variant mb-2 overflow-x-auto scrollbar-hide">
                <a class="hover:text-primary-fixed whitespace-nowrap" href="{{ route('admin.content.index') }}">{{ __('app.materials') }}</a>
                <span class="whitespace-nowrap">/</span>
                <span class="text-primary-fixed whitespace-nowrap truncate">{{ $content->title }}</span>
            </nav>
            <h1 class="font-display-lg text-display-lg-mobile md:text-display-lg text-on-surface tracking-tight break-words">
                {{ $content->title }}</h1>
        </div>
        <div class="flex items-center gap-3 flex-shrink-0 w-full sm:w-auto flex-wrap">
            @if ($content->status === 'pending_review')
                <form method="POST" action="{{ route('admin.content.approve', $content) }}">
                    @csrf
                    <button type="submit"
                        class="px-4 sm:px-6 py-2 rounded-xl bg-green-500/10 border border-green-500/30 text-green-400 hover:bg-green-500/20 transition-all font-medium flex items-center gap-2 text-sm">
                        <span class="material-symbols-outlined text-[18px]">check</span>
                        {{ __('app.approve') }}
                    </button>
                </form>
                <button type="button" onclick="showRejectModal({{ $content->id }})"
                    class="px-4 sm:px-6 py-2 rounded-xl bg-error/10 border border-error/30 text-error hover:bg-error/20 transition-all font-medium flex items-center gap-2 text-sm">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                    {{ __('app.reject') }}
                </button>
            @endif
            <a href="{{ route('admin.content.edit', $content) }}"
                class="flex-1 sm:flex-none px-4 sm:px-6 py-2 border border-primary-container/30 text-primary-fixed rounded-full hover:bg-primary-container/10 transition-all font-medium flex items-center justify-center gap-2 text-sm sm:text-base">
                <span class="material-symbols-outlined text-[18px]">edit</span>
                <span class="sm:inline">{{ __('app.edit') }}</span>
            </a>
            <form method="POST" action="{{ route('admin.content.destroy', $content) }}"
                onsubmit="confirmAction(event, { title: '{{ __('app.are_you_sure_delete') }}' })" class="flex-1 sm:flex-none">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="w-full sm:w-auto px-4 sm:px-6 py-2 border border-error/30 text-error rounded-full hover:bg-error/10 transition-all font-medium flex items-center justify-center gap-2 text-sm sm:text-base">
                    <span class="material-symbols-outlined text-[18px]">delete</span>
                    <span class="sm:inline">{{ __('app.delete') }}</span>
                </button>
            </form>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6 md:mb-8">
        <div class="glass-card-static rounded-2xl p-4 md:p-5">
            <div class="flex items-center gap-3 mb-2">
                <div class="p-2 bg-primary/10 rounded-lg">
                    <span class="material-symbols-outlined text-primary text-lg">visibility</span>
                </div>
                <span class="text-label-sm text-[10px] uppercase tracking-wider text-on-surface-variant">{{ __('app.total_views') }}</span>
            </div>
            <p class="text-2xl md:text-3xl font-headline-md font-bold">{{ $totalViewers }}</p>
            <p class="text-[10px] text-on-surface-variant/60 mt-1">{{ __('app.unique_viewers') }}</p>
        </div>
        <div class="glass-card-static rounded-2xl p-4 md:p-5">
            <div class="flex items-center gap-3 mb-2">
                <div class="p-2 bg-secondary/10 rounded-lg">
                    <span class="material-symbols-outlined text-secondary text-lg">style</span>
                </div>
                <span class="text-label-sm text-[10px] uppercase tracking-wider text-on-surface-variant">{{ __('app.flashcard_learners') }}</span>
            </div>
            <p class="text-2xl md:text-3xl font-headline-md font-bold">{{ $flashcardLearners }}</p>
            <p class="text-[10px] text-on-surface-variant/60 mt-1">{{ __('app.mastery_rate') }}: {{ $masteryRate }}%</p>
        </div>
        <div class="glass-card-static rounded-2xl p-4 md:p-5">
            <div class="flex items-center gap-3 mb-2">
                <div class="p-2 bg-primary-fixed-dim/10 rounded-lg">
                    <span class="material-symbols-outlined text-primary-fixed-dim text-lg">quiz</span>
                </div>
                <span class="text-label-sm text-[10px] uppercase tracking-wider text-on-surface-variant">{{ __('app.quiz_takers') }}</span>
            </div>
            <p class="text-2xl md:text-3xl font-headline-md font-bold">{{ $quizTakers }}</p>
            <p class="text-[10px] text-on-surface-variant/60 mt-1">{{ __('app.total_attempts') }}: {{ $totalQuizAttempts }}</p>
        </div>
        <div class="glass-card-static rounded-2xl p-4 md:p-5">
            <div class="flex items-center gap-3 mb-2">
                <div class="p-2 bg-green-500/10 rounded-lg">
                    <span class="material-symbols-outlined text-green-400 text-lg">score</span>
                </div>
                <span class="text-label-sm text-[10px] uppercase tracking-wider text-on-surface-variant">{{ __('app.avg_score') }}</span>
            </div>
            <p class="text-2xl md:text-3xl font-headline-md font-bold">{{ $avgQuizScore ? number_format($avgQuizScore, 1) : '-' }}</p>
            <p class="text-[10px] text-on-surface-variant/60 mt-1">{{ __('app.average_quiz_score') }}</p>
        </div>
    </div>

    {{-- Chart --}}
    {{-- @if ($totalViewers > 0)
        <div class="glass-card-static rounded-2xl p-4 md:p-6 mb-6 md:mb-8">
            <h3 class="font-headline-md text-headline-md text-on-surface mb-4">{{ __('app.engagement_overview') }}</h3>
            <div class="relative h-48 md:h-64">
                <canvas id="engagementChart"></canvas>
            </div>
        </div>
    @endif --}}

    <div class="grid grid-cols-12 gap-gutter">
        <div class="col-span-12 xl:col-span-8 space-y-6 md:space-y-8">
            <section class="glass-card-static rounded-2xl p-4 md:p-8">
                @if ($content->description)
                    <p class="text-body-lg text-on-surface-variant mb-6 leading-relaxed">{{ $content->description }}</p>
                @endif
                <div class="prose prose-invert max-w-none font-body-lg text-on-surface/80 leading-relaxed break-words">
                    {!! $content->body_html !!}
                </div>
            </section>

            @if ($content->flashcards->count())
                <section class="glass-card-static rounded-2xl p-4 md:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-headline-md text-headline-md text-on-surface">{{ __('app.flashcards') }}</h3>
                        <span class="text-label-sm text-primary-container">{{ $content->flashcards->count() }} {{ __('app.cards') }}</span>
                    </div>
                    <div class="space-y-4">
                        @foreach ($content->flashcards as $fc)
                            <div class="glass-card-static rounded-xl p-4 border border-white/5">
                                <p class="font-label-sm text-label-sm text-primary-fixed mb-2">{{ $fc->question }}</p>
                                <p class="text-body-md text-on-surface-variant">{{ $fc->answer }}</p>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            @if ($content->quizzes->count())
                <section class="glass-card-static rounded-2xl p-4 md:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-headline-md text-headline-md text-on-surface">{{ __('app.quizzes') }}</h3>
                        <span class="text-label-sm text-primary-container">{{ $content->quizzes->count() }} {{ __('app.questions') }}</span>
                    </div>
                    <div class="space-y-6">
                        @foreach ($content->quizzes as $qz)
                            <div class="glass-card-static rounded-xl p-4 border border-white/5">
                                <p class="font-label-sm text-label-sm text-primary-fixed mb-3">{{ $qz->question }}</p>
                                <div class="space-y-2">
                                    @foreach ($qz->options ?? [] as $j => $option)
                                        <div class="flex items-center gap-2 p-2 rounded-lg {{ (string) $j === (string) $qz->correct_answer ? 'bg-green-500/10 border border-green-500/30' : 'bg-surface-container-low/50' }}">
                                            <span class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-bold {{ (string) $j === (string) $qz->correct_answer ? 'bg-green-500/20 text-green-400' : 'bg-white/10 text-on-surface-variant' }}">
                                                {{ chr(65 + $j) }}
                                            </span>
                                            <span class="text-body-md text-on-surface">{{ $option }}</span>
                                            @if ((string) $j === (string) $qz->correct_answer)
                                                <span class="material-symbols-outlined text-green-400 text-[16px] ml-auto">check_circle</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>

        <div class="col-span-12 xl:col-span-4 space-y-6 md:space-y-8">
            @if ($content->thumbnail)
                <section class="glass-card rounded-2xl overflow-hidden">
                    <img src="{{ Storage::url($content->thumbnail) }}" alt="{{ $content->title }}"
                        class="w-full object-cover" />
                </section>
            @endif

            <section class="glass-card rounded-2xl p-4 md:p-6 space-y-4">
                <h3 class="font-headline-md text-headline-md text-on-surface">{{ __('app.details') }}</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-label-sm text-on-surface-variant">{{ __('app.status') }}</span>
                        <span
                            class="text-label-sm px-2 py-1 rounded-full
                                @if ($content->status === 'published') bg-green-500/20 text-green-400
                                @elseif ($content->status === 'pending_review') bg-blue-500/20 text-blue-400
                                @elseif ($content->status === 'rejected') bg-error/20 text-error
                                @else bg-yellow-500/20 text-yellow-400 @endif">
                            {{ __("app.{$content->status}") }}
                        </span>
                    </div>
                    @if ($content->sdg_category)
                        <div class="flex justify-between">
                            <span class="text-label-sm text-on-surface-variant">{{ __('app.sdg_goal') }}</span>
                            <span class="text-label-sm text-primary-container">{{ $content->sdg_category }}</span>
                        </div>
                    @endif
                    @if ($content->difficulty)
                        <div class="flex justify-between">
                            <span class="text-label-sm text-on-surface-variant">{{ __('app.difficulty') }}</span>
                            <span
                                class="text-label-sm uppercase {{ $content->difficulty === 'expert' ? 'text-error' : ($content->difficulty === 'core' ? 'text-primary-container' : 'text-on-surface-variant') }}">{{ __("app.{$content->difficulty}") }}</span>
                        </div>
                    @endif
                    @if ($content->tags)
                        <div class="flex justify-between">
                            <span class="text-label-sm text-on-surface-variant">{{ __('app.keywords') }}</span>
                            <span class="text-label-sm text-on-surface">{{ $content->tags }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-label-sm text-on-surface-variant">{{ __('app.author') }}</span>
                        <span class="text-label-sm text-on-surface">{{ $content->user->name }}</span>
                    </div>
                    @if ($content->status === 'rejected' && $content->rejection_reason)
                        <div class="flex justify-between">
                            <span class="text-label-sm text-on-surface-variant">{{ __('app.rejection_reason_label') }}</span>
                            <span class="text-label-sm text-error text-right max-w-[200px]">{{ $content->rejection_reason }}</span>
                        </div>
                    @endif
                    @if ($content->submitted_at)
                        <div class="flex justify-between">
                            <span class="text-label-sm text-on-surface-variant">{{ __('app.submitted_at') }}</span>
                            <span class="text-label-sm text-on-surface">{{ $content->submitted_at->format('M d, Y H:i') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-label-sm text-on-surface-variant">{{ __('app.created') }}</span>
                        <span class="text-label-sm text-on-surface">{{ $content->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-label-sm text-on-surface-variant">{{ __('app.updated_at') }}</span>
                        <span class="text-label-sm text-on-surface">{{ $content->updated_at->format('M d, Y') }}</span>
                    </div>
                    @if ($content->published_at)
                        <div class="flex justify-between">
                            <span class="text-label-sm text-on-surface-variant">{{ __('app.published_at') }}</span>
                            <span class="text-label-sm text-on-surface">{{ $content->published_at->format('M d, Y') }}</span>
                        </div>
                    @endif
                </div>
            </section>

            <section class="glass-card-static rounded-2xl p-4 md:p-6 space-y-3">
                <h3 class="font-headline-md text-headline-md text-on-surface">{{ __('app.quick_actions') }}</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.content.edit', $content) }}"
                        class="w-full block text-center px-6 py-3 rounded-xl bg-primary-container/10 border border-primary-container/30 text-primary-fixed hover:bg-primary-container/20 transition-all font-medium">
                        <span class="material-symbols-outlined inline-block align-middle mr-1 text-[18px]">edit</span>
                        {{ __('app.edit_material') }}
                    </a>
                    <a href="{{ route('admin.content.edit', $content) }}#flashcards-section"
                        class="w-full block text-center px-6 py-3 rounded-xl border border-white/10 text-on-surface-variant hover:bg-white/5 transition-all font-medium">
                        <span class="material-symbols-outlined inline-block align-middle mr-1 text-[18px]">style</span>
                        {{ __('app.flashcards') }} ({{ $content->flashcards->count() }})
                    </a>
                    <a href="{{ route('admin.content.edit', $content) }}#quizzes-section"
                        class="w-full block text-center px-6 py-3 rounded-xl border border-white/10 text-on-surface-variant hover:bg-white/5 transition-all font-medium">
                        <span class="material-symbols-outlined inline-block align-middle mr-1 text-[18px]">quiz</span>
                        {{ __('app.quizzes') }} ({{ $content->quizzes->count() }})
                    </a>
                    <a href="{{ route('admin.content.index') }}"
                        class="w-full block text-center px-6 py-3 rounded-xl border border-white/10 text-on-surface-variant hover:bg-white/5 transition-all font-medium">
                        <span class="material-symbols-outlined inline-block align-middle mr-1 text-[18px]">arrow_back</span>
                        {{ __('app.back_to_materials') }}
                    </a>
                </div>
            </section>
        </div>
    </div>

    {{-- Reject Modal --}}
    <div id="reject-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="bg-surface/90 backdrop-blur-2xl border border-white/10 rounded-2xl p-6 w-full max-w-md mx-4 shadow-2xl">
            <h3 class="font-headline-md text-headline-md text-on-surface mb-4">{{ __('app.reject_content') }}</h3>
            <form id="reject-form" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-2">{{ __('app.rejection_reason_label') }}</label>
                    <textarea name="rejection_reason" rows="4" required
                        class="w-full bg-surface-container-high border border-outline-variant/20 rounded-lg px-4 py-3 text-sm text-on-surface placeholder:text-on-surface-variant/50 focus:outline-none focus:border-primary-container"
                        placeholder="{{ __('app.rejection_reason_placeholder') }}"></textarea>
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="hideRejectModal()"
                        class="px-4 py-2 border border-white/10 text-on-surface-variant rounded-full hover:border-white/30 transition-all text-sm">{{ __('app.cancel') }}</button>
                    <button type="submit"
                        class="px-6 py-2 bg-error text-white rounded-full font-medium hover:brightness-110 transition-all text-sm">{{ __('app.reject') }}</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showRejectModal(contentId) {
            const modal = document.getElementById('reject-modal');
            const form = document.getElementById('reject-form');
            form.action = '{{ url('admin/content') }}/' + contentId + '/reject';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function hideRejectModal() {
            const modal = document.getElementById('reject-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.getElementById('reject-modal')?.addEventListener('click', function(e) {
            if (e.target === this) hideRejectModal();
        });
    </script>
@endsection

@push('scripts')
    @if ($totalViewers > 0)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('engagementChart');
            if (!ctx) return;
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($engagementData['labels']),
                    datasets: [{
                        label: '{{ __('app.users') }}',
                        data: @json($engagementData['data']),
                        backgroundColor: [
                            'rgba(0, 242, 255, 0.7)',
                            'rgba(168, 130, 255, 0.7)',
                            'rgba(255, 159, 67, 0.7)',
                        ],
                        borderColor: [
                            'rgba(0, 242, 255, 1)',
                            'rgba(168, 130, 255, 1)',
                            'rgba(255, 159, 67, 1)',
                        ],
                        borderWidth: 1,
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                color: 'rgba(255,255,255,0.5)',
                            },
                            grid: { color: 'rgba(255,255,255,0.05)' },
                        },
                        x: {
                            ticks: {
                                color: 'rgba(255,255,255,0.7)',
                                font: { size: 11 },
                            },
                            grid: { display: false },
                        },
                    },
                },
            });
        });
    </script>
    @endif
@endpush
