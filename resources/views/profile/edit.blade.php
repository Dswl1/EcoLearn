@extends('app')
@section('title', __('app.profile') . ' | ' . __('app.app_name'))

@section('content')
<div class="relative">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_left,_var(--tw-gradient-stops))] from-primary/10 via-background to-background -z-10 blur-3xl opacity-50"></div>
    <div class="max-w-5xl mx-auto py-12 md:py-20 flex flex-col gap-section-gap">

        <!-- Page Header -->
        <section class="flex flex-col md:flex-row items-center md:items-start justify-between gap-6 relative z-10">
            <div class="text-center md:text-left">
                <h1 class="font-display-lg-mobile md:font-display-lg text-display-lg-mobile md:text-display-lg text-white drop-shadow-[0_0_10px_rgba(255,255,255,0.2)]">{{ __('app.profile') }}</h1>
                <p class="font-body-lg text-body-lg text-on-surface-variant mt-1">{{ __('app.profile_information_desc') }}</p>
            </div>
            <a href="{{ route('profile.show') }}" class="glass-card text-on-surface px-6 py-3 rounded-lg font-label-sm text-label-sm hover:text-primary transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                {{ __('app.view_profile') }}
            </a>
        </section>

        <!-- Profile Information -->
        <section class="glass-card rounded-xl p-8 relative z-10">
            <div class="max-w-2xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </section>

        <!-- Update Password -->
        <section class="glass-card rounded-xl p-8 relative z-10">
            <div class="max-w-2xl">
                @include('profile.partials.update-password-form')
            </div>
        </section>

        <!-- Delete Account -->
        <section class="glass-card rounded-xl p-8 relative z-10">
            <div class="max-w-2xl">
                @include('profile.partials.delete-user-form')
            </div>
        </section>

    </div>
</div>
@endsection