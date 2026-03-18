@props([
    'variant' => 'default', // или 'primary', 'danger', и т.д.
])

@php
    $base = 'rounded-md px-2.5 py-1.5 text-sm font-semibold text-center shadow-sm';
    $variants = [
        'default' => 'bg-white text-black border border-slate-300 hover:bg-slate-100',
        'primary' => 'bg-blue-600 text-white border border-blue-700 hover:bg-blue-700',
        'success' => 'bg-green-600 text-white hover:bg-green-700',
        'danger' => 'bg-red-600 text-white border border-red-700 hover:bg-red-700',
    ];
@endphp

<button {{ $attributes->class("$base {$variants[$variant]}") }}>
    {{ $slot }}
</button>
