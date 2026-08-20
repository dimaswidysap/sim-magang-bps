@props([
    'href' => null,
    'type' => 'submit',
    'color' => 'primary', // Default ke warna biru
])

@php
    $baseClasses = 'font-montserrat self-center cursor-pointer inline-flex items-center border border-text/20 justify-center py-1 gap-2 px-4 rounded-md font-montserrat font-bold text-[12px] tracking-[2px] transition-all duration-200 focus:outline-none';

    $colorClasses = match ($color) {
        'primary' => 'bg-primary text-white hover:bg-primary/80 shadow-md',
        'secondary' => 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-300 shadow-md',
        'accent-dark' => 'bg-accent-dark text-white hover:brightness-90 shadow-md',
        'danger' => 'bg-danger text-white hover:brightness-90 shadow-md',
        // Tambahkan varian baru untuk state "inactive" dari kode Anda
        'surface' => 'bg-surface border-border text-text-light hover:bg-primary/5 hover:border-primary/50 hover:text-primary shadow-sm transition-colors',
        default => 'bg-blue-500 text-white hover:bg-blue-600 shadow-md',
    };
@endphp

{{-- Render Anchor atau Button (Sama seperti kode Anda sebelumnya) --}}
@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $baseClasses . ' ' . $colorClasses]) }}>
        @if (isset($icon))
            <span class="w-4 h-4 flex items-center justify-center [&>svg]:w-full [&>svg]:h-full">
                {{ $icon }}
            </span>
        @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $baseClasses . ' ' . $colorClasses]) }}>
        @if (isset($icon))
            <span class="w-4 h-4 flex items-center justify-center [&>svg]:w-full [&>svg]:h-full">
                {{ $icon }}
            </span>
        @endif
        {{ $slot }}
    </button>
@endif
