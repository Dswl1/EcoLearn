@extends('app')
@section('title')
    {{ __('app.my_submissions') }} | {{ __('app.app_name') }}
@endsection

@section('content')
    <section class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 sm:gap-6">
        <div class="w-full sm:w-auto">
            <h3 class="font-headline-md text-headline-md text-primary-container mb-2">{{ __('app.my_submissions') }}</h3>
            <p class="text-on-surface-variant max-w-xl text-sm sm:text-base">{{ __('app.submit_content') }}</p>
        </div>
        <a href="{{ route('user.content.create') }}"
            class="w-full sm:w-auto px-6 sm:px-8 py-3 sm:py-4 rounded-xl bg-primary-container text-on-primary font-bold font-headline-md text-headline-sm neon-glow active:scale-[0.98] transition-transform flex items-center justify-center gap-3">
            <span class="material-symbols-outlined">add_circle</span>
            {{ __('app.create_submission') }}
        </a>
    </section>

    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter mt-5">
        @forelse ($contents as $content)
            <div class="glass-card rounded-2xl overflow-hidden group">
                <div class="h-48 relative overflow-hidden">
                    @if ($content->thumbnail)
                        <img alt="{{ $content->title }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                            src="{{ Storage::url($content->thumbnail) }}" />
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-primary-container/20 to-surface-container-low flex items-center justify-center">
                            <span class="material-symbols-outlined text-6xl text-primary-container/40">auto_stories</span>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-surface to-transparent opacity-60"></div>
                    @if ($content->sdg_category)
                        <span class="absolute top-4 left-4 px-3 py-1 bg-primary-container/20 backdrop-blur-md border border-primary-container/30 rounded-full text-primary-container font-label-sm text-[10px]">{{ $content->sdg_category }}</span>
                    @endif
                    <span class="absolute top-4 right-4 px-3 py-1 backdrop-blur-md border rounded-full font-label-sm text-[10px] flex items-center gap-1
                        @if ($content->status === 'published') bg-green-500/20 border-green-500/30 text-green-400
                        @elseif ($content->status === 'pending_review') bg-blue-500/20 border-blue-500/30 text-blue-400
                        @elseif ($content->status === 'rejected') bg-error/20 border-error/30 text-error
                        @else bg-yellow-500/20 border-yellow-500/30 text-yellow-400 @endif">
                        @if ($content->status === 'published')
                            <span class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></span>
                        @else
                            <span class="w-1.5 h-1.5 rounded-full"></span>
                        @endif
                        {{ strtoupper(__("app.{$content->status}")) }}
                    </span>
                </div>
                <div class="p-4 sm:p-6">
                    <h4 class="font-headline-md text-headline-md text-on-surface mb-2 text-base sm:text-lg">{{ $content->title }}</h4>
                    <p class="text-on-surface-variant text-xs sm:text-sm mb-2 line-clamp-2">{{ $content->description ?? __('app.no_description') }}</p>

                    <div class="flex items-center gap-3 text-xs text-on-surface-variant mb-4">
                        <span class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">style</span>
                            {{ $content->flashcards_count }} {{ __('app.cards') }}
                        </span>
                        <span class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">quiz</span>
                            {{ $content->quizzes_count }} {{ __('app.questions') }}
                        </span>
                    </div>

                    @if ($content->status === 'rejected' && $content->rejection_reason)
                        <div class="mb-4 p-3 rounded-lg bg-error/5 border border-error/20">
                            <p class="text-[10px] font-bold text-error uppercase tracking-wider mb-1">{{ __('app.rejection_reason_label') }}</p>
                            <p class="text-xs text-on-surface-variant">{{ $content->rejection_reason }}</p>
                        </div>
                    @endif

                    <div class="flex flex-wrap items-center gap-2 pt-4 border-t border-white/5">
                        @if ($content->status === 'rejected')
                            <a href="{{ route('user.content.edit', $content) }}"
                                class="px-4 py-2 bg-primary-container/10 border border-primary-container/30 text-primary-fixed rounded-full hover:bg-primary-container/20 transition-all text-xs font-medium">
                                {{ __('app.resubmit') }}
                            </a>
                        @elseif ($content->status === 'draft')
                            <form method="POST" action="{{ route('user.content.submit', $content) }}">
                                @csrf
                                <button type="submit"
                                    class="px-4 py-2 bg-primary-container/10 border border-primary-container/30 text-primary-fixed rounded-full hover:bg-primary-container/20 transition-all text-xs font-medium">
                                    {{ __('app.submit_for_review') }}
                                </button>
                            </form>
                            <a href="{{ route('user.content.edit', $content) }}"
                                class="px-4 py-2 border border-white/10 text-on-surface-variant rounded-full hover:border-white/30 transition-all text-xs">
                                {{ __('app.edit') }}
                            </a>
                        @elseif ($content->status === 'published')
                            <a href="{{ route('materials.show', $content) }}"
                                class="px-4 py-2 border border-green-500/30 text-green-400 rounded-full hover:bg-green-500/10 transition-all text-xs">
                                {{ __('app.view') }}
                            </a>
                        @else
                            <span class="text-xs text-on-surface-variant">{{ __('app.cannot_edit_while_pending') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full glass-card-static rounded-2xl p-12 text-center">
                <span class="material-symbols-outlined text-6xl text-on-surface-variant/30 mb-4">note_add</span>
                <h3 class="font-headline-md text-headline-md text-on-surface mb-2">{{ __('app.no_submissions_yet') }}</h3>
                <p class="text-on-surface-variant mb-6">{{ __('app.create_your_first') }}</p>
                <a href="{{ route('user.content.create') }}"
                    class="px-6 py-3 rounded-xl bg-primary-container text-on-primary font-bold inline-flex items-center gap-2">
                    <span class="material-symbols-outlined">add_circle</span>
                    {{ __('app.create_submission') }}
                </a>
            </div>
        @endforelse
    </section>

    <div class="flex flex-col items-center pt-10 pb-20">
        <p class="text-on-surface-variant/60 font-label-sm text-[12px] mb-4">
            {{ __('app.showing_records', ['from' => $contents->firstItem() ?? 0, 'to' => $contents->lastItem() ?? 0, 'total' => $contents->total()]) }}
        </p>
        {{ $contents->links() }}
    </div>
@endsection
