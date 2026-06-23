@extends('layouts.auth')
@section('title')
    {{ __('app.register_session') }} | {{ __('app.app_name') }}
@endsection
@section('auth')
    <div class="min-w-xl w-[700px]">

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
        <div class="glass-card rounded-xl p-8 md:p-12 min-w-2xl relative overflow-hidden">

            <div class="relative z-10">
                <h2 class="font-headline-md text-white text-3xl font-bold mb-20 text-center">
                    {{ __('app.register_session') }}
                </h2>


                <form method="POST" action="{{ route('register') }}" class="space-y-8">

                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                        <!-- LEFT -->
                        <div class="space-y-12">

                            <!-- Name -->
                            <div class="relative group">

                                <label
                                    class="font-label-sm text-primary-container absolute -top-6 left-0 opacity-70 group-focus-within:opacity-100 transition-opacity">
                                    {{ __('app.full_name') }}
                                </label>

                                <input
                                    class="w-full bg-transparent border-0 border-b-2 border-white/10 py-4 font-body-md text-white placeholder:text-white/20 focus:ring-0 focus:outline-none input-glow transition-all duration-300"
                                    name="name" type="text" value="{{ old('name') }}" placeholder="{{ __('app.name_placeholder') }}"
                                    required autocomplete="off">

                                <div class="absolute right-0 bottom-4 text-primary-container/40">
                                    <span class="material-symbols-outlined">
                                        person
                                    </span>
                                </div>

                                @error('name')
                                    <p class="mt-2 text-sm text-red-400">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>

                            <!-- Email -->
                            <div class="relative group">

                                <label
                                    class="font-label-sm text-primary-container absolute -top-6 left-0 opacity-70 group-focus-within:opacity-100 transition-opacity">
                                    {{ __('app.email') }}
                                </label>

                                <input
                                    class="w-full bg-transparent border-0 border-b-2 border-white/10 py-4 font-body-md text-white placeholder:text-white/20 focus:ring-0 focus:outline-none input-glow transition-all duration-300"
                                    name="email" type="email" value="{{ old('email') }}"
                                    placeholder="{{ __('app.email_placeholder') }}" required autocomplete="off">

                                <div class="absolute right-0 bottom-4 text-primary-container/40">
                                    <span class="material-symbols-outlined">
                                        alternate_email
                                    </span>
                                </div>

                                @error('email')
                                    <p class="mt-2 text-sm text-red-400">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>

                        </div>

                        <!-- RIGHT -->
                        <div class="space-y-8">

                            <!-- Password -->
                            <div class="relative group">

                                <label
                                    class="font-label-sm text-primary-container absolute -top-6 left-0 opacity-70 group-focus-within:opacity-100 transition-opacity">
                                    {{ __('app.password') }}
                                </label>

                                <input
                                    class="w-full bg-transparent border-0 border-b-2 border-white/10 py-4 font-body-md text-white placeholder:text-white/20 focus:ring-0 focus:outline-none input-glow transition-all duration-300"
                                    name="password" type="password" placeholder="{{ __('app.password_placeholder') }}" required>

                                <div class="absolute right-0 bottom-4 text-primary-container/40">
                                    <span class="material-symbols-outlined">
                                        lock
                                    </span>
                                </div>

                                @error('password')
                                    <p class="mt-2 text-sm text-red-400">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>

                            <!-- Confirm Password -->
                            <div class="relative group">

                                <label
                                    class="font-label-sm text-primary-container absolute -top-6 left-0 opacity-70 group-focus-within:opacity-100 transition-opacity">
                                    {{ __('app.confirm_password') }}
                                </label>

                                <input
                                    class="w-full bg-transparent border-0 border-b-2 border-white/10 py-4 font-body-md text-white placeholder:text-white/20 focus:ring-0 focus:outline-none input-glow transition-all duration-300"
                                    name="password_confirmation" type="password" placeholder="{{ __('app.password_placeholder') }}" required>

                                <div class="absolute right-0 bottom-4 text-primary-container/40">
                                    <span class="material-symbols-outlined">
                                        verified_user
                                    </span>
                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- Button -->
                    <div class="flex justify-center">
                        <button id="submit-btn"
                            class="neon-button w-[75%] py-5 rounded-lg flex items-center justify-center gap-3 text-on-primary font-bold font-headline-md group"
                            type="submit">

                            <span>
                                {{ __('app.create_account') }}
                            </span>

                            <span class="material-symbols-outlined transition-transform group-hover:translate-x-1">
                                arrow_forward
                            </span>

                        </button>
                    </div>

                </form>

                <!-- Footer -->
                <div class="mt-8 text-center">

                    <a href="{{ route('login') }}"
                        class="inline-flex items-center gap-2 text-on-surface-variant hover:text-primary-container transition-colors font-label-sm uppercase tracking-widest">

                        <span class="material-symbols-outlined text-sm">
                            arrow_back
                        </span>

                        {{ __('app.back_to_login') }}

                    </a>

                </div>

            </div>

        </div>
    </div>
@endsection
