@props(['href' => null, 'type' => 'button'])

@php
    $classes = 'py-1 px-2 rounded-md font-bold text-sm text-text cursor-pointer inline-flex items-center gap-2';
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
