<x-guest-layout>
    <div class="mb-2 text-center">
        <div class="inline-flex items-center gap-2 mb-2">
            <span class="material-symbols-outlined text-primary-container text-4xl"
                style="font-variation-settings: 'FILL' 1;">public</span>
            <h1 class="font-display-lg text-primary-container tracking-tighter uppercase text-xl font-bold">{{ __('app.app_name') }}</h1>
        </div>
    </div>

    <div class="glass-card rounded-xl p-8 md:p-12 relative overflow-hidden">
        <div class="relative z-10">
            <h2 class="font-headline-md text-white text-3xl font-bold mb-6 text-center">
                {{ __('app.verify_email') }}
            </h2>

            <p class="font-body-md text-on-surface-variant mb-8 leading-relaxed text-center">
                {{ __('app.verify_email_desc') }}
            </p>

            @if (session('status') == 'verification-link-sent')
                <script>document.addEventListener('DOMContentLoaded', function() { Swal.fire({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, icon: 'success', title: '{{ __('app.verification_link_sent') }}' }); })</script>
            @endif

            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <form method="POST" action="{{ route('verification.send') }}" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit"
                        class="neon-button w-full sm:w-auto px-8 py-4 rounded-lg flex items-center justify-center gap-3 text-on-primary font-bold font-headline-md group">
                        <span>{{ __('app.resend_verification') }}</span>
                        <span class="material-symbols-outlined transition-transform group-hover:translate-x-1">refresh</span>
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 text-on-surface-variant hover:text-primary transition-colors font-label-sm uppercase tracking-widest">
                        <span class="material-symbols-outlined text-sm">logout</span>
                        {{ __('app.logout') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
