@extends('app')
@section('title')
    {{ $content->title }} | {{ __('app.app_name') }}
@endsection

@section('content')
    <div x-data="{ tab: 'material' }" class="grid grid-cols-12 gap-gutter">
        <div class="col-span-12 xl:col-span-8 space-y-6 md:space-y-8">
            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                <div class="min-w-0">
                    <nav class="flex gap-2 text-label-sm font-label-sm text-on-surface-variant mb-2 overflow-x-auto scrollbar-hide">
                        <a class="hover:text-primary-fixed whitespace-nowrap" href="{{ route('materials.index') }}">{{ __('app.materials') }}</a>
                        <span class="whitespace-nowrap">/</span>
                        <span class="text-primary-fixed whitespace-nowrap truncate">{{ $content->title }}</span>
                    </nav>
                    <h1 class="font-display-lg text-display-lg-mobile md:text-display-lg text-on-surface tracking-tight break-words">{{ $content->title }}</h1>
                </div>
            </div>

            <div class="flex border-b border-white/10 gap-1">
                <button @click="tab = 'material'"
                    class="px-5 py-3 text-label-sm font-medium transition-all duration-200 border-b-2 -mb-[1px]"
                    :class="tab === 'material' ? 'text-primary-container border-primary-container' : 'text-on-surface-variant border-transparent hover:text-on-surface'">
                    <span class="material-symbols-outlined text-[18px] align-middle mr-1.5">article</span>
                    {{ __('app.materials') }}
                </button>
                @if ($content->flashcards->count())
                    <button @click="tab = 'flashcards'"
                        class="px-5 py-3 text-label-sm font-medium transition-all duration-200 border-b-2 -mb-[1px]"
                        :class="tab === 'flashcards' ? 'text-primary-container border-primary-container' : 'text-on-surface-variant border-transparent hover:text-on-surface'">
                        <span class="material-symbols-outlined text-[18px] align-middle mr-1.5">style</span>
                        {{ __('app.flashcards') }}
                        <span class="ml-1.5 text-[10px] px-1.5 py-0.5 rounded-full bg-primary-container/10">{{ $content->flashcards->count() }}</span>
                    </button>
                @endif
                @if ($content->quizzes->count())
                    <button @click="tab = 'quiz'"
                        class="px-5 py-3 text-label-sm font-medium transition-all duration-200 border-b-2 -mb-[1px]"
                        :class="tab === 'quiz' ? 'text-primary-container border-primary-container' : 'text-on-surface-variant border-transparent hover:text-on-surface'">
                        <span class="material-symbols-outlined text-[18px] align-middle mr-1.5">quiz</span>
                        {{ __('app.quizzes') }}
                        <span class="ml-1.5 text-[10px] px-1.5 py-0.5 rounded-full bg-primary-container/10">{{ $content->quizzes->count() }}</span>
                    </button>
                @endif
            </div>

            {{-- Tab: Material --}}
            <div x-show="tab === 'material'" x-transition.opacity.duration.200ms>
                @if ($content->thumbnail)
                    <section class="glass-card rounded-2xl overflow-hidden">
                        <img src="{{ Storage::url($content->thumbnail) }}" alt="{{ $content->title }}"
                            class="w-full object-cover max-h-96" />
                    </section>
                @endif
                @if ($content->description)
                    <section class="glass-card-static rounded-2xl p-4 md:p-8">
                        <p class="text-body-lg text-on-surface-variant leading-relaxed">{{ $content->description }}</p>
                    </section>
                @endif
                <section class="glass-card rounded-2xl p-4 md:p-8">
                    @auth
                        <div class="prose prose-invert max-w-none font-body-lg text-on-surface/80 leading-relaxed break-words">
                            {!! $content->body_html !!}
                        </div>
                    @else
                        <div class="relative">
                            <div class="prose prose-invert max-w-none font-body-lg text-on-surface/80 leading-relaxed break-words whitespace-pre-wrap overflow-hidden max-h-60">
                                <p>{{ $previewBody }}</p>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-surface via-surface/80 to-transparent"></div>
                        </div>
                        <div class="mt-6 text-center">
                            <a href="{{ route('login') }}"
                                class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-primary text-on-primary font-label-sm hover:brightness-110 transition-all shadow-[0_0_12px_rgba(0,242,255,0.2)]">
                                <span class="material-symbols-outlined text-[18px]">lock_open</span>
                                {{ __('app.guest_login_to_read') }}
                            </a>
                        </div>
                    @endauth
                </section>
            </div>

            {{-- Tab: Flashcards --}}
            @if ($content->flashcards->count())
                <div x-show="tab === 'flashcards'" x-transition.opacity.duration.200ms>
                    @auth
                    <div x-data="flashcardSession(@js($content->flashcards), @js($progress))" class="flex flex-col items-center w-full max-w-3xl mx-auto">
                        <template x-if="!isComplete">
                            <div class="w-full space-y-6">
                                {{-- Progress Bar --}}
                                <div class="w-full max-w-2xl mx-auto flex flex-col items-center">
                                    <div class="flex justify-between w-full mb-2 font-label-sm text-label-sm text-on-surface-variant">
                                        <span>{{ __('app.session_progress') }}</span>
                                        <span class="text-primary-fixed" x-text="`${masteredCount}/${totalCount} {{ __('app.cards_mastered') }}`"></span>
                                    </div>
                                    <div class="w-full h-1 bg-surface-container-highest rounded-full overflow-hidden">
                                        <div class="h-full bg-primary-fixed rounded-full progress-glow transition-all duration-500" :style="`width: ${percentComplete}%`"></div>
                                    </div>
                                </div>

                                {{-- Card --}}
                                <div class="w-full max-w-2xl aspect-[4/3] md:aspect-[16/9] perspective-1000 cursor-pointer relative z-20 mx-auto" @click="flipCard">
                                    <div class="relative w-full h-full transition-transform duration-700 transform-style-3d flip-card-inner" :class="flipped ? 'flipped' : ''">
                                        {{-- Front --}}
                                        <div class="absolute inset-0 backface-hidden bg-surface-container/40 backdrop-blur-xl border border-white/10 rounded-xl shadow-[inset_0_0_0_1px_rgba(255,255,255,0.05)] flex flex-col items-center justify-center p-8 text-center hover:border-primary/30 transition-colors">
                                            <span class="material-symbols-outlined text-primary-fixed/50 text-[48px] mb-6 block">bolt</span>
                                            <h2 class="font-headline-md text-[24px] md:text-[32px] text-primary-fixed tracking-tight font-semibold px-4" x-text="currentCard.question"></h2>
                                            <p class="mt-8 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest opacity-60">{{ __('app.card_front_hint') }}</p>
                                        </div>
                                        {{-- Back --}}
                                        <div class="absolute inset-0 backface-hidden bg-surface-container-high/60 backdrop-blur-2xl border border-primary-container/30 rounded-xl shadow-[inset_0_0_0_1px_rgba(0,242,255,0.1),0_0_20px_rgba(0,242,255,0.05)] flex flex-col items-center justify-center p-8 md:p-12 text-center rotate-y-180">
                                            <div class="w-16 h-1 bg-primary-container/50 mb-8 rounded-full"></div>
                                            <p class="font-body-lg text-body-lg md:text-[22px] leading-relaxed text-on-surface px-4" x-text="currentCard.answer"></p>
                                            @if ($content->sdg_category)
                                                <div class="mt-8 px-4 py-2 bg-secondary-container/20 border border-secondary/20 rounded font-label-sm text-label-sm text-secondary-fixed">
                                                    {{ $content->sdg_category }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Action Buttons --}}
                                <div class="flex gap-4 w-full max-w-md mx-auto justify-center" x-show="flipped" x-transition.opacity>
                                    <button @click.stop="markReview()"
                                        class="flex-1 py-4 px-6 rounded-lg bg-surface-container border border-white/10 text-on-surface font-label-sm text-label-sm hover:bg-surface-bright transition-colors flex items-center justify-center gap-2">
                                        <span class="material-symbols-outlined text-[18px]">history</span>
                                        {{ __('app.review_later') }}
                                    </button>
                                    <button @click.stop="markMastered()"
                                        class="flex-1 py-4 px-6 rounded-lg bg-primary text-on-primary font-label-sm text-label-sm shadow-[0_0_10px_rgba(0,219,231,0.2)] hover:brightness-110 transition-all flex items-center justify-center gap-2">
                                        <span class="material-symbols-outlined text-[18px]">check_circle</span>
                                        {{ __('app.got_it') }}
                                    </button>
                                </div>

                                {{-- Card Navigation Dots --}}
                                <div class="flex justify-center gap-1.5 mt-2">
                                    <template x-for="(card, idx) in cards" :key="card.id">
                                        <button @click="currentIndex = idx; flipped = false"
                                            class="w-2 h-2 rounded-full transition-all duration-300"
                                            :class="getCardStatus(card) === 'mastered'
                                                ? 'bg-primary-fixed'
                                                : idx === currentIndex
                                                    ? 'bg-primary-container w-4'
                                                    : 'bg-white/10 hover:bg-white/30'">
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>

                        {{-- Session Complete --}}
                        <template x-if="isComplete">
                            <div class="w-full flex flex-col items-center justify-center py-16 space-y-6 text-center">
                                <span class="material-symbols-outlined text-primary-fixed text-[72px]">stars</span>
                                <h2 class="font-headline-md text-headline-md text-primary-fixed">{{ __('app.session_complete') }}</h2>
                                <p class="text-body-lg text-on-surface-variant max-w-md">{{ __('app.session_complete_desc') }}</p>
                                <button @click="restartSession()"
                                    class="px-8 py-3 rounded-full border border-primary-container/30 text-primary-fixed hover:bg-primary-container/10 transition-all font-label-sm text-label-sm">
                                    {{ __('app.restart_session') }}
                                </button>
                            </div>
                        </template>
                    </div>
                    @else
                    <div class="glass-card-static rounded-2xl p-8 md:p-12 text-center">
                        <span class="material-symbols-outlined text-5xl text-on-surface-variant/20 mb-4">lock</span>
                        <h3 class="font-headline-md text-headline-md text-on-surface mb-2">{{ __('app.guest_flashcards_locked') }}</h3>
                        <p class="text-body-md text-on-surface-variant mb-6 max-w-md mx-auto">{{ __('app.guest_login_to_access') }}</p>
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-primary text-on-primary font-label-sm hover:brightness-110 transition-all shadow-[0_0_12px_rgba(0,242,255,0.2)]">
                            <span class="material-symbols-outlined text-[18px]">login</span>
                            {{ __('app.guest_login_to_continue') }}
                        </a>
                    </div>
                    @endauth
                </div>
            @endif

            {{-- Tab: Quiz --}}
            @if ($content->quizzes->count())
                <div x-show="tab === 'quiz'" x-transition.opacity.duration.200ms>
                    @auth
                    <div x-data="quizSession(@js($content->quizzes), @js($progress))" class="w-full">
                        {{-- Score Header --}}
                        <section class="glass-card-static rounded-2xl p-4 md:p-6 mb-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="font-label-sm text-label-sm text-on-surface-variant">{{ __('app.score_label') }}</h3>
                                    <p class="font-headline-md text-headline-md text-primary-fixed mt-1">
                                        <span x-text="totalEarned"></span><span class="text-on-surface-variant">/<span x-text="totalPossible"></span></span>
                                        <span class="text-body-md text-on-surface-variant font-normal" x-text="`{{ __('app.points_out_of') }}`"></span>
                                    </p>
                                </div>
                                <template x-if="isComplete">
                                    <span class="px-4 py-2 rounded-full bg-primary-container/10 border border-primary-container/30 text-primary-fixed text-label-sm">
                                        {{ __('app.quiz_complete') }}
                                    </span>
                                </template>
                            </div>
                            <div class="mt-3 w-full h-1 bg-surface-container-highest rounded-full overflow-hidden">
                                <div class="h-full bg-primary-fixed rounded-full progress-glow transition-all duration-500" :style="`width: ${(totalEarned / totalPossible) * 100}%`"></div>
                            </div>
                        </section>

                        {{-- Questions --}}
                        <div class="space-y-6">
                            <template x-for="(q, qi) in questions" :key="q.id">
                                <div class="glass-card-static rounded-xl border border-white/5 p-4 md:p-6 quiz-option">
                                    <div class="flex items-start justify-between gap-3 mb-4">
                                        <p class="font-label-sm text-label-sm text-primary-fixed flex-1" x-text="q.question"></p>
                                        <span class="text-label-sm text-on-surface-variant whitespace-nowrap" x-text="`${q.points} pts`"></span>
                                    </div>
                                    <div class="space-y-2 mb-4">
                                        <template x-for="(opt, oi) in q.options" :key="oi">
                                            <label @click="selectAnswer(qi, oi)"
                                                class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition-all duration-200"
                                                :class="[
                                                    q.submitted && oi === q.correct
                                                        ? 'border-green-500/50 bg-green-500/10'
                                                        : q.submitted && q.selected === oi && oi !== q.correct
                                                            ? 'border-error/50 bg-error/10'
                                                            : q.selected === oi
                                                                ? 'border-primary-container/50 bg-primary-container/10'
                                                                : 'border-white/5 hover:border-white/20'
                                                ]">
                                                <span class="w-6 h-6 rounded-full border-2 flex items-center justify-center text-[10px] font-bold flex-shrink-0 transition-all"
                                                    :class="q.submitted && oi === q.correct
                                                        ? 'border-green-500 bg-green-500 text-white'
                                                        : q.submitted && q.selected === oi && oi !== q.correct
                                                            ? 'border-error bg-error text-white'
                                                            : q.selected === oi
                                                                ? 'border-primary-container bg-primary-container text-on-primary-container'
                                                                : 'border-white/20 text-on-surface-variant'"
                                                    x-text="String.fromCharCode(65 + oi)">
                                                </span>
                                                <span class="text-body-md text-on-surface flex-1" x-text="opt"></span>
                                                <span x-show="q.submitted && oi === q.correct"
                                                    class="material-symbols-outlined text-green-400">check_circle</span>
                                                <span x-show="q.submitted && q.selected === oi && oi !== q.correct"
                                                    class="material-symbols-outlined text-error">cancel</span>
                                            </label>
                                        </template>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <button @click="submitSingleQuestion(qi)"
                                            x-show="q.submitted === false && batchSubmitted === true"
                                            class="px-5 py-2 bg-primary-container/10 border border-primary-container/30 text-primary-fixed rounded-full hover:bg-primary-container/20 transition-all text-sm font-medium">
                                            {{ __('app.submit_answer') }}
                                        </button>
                                        <template x-if="q.submitted">
                                            <div class="flex items-center gap-2">
                                                <template x-if="q.selected === q.correct">
                                                    <div class="flex items-center gap-1">
                                                        <span class="material-symbols-outlined text-green-400 text-[18px]">check_circle</span>
                                                        <span class="text-green-400 font-bold text-sm">{{ __('app.correct_first_try') }}</span>
                                                    </div>
                                                </template>
                                                <template x-if="q.selected !== q.correct">
                                                    <div class="flex items-center gap-2">
                                                        <span class="material-symbols-outlined text-error text-[18px]">cancel</span>
                                                        <span class="text-error font-medium text-sm">{{ __('app.quiz_wrong') }}</span>
                                                        <button @click="resetQuestion(qi)"
                                                            class="text-xs text-on-surface-variant hover:text-primary-container underline ml-2">
                                                            {{ __('app.try_again') }}
                                                        </button>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>

                        {{-- Submit All Button --}}
                        <div class="mt-6 text-center" x-show="!batchSubmitted">
                            <button @click="submitAll()" :disabled="!allAnswered"
                                class="px-8 py-3 rounded-full transition-all font-label-sm text-label-sm inline-flex items-center gap-2"
                                :class="allAnswered
                                    ? 'bg-primary text-on-primary shadow-[0_0_12px_rgba(0,242,255,0.2)] hover:brightness-110 cursor-pointer'
                                    : 'bg-surface-container text-on-surface-variant cursor-not-allowed opacity-60'">
                                <span class="material-symbols-outlined text-[18px]">how_to_reg</span>
                                {{ __('app.submit_all_answers') }}
                            </button>
                            <p x-show="!allAnswered" class="text-xs text-on-surface-variant mt-2">{{ __('app.answer_all_questions') }}</p>
                        </div>

                        {{-- Complete Banner --}}
                        <template x-if="isComplete">
                            <div class="mt-8 glass-card-static rounded-2xl p-6 md:p-8 text-center space-y-3">
                                <span class="material-symbols-outlined text-primary-fixed text-[56px]">award_star</span>
                                <h3 class="font-headline-md text-headline-md text-primary-fixed">{{ __('app.quiz_complete') }}</h3>
                                <p class="text-body-lg text-on-surface-variant" x-text="`{{ __('app.quiz_complete_desc') }} ${totalEarned}/${totalPossible} {{ __('app.points_out_of') }}`"></p>
                            </div>
                        </template>
                    </div>
                    @else
                    <div class="glass-card-static rounded-2xl p-8 md:p-12 text-center">
                        <span class="material-symbols-outlined text-5xl text-on-surface-variant/20 mb-4">lock</span>
                        <h3 class="font-headline-md text-headline-md text-on-surface mb-2">{{ __('app.guest_quiz_locked') }}</h3>
                        <p class="text-body-md text-on-surface-variant mb-6 max-w-md mx-auto">{{ __('app.guest_login_to_access') }}</p>
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-primary text-on-primary font-label-sm hover:brightness-110 transition-all shadow-[0_0_12px_rgba(0,242,255,0.2)]">
                            <span class="material-symbols-outlined text-[18px]">login</span>
                            {{ __('app.guest_login_to_continue') }}
                        </a>
                    </div>
                    @endauth
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="col-span-12 xl:col-span-4 space-y-6 md:space-y-8">
            
            <section class="glass-card-static rounded-2xl p-4 md:p-6 space-y-4">
                <h3 class="font-headline-md text-headline-md text-on-surface">{{ __('app.details') }}</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-label-sm text-on-surface-variant">{{ __('app.difficulty') }}</span>
                        <span class="text-label-sm uppercase {{ $content->difficulty === 'expert' ? 'text-error' : ($content->difficulty === 'core' ? 'text-primary-container' : 'text-on-surface-variant') }}">{{ $content->difficulty ? __("app.{$content->difficulty}") : '-' }}</span>
                    </div>
                    @if ($content->sdg_category)
                        <div class="flex justify-between">
                            <span class="text-label-sm text-on-surface-variant">{{ __('app.sdg_goal') }}</span>
                            <span class="text-label-sm text-primary-container">{{ $content->sdg_category }}</span>
                        </div>
                    @endif
                    @if ($content->tags)
                        <div class="flex justify-between">
                            <span class="text-label-sm text-on-surface-variant">{{ __('app.keywords') }}</span>
                            <span class="text-label-sm text-on-surface text-right">{{ $content->tags }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-label-sm text-on-surface-variant">{{ __('app.author') }}</span>
                        <span class="text-label-sm text-on-surface">{{ $content->user->name }}</span>
                    </div>
                    @if ($content->published_at)
                        <div class="flex justify-between">
                            <span class="text-label-sm text-on-surface-variant">{{ __('app.published_at') }}</span>
                            <span class="text-label-sm text-on-surface">{{ $content->published_at->format('M d, Y') }}</span>
                        </div>
                    @endif
                </div>
            </section>

            {{-- Quick Actions --}}
            <section class="glass-card-static rounded-2xl p-4 md:p-6 space-y-3">
                <h3 class="font-headline-md text-headline-md text-on-surface">{{ __('app.quick_actions') }}</h3>
                <div class="grid grid-cols-2 gap-2">
                    <button @click="tab = 'material'"
                        class="flex flex-col items-center gap-1 px-3 py-3 rounded-xl border transition-all font-medium text-center"
                        :class="tab === 'material' ? 'bg-primary-container/10 border-primary-container/30 text-primary-fixed' : 'border-white/10 text-on-surface-variant hover:bg-white/5'">
                        <span class="material-symbols-outlined text-[22px]">article</span>
                        <span class="text-[11px] leading-tight">{{ __('app.materials') }}</span>
                    </button>
                    @if ($content->flashcards->count())
                        <button @click="tab = 'flashcards'"
                            class="flex flex-col items-center gap-1 px-3 py-3 rounded-xl border transition-all font-medium text-center"
                            :class="tab === 'flashcards' ? 'bg-primary-container/10 border-primary-container/30 text-primary-fixed' : 'border-white/10 text-on-surface-variant hover:bg-white/5'">
                            <span class="material-symbols-outlined text-[22px]">style</span>
                            <span class="text-[11px] leading-tight">{{ __('app.flashcards') }}</span>
                        </button>
                    @endif
                    @if ($content->quizzes->count())
                        <button @click="tab = 'quiz'"
                            class="flex flex-col items-center gap-1 px-3 py-3 rounded-xl border transition-all font-medium text-center"
                            :class="tab === 'quiz' ? 'bg-primary-container/10 border-primary-container/30 text-primary-fixed' : 'border-white/10 text-on-surface-variant hover:bg-white/5'">
                            <span class="material-symbols-outlined text-[22px]">quiz</span>
                            <span class="text-[11px] leading-tight">{{ __('app.quizzes') }}</span>
                        </button>
                    @endif
                    <a href="{{ route('materials.index') }}"
                        class="flex flex-col items-center gap-1 px-3 py-3 rounded-xl border border-white/10 text-on-surface-variant hover:bg-white/5 transition-all font-medium text-center">
                        <span class="material-symbols-outlined text-[22px]">arrow_back</span>
                        <span class="text-[11px] leading-tight">{{ __('app.back_to_materials') }}</span>
                    </a>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        Alpine.data('flashcardSession', (cards, progress) => ({
            cards: cards,
            currentIndex: 0,
            flipped: false,
            progress: progress || {},

            get currentCard() {
                return this.cards[this.currentIndex];
            },

            get masteredCount() {
                return this.cards.filter(c => this.getCardStatus(c) === 'mastered').length;
            },

            get totalCount() {
                return this.cards.length;
            },

            get isComplete() {
                return this.totalCount > 0 && this.masteredCount >= this.totalCount;
            },

            get percentComplete() {
                return this.totalCount > 0 ? (this.masteredCount / this.totalCount) * 100 : 0;
            },

            getCardStatus(card) {
                return this.progress['flashcard_' + card.id]?.status || null;
            },

            flipCard() {
                if (!this.isComplete) this.flipped = !this.flipped;
            },

            markMastered() {
                const card = this.currentCard;
                this.progress['flashcard_' + card.id] = { status: 'mastered', score: null };
                this.flipped = false;
                this.saveProgress(card.id, 'mastered');
                this.advanceToNext();
            },

            markReview() {
                const card = this.currentCard;
                this.progress['flashcard_' + card.id] = { status: 'reviewing', score: null };
                this.flipped = false;
                this.saveProgress(card.id, 'reviewing');
                this.advanceToNext();
            },

            advanceToNext() {
                for (let i = 0; i < this.cards.length; i++) {
                    const idx = (this.currentIndex + 1 + i) % this.cards.length;
                    if (this.getCardStatus(this.cards[idx]) !== 'mastered') {
                        this.currentIndex = idx;
                        return;
                    }
                }
                this.currentIndex = 0;
            },

            restartSession() {
                this.progress = {};
                this.currentIndex = 0;
                this.flipped = false;
            },

            saveProgress(itemId, status) {
                fetch('/progress', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        content_id: {{ $content->id }},
                        type: 'flashcard',
                        item_id: itemId,
                        status: status,
                    }),
                });
            },
        }));

        Alpine.data('quizSession', (questions, progress) => ({
            questions: questions.map(q => ({
                id: q.id,
                question: q.question,
                options: q.options,
                correct: parseInt(q.correct_answer),
                selected: null,
                submitted: false,
                score: (progress && progress['quiz_' + q.id]?.score) || 0,
                points: 10,
            })),

            batchSubmitted: false,

            get totalEarned() {
                return this.questions.reduce((sum, q) => sum + q.score, 0);
            },

            get totalPossible() {
                return this.questions.length * 10;
            },

            get isComplete() {
                return this.batchSubmitted && this.questions.every(q => q.submitted);
            },

            get allAnswered() {
                return this.questions.every(q => q.selected !== null);
            },

            selectAnswer(qi, oi) {
                if (!this.questions[qi].submitted) {
                    this.questions[qi].selected = oi;
                }
            },

            submitAll() {
                if (!this.allAnswered || this.batchSubmitted) return;

                const answers = this.questions.map(q => ({
                    item_id: q.id,
                    selected: q.selected,
                    correct: q.correct,
                }));

                fetch('/progress/quiz/batch', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        content_id: {{ $content->id }},
                        answers: answers,
                    }),
                })
                .then(res => res.json())
                .then(data => {
                    if (data.ok) {
                        this.batchSubmitted = true;
                        data.results.forEach(r => {
                            const q = this.questions.find(q => q.id === r.item_id);
                            if (q) {
                                q.submitted = true;
                                q.score = r.score;
                            }
                        });
                    }
                });
            },

            submitSingleQuestion(qi) {
                const q = this.questions[qi];
                if (q.selected === null || q.submitted) return;
                q.submitted = true;
                if (q.selected === q.correct && q.score === 0) {
                    q.score = q.points;
                    this.saveProgress(q.id, 'passed', q.points);
                } else if (q.selected !== q.correct && q.score === 0) {
                    this.saveProgress(q.id, 'failed', 0);
                }
            },

            resetQuestion(qi) {
                const q = this.questions[qi];
                if (q.selected !== q.correct) {
                    q.selected = null;
                    q.submitted = false;
                }
            },

            saveProgress(itemId, status, score) {
                fetch('/progress', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        content_id: {{ $content->id }},
                        type: 'quiz',
                        item_id: itemId,
                        status: status,
                        score: score,
                    }),
                });
            },
        }));
    });
</script>
@endpush
