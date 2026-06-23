@extends('app')
@section('title')
    {{ __('app.edit_user') }} | {{ __('app.app_name') }}
@endsection

@section('content')
    <section class="mb-6">
        <nav class="flex gap-2 text-label-sm font-label-sm text-on-surface-variant mb-2">
            <a class="hover:text-primary-fixed" href="{{ route('admin.user.index') }}">{{ __('app.user_management') }}</a>
            <span>/</span>
            <span class="text-primary-fixed">{{ __('app.edit_user') }}</span>
        </nav>
        <h3 class="font-headline-md text-headline-md text-primary-container">{{ __('app.edit_user') }}</h3>
    </section>

    <form method="POST" action="{{ route('admin.user.update', $user) }}"
        class="glass-card-static rounded-2xl p-4 md:p-8 max-w-2xl space-y-6">
        @csrf @method('PATCH')

        <div>
            <label class="block text-label-sm text-on-surface-variant mb-2">{{ __('app.full_name') }}</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                class="w-full bg-surface-container-high border border-outline-variant/20 rounded-lg px-4 py-3 text-sm text-on-surface placeholder:text-on-surface-variant/50 focus:outline-none focus:border-primary-container" />
            @error('name')
                <p class="text-error text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-label-sm text-on-surface-variant mb-2">{{ __('app.email') }}</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                class="w-full bg-surface-container-high border border-outline-variant/20 rounded-lg px-4 py-3 text-sm text-on-surface placeholder:text-on-surface-variant/50 focus:outline-none focus:border-primary-container" />
            @error('email')
                <p class="text-error text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-label-sm text-on-surface-variant mb-2">{{ __('app.password') }}</label>
            <input type="password" name="password"
                class="w-full bg-surface-container-high border border-outline-variant/20 rounded-lg px-4 py-3 text-sm text-on-surface placeholder:text-on-surface-variant/50 focus:outline-none focus:border-primary-container"
                placeholder="{{ __('app.leave_blank') }}" />
            @error('password')
                <p class="text-error text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-label-sm text-on-surface-variant mb-2">{{ __('app.confirm_password') }}</label>
            <input type="password" name="password_confirmation"
                class="w-full bg-surface-container-high border border-outline-variant/20 rounded-lg px-4 py-3 text-sm text-on-surface placeholder:text-on-surface-variant/50 focus:outline-none focus:border-primary-container"
                placeholder="{{ __('app.leave_blank') }}" />
        </div>

        <div class="flex items-center gap-3">
            <input type="checkbox" name="is_admin" value="1" {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}
                class="w-4 h-4 rounded border-outline-variant/30 bg-surface-container-high text-primary-container focus:ring-primary-container" />
            <label class="text-sm text-on-surface">{{ __('app.is_admin') }}</label>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit"
                class="px-8 py-3 rounded-xl bg-primary-container text-on-primary font-bold text-sm hover:brightness-110 transition-all">
                {{ __('app.update') }}
            </button>
            <a href="{{ route('admin.user.index') }}"
                class="px-8 py-3 rounded-xl border border-white/10 text-on-surface-variant hover:bg-white/5 transition-all text-sm">
                {{ __('app.cancel') }}
            </a>
        </div>
    </form>
@endsection
