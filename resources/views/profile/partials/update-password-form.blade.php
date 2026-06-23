<section>
    <header>
        <h2 class="text-lg font-headline-md text-on-surface">
            {{ __('app.update_password') }}
        </h2>

        <p class="mt-1 text-sm text-on-surface-variant">
            {{ __('app.update_password_desc') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div class="relative group">
            <x-input-label for="update_password_current_password" :value="__('app.current_password')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div class="relative group">
            <x-input-label for="update_password_password" :value="__('app.new_password')" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div class="relative group">
            <x-input-label for="update_password_password_confirmation" :value="__('app.confirm_password')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('app.save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <script>document.addEventListener('DOMContentLoaded', function() { Swal.fire({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000, icon: 'success', title: '{{ __('app.saved') }}' }); })</script>
            @endif
        </div>
    </form>
</section>
