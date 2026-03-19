@props([
    'variant' => 'default',
])

@php
    $classes = match ($variant) {
        'primary' => 'btn-primary',
        'success' => 'btn-success',
        'danger'  => 'btn-danger',
        default   => 'btn-secondary',
    };
@endphp

<button {{ $attributes->class([$classes]) }}>
    {{ $slot }}
</button>
