@props([
    'variant' => 'formosa', // 'formosa' o 'aniversario'
    'size' => 'md',       // 'sm', 'md', 'lg'
])

@php
    $logoSrc = match ($variant) {
        'aniversario' => asset('images/logo-aniversario.png'),
        default => asset('images/logo-formosa.png'),
    };

    $sizeClasses = match ($size) {
        'sm' => 'h-8',
        'lg' => 'h-20',
        default => 'h-12',
    };

    $altText = match ($variant) {
        'aniversario' => 'AACOP 25 Aniversario',
        default => 'AACOP Delegación Formosa',
    };
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center justify-center']) }}>
    <img src="{{ $logoSrc }}" alt="{{ $altText }}" class="{{ $sizeClasses }} object-contain">
</div>
