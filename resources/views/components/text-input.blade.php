@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full bg-transparent border-0 border-b-2 border-white/10 py-4 font-body-md text-white placeholder:text-white/20 focus:ring-0 focus:outline-none input-glow transition-all duration-300']) }}>
