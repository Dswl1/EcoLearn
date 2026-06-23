@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-primary-container text-start text-base font-medium text-primary bg-surface-container-high focus:outline-none focus:text-primary focus:bg-surface-container focus:border-primary-container transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high hover:border-white/20 focus:outline-none focus:text-on-surface focus:bg-surface-container-high focus:border-white/20 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
