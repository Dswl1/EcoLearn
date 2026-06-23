<button {{ $attributes->merge(['type' => 'submit', 'class' => 'neon-button w-full py-4 rounded-lg flex items-center justify-center gap-3 text-on-primary font-bold font-headline-md group']) }}>
    {{ $slot }}
</button>
