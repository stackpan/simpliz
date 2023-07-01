@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-none bg-gray-200 focus:border-gray-500 focus:ring-gray-500 shadow-sm']) !!}>
