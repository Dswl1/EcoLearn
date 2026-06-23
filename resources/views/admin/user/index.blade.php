@extends('app')
@section('title')
    {{ __('app.user_management') }} | {{ __('app.app_name') }}
@endsection

@section('content')
    <section class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 sm:gap-6">
        <div class="w-full sm:w-auto">
            <h3 class="font-headline-md text-headline-md text-primary-container mb-2">{{ __('app.user_management') }}</h3>
            <p class="text-on-surface-variant max-w-xl text-sm sm:text-base">{{ __('app.user_management_desc') }}</p>
        </div>
        <a href="{{ route('admin.user.create') }}"
            class="w-full sm:w-auto px-6 sm:px-8 py-3 sm:py-4 rounded-xl bg-primary-container text-on-primary font-bold font-headline-md text-headline-sm active:scale-[0.98] transition-transform flex items-center justify-center gap-3">
            <span class="material-symbols-outlined">person_add</span>
            {{ __('app.create_user') }}
        </a>
    </section>

    <form method="GET" action="{{ route('admin.user.index') }}"
        class="glass-card-static rounded-2xl p-3 md:p-4 mt-5 flex flex-col md:flex-row gap-3 items-end">
        <div class="flex-1 w-full md:w-auto">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="{{ __('app.search_users') }}"
                class="w-full bg-surface-container-high border border-outline-variant/20 rounded-lg px-4 py-2.5 text-sm text-on-surface placeholder:text-on-surface-variant/50 focus:outline-none focus:border-primary-container"
                autocomplete="off" />
        </div>
        <div class="flex flex-wrap gap-2 w-full md:w-auto">
            <select name="is_admin"
                class="bg-surface-container-high border border-outline-variant/20 rounded-lg px-3 py-2.5 text-sm text-on-surface focus:outline-none focus:border-primary-container">
                <option value="">{{ __('app.all_roles') }}</option>
                <option value="1" @selected(request('is_admin') === '1')>{{ __('app.admin') }}</option>
                <option value="0" @selected(request('is_admin') === '0')>{{ __('app.user') }}</option>
            </select>
            @if (request()->anyFilled(['search', 'is_admin']))
                <a href="{{ route('admin.user.index') }}"
                    class="px-3 py-2.5 border border-outline-variant/30 rounded-lg text-sm text-on-surface-variant hover:text-primary-container hover:border-primary-container transition-all flex items-center gap-1">
                    <span class="material-symbols-outlined text-lg">close</span>
                    {{ __('app.clear_filters') }}
                </a>
            @endif
        </div>
    </form>

    <div class="glass-card-static rounded-2xl overflow-hidden mt-5">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-white/5 text-on-surface-variant font-label-sm text-[11px] uppercase tracking-wider">
                        <th class="text-left px-4 md:px-6 py-4">{{ __('app.full_name') }}</th>
                        <th class="text-left px-4 md:px-6 py-4 hidden md:table-cell">{{ __('app.email') }}</th>
                        <th class="text-left px-4 md:px-6 py-4">{{ __('app.role') }}</th>
                        <th class="text-left px-4 md:px-6 py-4 hidden lg:table-cell">{{ __('app.joined') }}</th>
                        <th class="text-right px-4 md:px-6 py-4">{{ __('app.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                            <td class="px-4 md:px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-primary-container/20 flex items-center justify-center text-primary-container font-bold text-sm">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-on-surface">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 md:px-6 py-4 text-on-surface-variant hidden md:table-cell">{{ $user->email }}</td>
                            <td class="px-4 md:px-6 py-4">
                                @if ($user->is_admin)
                                    <span class="px-2.5 py-1 rounded-full bg-primary-container/10 border border-primary-container/30 text-primary-fixed text-[11px] font-label-sm">{{ __('app.admin') }}</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full bg-white/5 border border-white/10 text-on-surface-variant text-[11px] font-label-sm">{{ __('app.user') }}</span>
                                @endif
                            </td>
                            <td class="px-4 md:px-6 py-4 text-on-surface-variant hidden lg:table-cell">{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="px-4 md:px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.user.edit', $user) }}"
                                        class="p-2 rounded-lg hover:bg-primary-container/10 text-on-surface-variant hover:text-primary-container transition-all">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                    </a>
                                    @if (!$user->is(auth()->user()))
                                        <form method="POST" action="{{ route('admin.user.destroy', $user) }}"
                                            onsubmit="confirmAction(event, { title: '{{ __('app.are_you_sure_delete_user') }}' })">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="p-2 rounded-lg hover:bg-error/10 text-on-surface-variant hover:text-error transition-all">
                                                <span class="material-symbols-outlined text-lg">delete</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-on-surface-variant">
                                <span class="material-symbols-outlined text-4xl block mb-2">person_search</span>
                                {{ __('app.no_users_found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5">
        {{ $users->links() }}
    </div>
@endsection
