@extends('layouts.auth')
@section('title')
    {{ __('app.login_session') }} | {{ __('app.app_name') }}
@endsection
@section('auth')
    <!-- Logo -->
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
        <!-- Card -->
        <div class="glass-card rounded-xl p-8 md:p-12 relative overflow-hidden">

            <div class="relative z-10">

                <h2 class="font-headline-md text-white text-3xl font-bold mb-12 text-center">
                    {{ __('app.login_session') }}
                </h2>

                <form method="POST" action="{{ route('login') }}" class="space-y-8">
                    @csrf
                    <!-- Email -->
                    <div class="relative group">

                        <label
                            class="font-label-sm text-primary-container absolute -top-6 left-0 opacity-70 group-focus-within:opacity-100 transition-opacity">
                            {{ __('app.email') }}
                        </label>

                        <input
                            class="w-full bg-transparent border-0 border-b-2 border-white/10 py-4 font-body-md text-white placeholder:text-white/20 focus:ring-0 focus:outline-none input-glow transition-all duration-300"
                            name="email" type="email" value="{{ old('email') }}" placeholder="{{ __('app.email_placeholder') }}"
                            required autocomplete="off">
                        <div class="absolute right-0 bottom-4 text-primary-container/40">
                            <span class="material-symbols-outlined">alternate_email</span>
                        </div>

                        @error('email')
                            <p class="mt-2 text-sm text-red-400">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    <!-- Password -->
                    <div class="relative group">

                        <label
                            class="font-label-sm text-primary-container absolute -top-6 left-0 opacity-70 group-focus-within:opacity-100 transition-opacity">
                            {{ __('app.password') }}
                        </label>

                        <input
                            class="w-full bg-transparent border-0 border-b-2 border-white/10 py-4 font-body-lg text-white placeholder:text-white/20 focus:ring-0 focus:outline-none input-glow transition-all duration-300"
                            name="password" type="password" placeholder="{{ __('app.password_placeholder') }}" required>
                        <div class="absolute right-0 bottom-4 text-primary-container/40">
                            <span class="material-symbols-outlined">lock</span>
                        </div>

                        @error('password')
                            <p class="mt-2 text-sm text-red-400">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    <!-- Remember -->
                    <div class="flex items-center justify-between text-sm">

                        <label class="flex items-center gap-3 text-sm text-on-surface-variant">

                            <input type="checkbox" name="remember" class="rounded border-white/20 bg-transparent">

                            {{ __('app.remember_me') }}

                        </label>

                        <a href="{{ route('password.request') }}"
                            class="font-label-sm text-label-sm text-on-surface-variant/60 hover:text-primary transition-all duration-300 hover:drop-shadow-[0_0_8px_rgba(0,219,231,0.6)]">
                            {{ __('app.forgot_password') }}
                        </a>

                    </div>

                    <!-- Button -->
                    <button id="submit-btn"
                        class="neon-button w-full py-5 rounded-lg flex items-center justify-center gap-3 text-on-primary font-bold font-headline-md group"
                        type="submit">

                        <span>
                            {{ __('app.login_session') }}
                        </span>

                        <span class="material-symbols-outlined transition-transform group-hover:translate-x-1">
                            arrow_forward
                        </span>

                    </button>

                </form>

                <!-- Footer -->
                <div class="mt-8 text-center">

                    <a href="{{ route('register') }}"
                        class="inline-flex items-center gap-2 text-on-surface-variant hover:text-primary-container transition-colors font-label-sm uppercase tracking-widest">

                        <span class="material-symbols-outlined text-sm">
                            person_add
                        </span>

                        {{ __('app.create_account') }}

                    </a>

                </div>

            </div>

        </div>
    </div>
@endsection
