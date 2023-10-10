@props(['options', 'value'])

<select {{ $attributes->merge(['class' => 'select select-bordered']) }}>
    @if(!isset($value))
        <option value="">{{ __('Select') }}</option>
    @endif
    @foreach($options as $option)
        <option value="{{ $option->value }}"
            @if(isset($value) && $option->value === $value)
                selected
            @endif
            >{{ __($option->name) }}</option>
    @endforeach
</select>
