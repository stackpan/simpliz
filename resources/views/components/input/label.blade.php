@props(['text'])

<label {{ $attributes->merge(['class' => 'label']) }}>
    <span class="label-text">{{ $text }}</span>
    {{ $slot }}
</label>
