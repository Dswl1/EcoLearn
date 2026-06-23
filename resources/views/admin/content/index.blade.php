@extends('app')
@section('title')
    {{ __('app.material_management') }} | {{ __('app.app_name') }}
@endsection

@section('content')
    <section class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 sm:gap-6">
        <div class="w-full sm:w-auto">
            <h3 class="font-headline-md text-headline-md text-primary-container mb-2">{{ __('app.material_management') }}</h3>
            <p class="text-on-surface-variant max-w-xl text-sm sm:text-base">{{ __('app.material_desc') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.content.index', ['status' => 'pending_review']) }}"
                class="px-4 sm:px-6 py-3 sm:py-4 rounded-xl bg-blue-500/10 border border-blue-500/30 text-blue-400 font-bold font-headline-md text-headline-sm hover:bg-blue-500/20 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined">rate_review</span>
                {{ __('app.pending_reviews') }}
            </a>
            <a href="{{ route('admin.content.create') }}"
                class="w-full sm:w-auto px-6 sm:px-8 py-3 sm:py-4 rounded-xl bg-primary-container text-on-primary font-bold font-headline-md text-headline-sm neon-glow active:scale-[0.98] transition-transform flex items-center justify-center gap-3">
                <span class="material-symbols-outlined">add_circle</span>
                {{ __('app.create_material') }}
            </a>
        </div>
    </section>

    <form method="GET" action="{{ route('admin.content.index') }}" id="filter-form"
        class="glass-card-static rounded-2xl p-3 md:p-4 mt-5 flex flex-col md:flex-row gap-3 items-end">
        <div class="flex-1 w-full md:w-auto">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="{{ __('app.search_materials') }}"
                class="w-full bg-surface-container-high border border-outline-variant/20 rounded-lg px-4 py-2.5 text-sm text-on-surface placeholder:text-on-surface-variant/50 focus:outline-none focus:border-primary-container"
                autocomplete="off" />
        </div>
        <div class="flex flex-wrap gap-2 w-full md:w-auto">
            <select name="status"
                class="bg-surface-container-high border border-outline-variant/20 rounded-lg px-3 py-2.5 text-sm text-on-surface focus:outline-none focus:border-primary-container">
                <option value="">{{ __('app.all_statuses') }}</option>
                <option value="draft" @selected(request('status') === 'draft')>{{ __('app.draft') }}</option>
                <option value="published" @selected(request('status') === 'published')>{{ __('app.published') }}</option>
                <option value="pending_review" @selected(request('status') === 'pending_review')>{{ __('app.pending_review') }}</option>
                <option value="rejected" @selected(request('status') === 'rejected')>{{ __('app.rejected') }}</option>
            </select>
            <select name="difficulty"
                class="bg-surface-container-high border border-outline-variant/20 rounded-lg px-3 py-2.5 text-sm text-on-surface focus:outline-none focus:border-primary-container">
                <option value="">{{ __('app.all_difficulties') }}</option>
                <option value="basic" @selected(request('difficulty') === 'basic')>{{ __('app.basic') }}</option>
                <option value="core" @selected(request('difficulty') === 'core')>{{ __('app.core') }}</option>
                <option value="expert" @selected(request('difficulty') === 'expert')>{{ __('app.expert') }}</option>
            </select>
            <select name="sdg_category"
                class="bg-surface-container-high border border-outline-variant/20 rounded-lg px-3 py-2.5 text-sm text-on-surface focus:outline-none focus:border-primary-container">
                <option value="">{{ __('app.all_categories') }}</option>
                @foreach (config('sdg.categories') as $catKey => $category)
                    <optgroup label="{{ __("app.category_{$catKey}") }}">
                        @foreach ($category['sdgs'] as $num)
                            <option value="SDG {{ $num }}" @selected(request('sdg_category') === 'SDG '.$num)>SDG {{ $num }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
            <select name="sort"
                class="bg-surface-container-high border border-outline-variant/20 rounded-lg px-3 py-2.5 text-sm text-on-surface focus:outline-none focus:border-primary-container">
                <option value="created_at-desc" @selected(request('sort', 'created_at') === 'created_at' && request('direction', 'desc') === 'desc')>{{ __('app.sort_newest') }}</option>
                <option value="created_at-asc" @selected(request('sort') === 'created_at' && request('direction') === 'asc')>{{ __('app.sort_oldest') }}</option>
                <option value="title-asc" @selected(request('sort') === 'title' && request('direction', 'desc') === 'asc')>{{ __('app.sort_title_asc') }}</option>
                <option value="title-desc" @selected(request('sort') === 'title' && request('direction') === 'desc')>{{ __('app.sort_title_desc') }}</option>
                <option value="status-asc" @selected(request('sort') === 'status' && request('direction') === 'asc')>{{ __('app.sort_status') }} ↑</option>
                <option value="status-desc" @selected(request('sort') === 'status' && request('direction') === 'desc')>{{ __('app.sort_status') }} ↓</option>
                <option value="difficulty-asc" @selected(request('sort') === 'difficulty' && request('direction') === 'asc')>{{ __('app.sort_difficulty') }} ↑</option>
                <option value="difficulty-desc" @selected(request('sort') === 'difficulty' && request('direction') === 'desc')>{{ __('app.sort_difficulty') }} ↓</option>
            </select>
            @if (request()->anyFilled(['search', 'status', 'difficulty', 'sdg_category']) || request('sort') !== 'created_at' || request('direction') !== 'desc')
                <a href="{{ route('admin.content.index') }}"
                    class="px-3 py-2.5 border border-outline-variant/30 rounded-lg text-sm text-on-surface-variant hover:text-primary-container hover:border-primary-container transition-all flex items-center gap-1">
                    <span class="material-symbols-outlined text-lg">close</span>
                    {{ __('app.clear_filters') }}
                </a>
            @endif
        </div>
    </form>

    <div id="content-results" class="opacity-100 transition-opacity duration-300">
        <section id="content-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter mt-5">
            @forelse ($contents as $content)
                <div class="glass-card rounded-2xl overflow-hidden group">
                    <div class="h-48 relative overflow-hidden">
                        @if ($content->thumbnail)
                            <img alt="{{ $content->title }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                src="{{ Storage::url($content->thumbnail) }}" />
                        @else
                            <div
                                class="w-full h-full bg-gradient-to-br from-primary-container/20 to-surface-container-low flex items-center justify-center">
                                <span class="material-symbols-outlined text-6xl text-primary-container/40">auto_stories</span>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-surface to-transparent opacity-60"></div>
                        @if ($content->sdg_category)
                            <span
                                class="absolute top-4 left-4 px-3 py-1 bg-primary-container/20 backdrop-blur-md border border-primary-container/30 rounded-full text-primary-container font-label-sm text-[10px]">{{ $content->sdg_category }}</span>
                        @endif
                        <span
                            class="absolute top-4 right-4 px-3 py-1 backdrop-blur-md border rounded-full font-label-sm text-[10px] flex items-center gap-1
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
                        <p class="text-on-surface-variant text-xs sm:text-sm mb-4 sm:mb-6 line-clamp-2">{{ $content->description ?? __('app.no_description') }}</p>
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 sm:gap-0 pt-4 border-t border-white/5">
                            <span class="text-[10px] font-label-sm text-on-surface-variant/60 order-2 sm:order-1">{{ strtoupper(__('app.updated', ['time' => $content->updated_at->diffForHumans(null, true)])) }}</span>
                            <div class="flex gap-2 order-1 sm:order-2 self-end sm:self-auto flex-wrap justify-end">
                                @if ($content->status === 'pending_review')
                                    <form method="POST" action="{{ route('admin.content.approve', $content) }}">
                                        @csrf
                                        <button type="submit"
                                            class="px-3 py-2 rounded-lg bg-green-500/10 border border-green-500/30 text-green-400 hover:bg-green-500/20 transition-all text-[11px] font-medium flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[14px]">check</span>
                                            {{ __('app.approve') }}
                                        </button>
                                    </form>
                                    <button type="button" onclick="showRejectModal({{ $content->id }})"
                                        class="px-3 py-2 rounded-lg bg-error/10 border border-error/30 text-error hover:bg-error/20 transition-all text-[11px] font-medium flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">close</span>
                                        {{ __('app.reject') }}
                                    </button>
                                @endif
                                <a href="{{ route('admin.content.show', $content) }}"
                                    class="w-9 h-9 rounded-full border border-white/10 flex items-center justify-center text-on-surface-variant hover:text-primary-container hover:border-primary-container transition-all">
                                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                                </a>
                                <a href="{{ route('admin.content.edit', $content) }}"
                                    class="w-9 h-9 rounded-full border border-white/10 flex items-center justify-center text-on-surface-variant hover:text-primary-container hover:border-primary-container transition-all">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </a>
                                <form method="POST" action="{{ route('admin.content.destroy', $content) }}"
                                    onsubmit="confirmAction(event, { title: '{{ __('app.are_you_sure_delete') }}' })">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-9 h-9 rounded-full border border-white/10 flex items-center justify-center text-on-surface-variant hover:text-error hover:border-error transition-all">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                @php $hasFilters = request()->anyFilled(['search', 'status', 'difficulty', 'sdg_category']); @endphp
                <div class="col-span-full glass-card-static rounded-2xl p-12 text-center">
                    @if ($hasFilters)
                        <span class="material-symbols-outlined text-6xl text-on-surface-variant/30 mb-4">search_off</span>
                        <h3 class="font-headline-md text-headline-md text-on-surface mb-2">{{ __('app.no_results') }}</h3>
                        <p class="text-on-surface-variant mb-6">{{ __('app.try_adjust_search') }}</p>
                    @else
                        <span class="material-symbols-outlined text-6xl text-on-surface-variant/30 mb-4">inventory_2</span>
                        <h3 class="font-headline-md text-headline-md text-on-surface mb-2">{{ __('app.no_materials') }}</h3>
                        <p class="text-on-surface-variant mb-6">{{ __('app.create_first_material') }}</p>
                        <a href="{{ route('admin.content.create') }}"
                            class="px-6 py-3 rounded-xl bg-primary-container text-on-primary font-bold inline-flex items-center gap-2">
                            <span class="material-symbols-outlined">add_circle</span>
                            {{ __('app.create_material') }}
                        </a>
                    @endif
                </div>
            @endforelse
        </section>

        <div id="content-footer" class="flex flex-col items-center pt-10 pb-20">
            <p class="text-on-surface-variant/60 font-label-sm text-[12px] mb-4">
                {{ __('app.showing_records', ['from' => $contents->firstItem() ?? 0, 'to' => $contents->lastItem() ?? 0, 'total' => $contents->total()]) }}
            </p>
            {{ $contents->links() }}
        </div>
    </div>

    <script>
        (function() {
            var form = document.getElementById('filter-form');
            var results = document.getElementById('content-results');
            var searchInput = form.querySelector('[name="search"]');
            var selects = form.querySelectorAll('select');
            var timer;

            function submitForm() {
                var params = new URLSearchParams(new FormData(form));
                var url = form.action + '?' + params.toString();

                results.classList.remove('opacity-100');
                results.classList.add('opacity-30');

                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(function(r) { return r.text(); })
                    .then(function(html) {
                        var doc = new DOMParser().parseFromString(html, 'text/html');
                        var newResults = doc.getElementById('content-results');
                        if (newResults) {
                            results.innerHTML = newResults.innerHTML;
                        }
                        history.replaceState(null, '', url);
                        results.classList.remove('opacity-30');
                        results.classList.add('opacity-100');
                    });
            }

            searchInput.addEventListener('input', function() {
                clearTimeout(timer);
                timer = setTimeout(submitForm, 500);
            });

            selects.forEach(function(sel) {
                sel.addEventListener('change', submitForm);
            });
        })();
    </script>

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
