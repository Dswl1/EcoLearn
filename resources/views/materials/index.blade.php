@extends('app')
@section('title')
    {{ __('app.browse_materials') }} | {{ __('app.app_name') }}
@endsection

@section('content')
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 md:mb-8 gap-4">
        <div>
            <h1 class="font-display-lg text-display-lg-mobile md:text-display-lg text-on-surface tracking-tight">{{ __('app.materials_explorer') }}</h1>
            <p class="text-on-surface-variant mt-1">{{ __('app.browse_materials_desc') }}</p>
        </div>
    </div>

    <div class="glass-card-static rounded-2xl p-4 md:p-6 mb-6 md:mb-8">
        <form method="GET" action="{{ route('materials.index') }}" id="filter-form" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1 uppercase tracking-wider text-[10px]">{{ __('app.search') }}</label>
                <input type="text" name="search" value="{{ request('search') }}"
                    class="neon-input w-full font-body-md py-2 text-sm"
                    placeholder="{{ __('app.search_materials') }}" />
            </div>
            <div class="w-full sm:w-auto">
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1 uppercase tracking-wider text-[10px]">{{ __('app.sdg_alignment') }}</label>
                <select name="sdg_category" onchange="this.form.submit()"
                    class="bg-surface-container-high border border-white/10 rounded-lg py-2 px-4 font-body-md text-sm text-on-surface">
                    <option value="">{{ __('app.all_categories') }}</option>
                    @foreach (config('sdg.categories') as $catKey => $category)
                        <optgroup label="{{ __("app.category_{$catKey}") }}">
                            @foreach ($category['sdgs'] as $num)
                                <option value="SDG {{ $num }}" @selected(request('sdg_category') === 'SDG '.$num)>{{ __("app.sdg_{$num}") }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>
            <div class="w-full sm:w-auto">
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1 uppercase tracking-wider text-[10px]">{{ __('app.content_difficulty') }}</label>
                <select name="difficulty" onchange="this.form.submit()"
                    class="bg-surface-container-high border border-white/10 rounded-lg py-2 px-4 font-body-md text-sm text-on-surface">
                    <option value="">{{ __('app.all_difficulties_short') }}</option>
                    <option value="basic" @selected(request('difficulty') === 'basic')>{{ __('app.basic') }}</option>
                    <option value="core" @selected(request('difficulty') === 'core')>{{ __('app.core') }}</option>
                    <option value="expert" @selected(request('difficulty') === 'expert')>{{ __('app.expert') }}</option>
                </select>
            </div>
            @if (request()->anyFilled(['search', 'sdg_category', 'difficulty']))
                <div class="w-full sm:w-auto flex items-end">
                    <a href="{{ route('materials.index') }}"
                        class="px-4 py-2 text-sm border border-error/30 text-error rounded-lg hover:bg-error/10 transition-all">
                        {{ __('app.clear_filters') }}
                    </a>
                </div>
            @endif
        </form>
    </div>

    @if ($materials->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($materials as $material)
                <a href="{{ route('materials.show', $material) }}"
                    class="glass-card rounded-2xl overflow-hidden group hover:border-primary-container/40 transition-all duration-300 flex flex-col">
                    @if ($material->thumbnail)
                        <div class="aspect-video overflow-hidden">
                            <img src="{{ Storage::url($material->thumbnail) }}"
                                alt="{{ $material->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                        </div>
                    @else
                        <div class="aspect-video bg-surface-container-high flex items-center justify-center">
                            <span class="material-symbols-outlined text-4xl text-on-surface-variant/30">image</span>
                        </div>
                    @endif
                    <div class="p-5 flex flex-col flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            @if ($material->sdg_category)
                                <span class="text-[10px] font-label-sm px-2 py-0.5 rounded-full bg-primary-container/10 text-primary-container border border-primary-container/20">{{ $material->sdg_category }}</span>
                            @endif
                            @if ($material->difficulty)
                                <span class="text-[10px] font-label-sm px-2 py-0.5 rounded-full bg-surface-container-high text-on-surface-variant uppercase">{{ __("app.{$material->difficulty}") }}</span>
                            @endif
                        </div>
                        <h3 class="font-headline-md text-headline-md text-on-surface group-hover:text-primary-container transition-colors mb-1">{{ $material->title }}</h3>
                        <p class="text-label-sm text-on-surface-variant/70 line-clamp-2 mb-3 flex-1">
                            {{ $material->description ?? __('app.no_description') }}
                        </p>
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] text-on-surface-variant/50">{{ $material->user->name }}</span>
                            <span class="text-[10px] text-on-surface-variant/50">{{ $material->published_at?->diffForHumans() }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $materials->links() }}
        </div>
    @else
        <div class="glass-card-static rounded-2xl p-8 md:p-12 text-center">
            <span class="material-symbols-outlined text-5xl text-on-surface-variant/20 mb-4">inventory_2</span>
            <h3 class="font-headline-md text-headline-md text-on-surface-variant mb-2">{{ request()->anyFilled(['search', 'sdg_category', 'difficulty']) ? __('app.no_results') : __('app.no_published_materials') }}</h3>
            <p class="text-label-sm text-on-surface-variant/60">{{ request()->anyFilled(['search', 'sdg_category', 'difficulty']) ? __('app.try_adjust_search') : __('app.check_back_later') }}</p>
        </div>
    @endif
@endsection
