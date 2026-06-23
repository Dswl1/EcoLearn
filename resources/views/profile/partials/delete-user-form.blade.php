<section class="space-y-6">
    <header>
        <h2 class="text-lg font-headline-md text-on-surface">
                    {{ __('app.delete_account') }}
        </h2>

        <p class="mt-1 text-sm text-on-surface-variant">
            {{ __('app.delete_account_desc') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('app.delete_account') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 bg-surface">
            @csrf
            @method('delete')

            <h2 class="text-lg font-headline-md text-on-surface">
                {{ __('app.confirm_delete_account') }}
            </h2>

            <p class="mt-1 text-sm text-on-surface-variant">
                {{ __('app.confirm_delete_desc') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('app.password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('app.password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('app.cancel') }}
                </x-secondary-button>

                <x-danger-button>
            {{ __('app.delete_account') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
