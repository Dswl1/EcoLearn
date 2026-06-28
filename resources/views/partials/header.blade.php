<header
    class="fixed top-0 left-0 right-0 z-20 flex items-center gap-3 px-container-padding-mobile md:px-container-padding-desktop py-2 bg-surface/60 backdrop-blur-xl border-b border-white/10 shadow-[0_0_10px_rgba(0,219,231,0.1)]"
    :class="sidebarOpen ? 'md:left-64' : 'md:left-0'">

    <button @click="sidebarOpen = !sidebarOpen" class="text-on-surface-variant hover:text-primary p-1 transition-colors">
        <span x-show="!sidebarOpen" class="material-symbols-outlined text-2xl">menu</span>
        <span x-show="sidebarOpen" class="material-symbols-outlined text-2xl">menu_open</span>
    </button>

    <div class="flex-1 max-w-lg mx-auto">
        <form method="GET" action="{{ route('materials.index') }}" class="relative group">
            <span
                class="absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors material-symbols-outlined text-lg">search</span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('app.search_placeholder') }}"
                class="w-full bg-white/5 border border-white/10 rounded-xl py-2 pl-10 pr-4 text-sm text-on-surface placeholder-on-surface-variant/50 font-body-md focus:outline-none focus:border-primary-container/50 focus:bg-white/10 transition-all duration-300 group-hover:border-white/20">
        </form>
    </div>

    <div x-data="{ langOpen: false }" class="relative">
        <button @click="langOpen = !langOpen"
            class="flex items-center gap-2 text-on-surface-variant hover:text-primary-container px-3 py-2 hover:bg-white/5 transition-all duration-300 rounded-lg font-label-sm text-label-sm uppercase tracking-wider">
            <span class="material-symbols-outlined text-lg">translate</span>
            <span class="hidden sm:inline">{{ app()->getLocale() === 'id' ? 'ID' : 'EN' }}</span>
        </button>
        <div x-show="langOpen" x-cloak @click.outside="langOpen = false"
            class="absolute right-0 top-full mt-2 w-32 bg-surface/95 backdrop-blur-xl border border-white/10 rounded-xl shadow-2xl z-50 p-2">
            <button @click="window.location.href='/lang/id'"
                class="w-full text-left px-4 py-3 rounded-lg text-sm transition {{ app()->getLocale() === 'id' ? 'text-primary-container bg-primary-container/10' : 'text-on-surface-variant hover:bg-white/10' }}">
                {{ __('app.indonesian') }}
            </button>
            <button @click="window.location.href='/lang/en'"
                class="w-full text-left px-4 py-3 rounded-lg text-sm transition {{ app()->getLocale() === 'en' ? 'text-primary-container bg-primary-container/10' : 'text-on-surface-variant hover:bg-white/10' }}">
                {{ __('app.english') }}
            </button>
        </div>
    </div>

    @auth
    <div x-data="{ notifOpen: false }" class="relative">
        <button @click="notifOpen = !notifOpen"
            class="relative p-3 text-on-surface-variant hover:text-primary-container hover:bg-white/5 transition-all duration-300 rounded-lg">
            <span class="material-symbols-outlined">notifications</span>
            @php $unreadCount = Auth::user()->unreadNotifications->count(); @endphp
            @if ($unreadCount > 0)
                <span class="absolute top-1.5 right-1.5 w-4 h-4 bg-error text-white text-[9px] font-bold rounded-full flex items-center justify-center">{{ min($unreadCount, 99) }}</span>
            @endif
        </button>
        <div x-show="notifOpen" x-cloak @click.outside="notifOpen = false"
            class="absolute right-0 top-full mt-2 w-72 sm:w-80 bg-surface/95 backdrop-blur-xl border border-white/10 rounded-xl shadow-2xl z-50 max-h-96 overflow-y-auto">
            <div class="p-3 border-b border-white/5 flex items-center justify-between">
                <span class="text-label-sm font-bold text-on-surface">{{ __('app.notifications') }}</span>
                @if ($unreadCount > 0)
                    <form method="POST" action="{{ route('notifications.read-all') }}">
                        @csrf
                        <button type="submit" class="text-[10px] text-primary-container hover:underline">{{ __('app.mark_all_read') }}</button>
                    </form>
                @endif
            </div>
            @php $notifications = Auth::user()->notifications()->latest()->take(10)->get(); @endphp
            @forelse ($notifications as $notification)
                @php
                    $notifType = $notification->data['type'] ?? 'content_deleted';
                    $isReview = $notifType === 'content_submitted';
                    $icon = $isReview ? 'rate_review' : 'delete_forever';
                    $iconColor = $isReview ? 'text-blue-400' : 'text-error';
                    $message = app()->getLocale() === 'id'
                        ? ($notification->data['message_id'] ?? '')
                        : ($notification->data['message_en'] ?? '');
                @endphp
                <div class="p-3 border-b border-white/5 hover:bg-white/5 transition {{ $notification->read_at ? '' : 'bg-primary-container/5' }}">
                    <div class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-[16px] mt-0.5 {{ $iconColor }}">{{ $icon }}</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-on-surface leading-relaxed">{{ $message }}</p>
                            <p class="text-[10px] text-on-surface-variant/60 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                        @if (!$notification->read_at)
                            <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                @csrf
                                <button type="submit" class="text-[10px] text-primary-container hover:underline mt-0.5">{{ __('app.read') }}</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-on-surface-variant/60">
                    <span class="material-symbols-outlined text-2xl block mb-1">notifications_off</span>
                    <p class="text-xs">{{ __('app.no_notifications') }}</p>
                </div>
            @endforelse
        </div>
    </div>
    @endauth

    <div x-data="{ open: false }" class="relative">
        @auth
            <button @click="open = !open" @click.outside="open = false"
                class="flex items-center gap-3 text-on-surface-variant p-3 hover:bg-white/5 transition-all duration-300 rounded-lg font-label-sm text-label-sm">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">person</span>
                <span class="hidden sm:inline">{{ Auth::user()->name }}</span>
                <span class="material-symbols-outlined text-[18px] transition-transform"
                    :class="{ 'rotate-180': open }">expand_more</span>
            </button>

            <div x-show="open" x-cloak @click.outside="open = false"
                class="absolute right-0 top-full mt-2 w-52 bg-surface/95 backdrop-blur-xl border border-white/10 rounded-xl shadow-2xl z-50 p-2">
                <a href="{{ route('profile.show') }}"
                    class="block px-4 py-3 rounded-lg text-sm text-on-surface-variant hover:bg-white/10 transition">
                    {{ __('app.profile') }}
                </a>
                <a href="{{ route('profile.edit') }}"
                    class="block px-4 py-3 rounded-lg text-sm text-on-surface-variant hover:bg-white/10 transition">
                    {{ __('app.profile_information') }}
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                    class="w-full text-left px-4 py-3 rounded-lg text-sm text-on-surface-variant hover:bg-white/10 transition">
                    {{ __('app.logout') }}
                    </button>
                </form>
            </div>
        @else
            <button class="flex items-center gap-3 text-on-surface-variant p-3 rounded-lg font-label-sm text-label-sm opacity-50">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">person</span>
                <span class="hidden sm:inline">{{ __('app.guest') }}</span>
            </button>
        @endauth
    </div>
</header>