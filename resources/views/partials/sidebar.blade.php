<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed top-0 left-0 h-full w-64 bg-surface/60 backdrop-blur-2xl border-r border-primary-container/20 shadow-2xl z-40 flex flex-col transition-transform duration-300">

    <div class="flex items-center gap-3 px-6 py-5 border-b border-white/5">
        <span class="material-symbols-outlined text-primary-container text-3xl"
            style="font-variation-settings: 'FILL' 1;">public</span>
        <span class="font-display-lg text-primary-container text-xl">{{ __('app.app_name') }}</span>
    </div>

    @php
        $isAdmin = Auth::user()?->is_admin;
    @endphp

    <nav class="flex-1 py-6 overflow-y-auto font-label-sm text-label-sm flex flex-col gap-1 px-3">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 p-4 transition-all duration-300 rounded-lg
            {{ request()->routeIs('dashboard') ? 'bg-primary-container/10 text-primary-container border-r-4 border-primary-container neon-shadow' : 'text-on-surface-variant hover:bg-white/5' }}">
                    <span class="material-symbols-outlined"
                        style="font-variation-settings: 'FILL' {{ request()->routeIs('dashboard') ? 1 : 0 }};">dashboard</span>
                    {{ __('app.dashboard') }}
                </a>

        <div x-data="{ materialsOpen: {{ request()->routeIs('materials*') || request()->routeIs('admin.content*') ? 'true' : 'false' }} }" class="flex flex-col">
            <button @click="materialsOpen = !materialsOpen"
                class="flex items-center gap-3 p-4 transition-all duration-300 rounded-lg w-full text-left
                        {{ request()->routeIs('materials*') || request()->routeIs('admin.content*') ? 'bg-primary-container/10 text-primary-container border-r-4 border-primary-container neon-shadow' : 'text-on-surface-variant hover:bg-white/5' }}">
                <span class="material-symbols-outlined"
                    style="font-variation-settings: 'FILL' {{ request()->routeIs('materials*') || request()->routeIs('admin.content*') ? 1 : 0 }};">inventory_2</span>
                <span class="flex-1">{{ __('app.materials') }}</span>
                <span class="material-symbols-outlined text-[18px] transition-transform duration-300"
                    :class="materialsOpen ? 'rotate-180' : ''">expand_more</span>
            </button>
            <div x-show="materialsOpen" x-transition.opacity.duration.200
                class="flex flex-col ml-4 border-l border-white/10">
                <a href="{{ route('materials.index') }}"
                    class="flex items-center gap-3 py-3 px-4 transition-all duration-300 rounded-lg mx-2
                            {{ request()->routeIs('materials*') && !request()->routeIs('admin.content*') ? 'text-primary-container' : 'text-on-surface-variant hover:text-on-surface' }}">
                    <span class="material-symbols-outlined text-[18px]">travel_explore</span>
                    <span class="text-[11px]">{{ __('app.browse_materials') }}</span>
                </a>
                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 py-3 px-4 transition-all duration-300 rounded-lg mx-2 text-on-surface-variant hover:text-on-surface">
                    <span class="material-symbols-outlined text-[18px]">school</span>
                    <span class="text-[11px]">{{ __('app.my_learning') }}</span>
                </a>
                @if (!$isAdmin)
                    <a href="{{ route('user.content.index') }}"
                        class="flex items-center gap-3 py-3 px-4 transition-all duration-300 rounded-lg mx-2
                                {{ request()->routeIs('user.content*') ? 'text-primary-container' : 'text-on-surface-variant hover:text-on-surface' }}">
                        <span class="material-symbols-outlined text-[18px]">note_add</span>
                        <span class="text-[11px]">{{ __('app.my_submissions') }}</span>
                    </a>
                @endif
                @if ($isAdmin)
                    <a href="{{ route('admin.content.index') }}"
                        class="flex items-center gap-3 py-3 px-4 transition-all duration-300 rounded-lg mx-2
                                {{ request()->routeIs('admin.content*') ? 'text-primary-container' : 'text-on-surface-variant hover:text-on-surface' }}">
                        <span class="material-symbols-outlined text-[18px]">manage_accounts</span>
                        <span class="text-[11px]">{{ __('app.manage_materials') }}</span>
                    </a>
                @endif
            </div>
        </div>

        @if ($isAdmin)
            <a href="{{ route('admin.user.index') }}"
                class="flex items-center gap-3 p-4 transition-all duration-300 rounded-lg
                        {{ request()->routeIs('admin.user*') ? 'bg-primary-container/10 text-primary-container border-r-4 border-primary-container neon-shadow' : 'text-on-surface-variant hover:bg-white/5' }}">
                <span class="material-symbols-outlined"
                    style="font-variation-settings: 'FILL' {{ request()->routeIs('admin.user*') ? 1 : 0 }};">group</span>
                {{ __('app.users') }}
            </a>
        @endif
    </nav>

    <div class="px-3 py-4 border-t border-white/5">
        <a href="{{ url('/') }}"
            class="flex items-center gap-2 p-4 transition-all duration-300 rounded-lg text-on-surface-variant hover:text-primary-container hover:bg-white/5"
            title="{{ __('app.back_to_home') }}">
            <span class="material-symbols-outlined">arrow_back</span>
            <span class="text-sm">{{ __('app.back_to_home') }}</span>
        </a>
    </div>
</aside>
