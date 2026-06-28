@extends('layouts.auth')
@section('title')
    {{ __('app.forgot_password_title') }} | {{ __('app.app_name') }}
@endsection

@section('auth')
    <!-- Branding Header -->
    <!-- Forgot Password Card -->
    <div class="glass-card rounded-xl p-8 md:p-12 relative overflow-hidden">
        <div class="mb-4 text-center space-y-2">
            <div class="inline-flex items-center gap-2 mb-4">
                <span class="material-symbols-outlined text-primary-container text-4xl"
                    style="font-variation-settings: 'FILL' 1;">public</span>
                <h1 class="font-display-lg text-primary-container tracking-tighter uppercase text-xl font-bold">{{ __('app.app_name') }}</h1>
            </div>
        </div>
        <div class="relative z-10">
            <h2 class="font-headline-md text-white text-3xl font-bold mb-4 drop-shadow-[0_0_10px_rgba(255,255,255,0.3)]">
                Lupa Password?
            </h2>
            <p class="font-body-md text-on-surface-variant mb-10 leading-relaxed">
                {{ __('app.forgot_password_desc') }}
            </p>
            <form method="POST" action="{{ route('password.email') }}" class="space-y-8">
                @csrf
                <!-- Email Input Group -->
                <div class="relative group">
                    <label
                        class="font-label-sm text-primary-container absolute -top-6 left-0 opacity-70 group-focus-within:opacity-100 transition-opacity"
                        for="email">
                        {{ __('app.email') }}
                    </label>

                    <input
                        class="w-full bg-transparent border-0 border-b-2 border-white/10 py-4 font-body-lg text-white placeholder:text-white/20 focus:ring-0 focus:outline-none input-glow transition-all duration-300"
                        id="email" name="email" placeholder="{{ __('app.email_placeholder') }}" required="" type="email"
                        value="{{ old('email') }}" />
                    @error('email')
                        <p class="mt-2 text-sm text-red-400">
                            {{ $message }}
                        </p>
                    @enderror
                    <div class="absolute right-0 bottom-4 text-primary-container/40">
                        <span class="material-symbols-outlined">alternate_email</span>
                    </div>
                </div>
                <!-- Action Button -->
                <div class="pt-4">
                    <button id="submit-btn"
                        class="neon-button w-full py-5 rounded-lg flex items-center justify-center gap-3 text-on-primary font-bold font-headline-md group"
                        type="submit">
                        <span>{{ __('app.send_reset_link') }}</span>
                        <span class="material-symbols-outlined transition-transform group-hover:translate-x-1">
                            arrow_forward
                        </span>
                    </button>
                </div>
            </form>
            <!-- Footer Link -->
            <div class="mt-8 text-center">
                <a class="inline-flex items-center gap-2 text-on-surface-variant hover:text-primary-container transition-colors font-label-sm uppercase tracking-widest"
                    href="{{ route('login') }}">
                    <span class="material-symbols-outlined text-sm">arrow_back</span>
                    {{ __('app.back_to_login') }}
                </a>
            </div>
        </div>
    </div>
@endsection
