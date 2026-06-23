@props(['value'])

<label {{ $attributes->merge(['class' => 'font-label-sm text-primary-container opacity-70 group-focus-within:opacity-100 transition-opacity']) }}>
    {{ $value ?? $slot }}
</label>
