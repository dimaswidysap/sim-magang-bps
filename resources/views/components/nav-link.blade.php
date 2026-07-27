@props(['active' => false])

@php
// Mengatur class CSS berdasarkan status aktif atau tidak
$classes = $active
    ? 'relative flex items-center overflow-hidden gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200 bg-primary text-surface shadow-sm'
    : 'relative flex items-center overflow-hidden gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200 text-text-light hover:bg-background hover:text-primary';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    <!-- Elemen dekorasi bg-accent-dark HANYA di-render saat menu aktif -->
    @if($active)
        <span class="absolute inline-flex left-0 h-full aspect-square bg-accent-dark rounded-full -translate-x-1/2 scale-110"></span>
    @endif

    <!-- Tempat untuk memasukkan Ikon -->
    @if (isset($icon))
        <div class="relative z-10 shrink-0 flex items-center justify-center">
            {{ $icon }}
        </div>
    @endif

    <!-- Teks Menu -->
    <span class="relative z-10">{{ $slot }}</span>
</a>
