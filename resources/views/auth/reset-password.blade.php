@extends('layouts.auth')
@section('title')
    {{ __('app.reset_password') }} | {{ __('app.app_name') }}
@endsection
@section('auth')
    <div class="min-w-xl w-[400px]">

        <div class="mb-2 text-center">
            <div class="inline-flex items-center gap-2 mb-2">
                <span class="material-symbols-outlined text-primary-container text-4xl"
                    style="font-variation-settings: 'FILL' 1;">
                    public
                </span>
                <h1 class="font-display-lg text-primary-container tracking-tighter uppercase text-xl font-bold">
                    {{ __('app.app_name') }}
                </h1>
            </div>
        </div>

        <div class="glass-card rounded-xl p-8 md:p-12 relative overflow-hidden">
            <div class="relative z-10">

                <h2 class="font-headline-md text-white text-3xl font-bold mb-4 text-center">
                    {{ __('app.reset_password') }}
                </h2>
                <p class="text-center text-on-surface-variant text-sm mb-10">
                    {{ __('app.confirm_password_desc') }}
                </p>

                <form method="POST" action="{{ route('password.store') }}" class="space-y-8">
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div class="relative group">
                        <label class="font-label-sm text-primary-container absolute -top-6 left-0 opacity-70 group-focus-within:opacity-100 transition-opacity">
                            {{ __('app.email') }}
                        </label>
                        <input
                            class="w-full bg-transparent border-0 border-b-2 border-white/10 py-4 font-body-md text-white placeholder:text-white/20 focus:ring-0 focus:outline-none input-glow transition-all duration-300"
                            id="email" type="email" name="email" value="{{ old('email', $request->email) }}"
                            placeholder="{{ __('app.email_placeholder') }}" required autofocus autocomplete="username">
                        <div class="absolute right-0 bottom-4 text-primary-container/40">
                            <span class="material-symbols-outlined">alternate_email</span>
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="relative group">
                        <label class="font-label-sm text-primary-container absolute -top-6 left-0 opacity-70 group-focus-within:opacity-100 transition-opacity">
                            {{ __('app.password') }}
                        </label>
                        <input
                            class="w-full bg-transparent border-0 border-b-2 border-white/10 py-4 font-body-md text-white placeholder:text-white/20 focus:ring-0 focus:outline-none input-glow transition-all duration-300"
                            id="password" type="password" name="password" placeholder="{{ __('app.password_placeholder') }}" required autocomplete="new-password">
                        <div class="absolute right-0 bottom-4 text-primary-container/40">
                            <span class="material-symbols-outlined">lock</span>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="relative group">
                        <label class="font-label-sm text-primary-container absolute -top-6 left-0 opacity-70 group-focus-within:opacity-100 transition-opacity">
                            {{ __('app.confirm_password') }}
                        </label>
                        <input
                            class="w-full bg-transparent border-0 border-b-2 border-white/10 py-4 font-body-md text-white placeholder:text-white/20 focus:ring-0 focus:outline-none input-glow transition-all duration-300"
                            id="password_confirmation" type="password" name="password_confirmation"
                            placeholder="{{ __('app.password_placeholder') }}" required autocomplete="new-password">
                        <div class="absolute right-0 bottom-4 text-primary-container/40">
                            <span class="material-symbols-outlined">verified_user</span>
                        </div>
                        @error('password_confirmation')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="neon-button w-full py-5 rounded-lg flex items-center justify-center gap-3 text-on-primary font-bold font-headline-md group">
                        <span>{{ __('app.reset_password') }}</span>
                        <span class="material-symbols-outlined transition-transform group-hover:translate-x-1">arrow_forward</span>
                    </button>

                </form>

                <div class="mt-8 text-center">
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center gap-2 text-on-surface-variant hover:text-primary-container transition-colors font-label-sm uppercase tracking-widest">
                        <span class="material-symbols-outlined text-sm">arrow_back</span>
                        {{ __('app.back_to_login') }}
                    </a>
                </div>

            </div>
        </div>
    </div>
@endsection