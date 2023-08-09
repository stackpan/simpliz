@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'bg-gray-200 border-none focus:ring-gray-400']) !!}>
