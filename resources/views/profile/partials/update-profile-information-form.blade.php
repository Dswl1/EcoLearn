<section>
    <header>
        <h2 class="text-lg font-headline-md text-on-surface">
            {{ __('app.profile_information') }}
        </h2>

        <p class="mt-1 text-sm text-on-surface-variant">
            {{ __('app.profile_information_desc') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="relative group">
            <x-input-label for="name" :value="__('app.name_field')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="relative group">
            <x-input-label for="email" :value="__('app.email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-on-surface-variant">
                        {{ __('app.email_unverified') }}

                        <button form="send-verification" class="underline text-sm text-primary hover:text-primary-container rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-container">
                            {{ __('app.resend_verification_email') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <script>document.addEventListener('DOMContentLoaded', function() { Swal.fire({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, icon: 'success', title: '{{ __('app.verification_link_sent') }}' }); })</script>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('app.save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <script>document.addEventListener('DOMContentLoaded', function() { Swal.fire({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000, icon: 'success', title: '{{ __('app.saved') }}' }); })</script>
            @endif
        </div>
    </form>
</section>
