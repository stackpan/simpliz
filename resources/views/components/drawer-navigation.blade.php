@props(['active'])

@if($active)
    <a {{ $attributes->merge(['href', 'class' => 'bg-accent-focus/60']) }}>
        {{ $slot }}
    </a>
@else
    <a {{ $attributes->merge(['href', 'class' => '']) }}>
        {{ $slot }}
    </a>
@endif
