@props(['content', 'route', 'active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex justify-center w-full bg-gray-200 text-center py-2 uppercase font-bold text-xl cursor-pointer'
            : 'inline-flex justify-center w-full bg-gray-400 text-center py-2 uppercase font-bold text-xl active:bg-gray-300 cursor-pointer';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} href="{{ route($route) }}">{{ __($content) }}</a>