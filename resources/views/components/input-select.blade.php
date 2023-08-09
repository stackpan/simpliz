@props(['options', 'selected'])

<select {{ $attributes->merge(['class' => 'bg-gray-200 border-none focus:ring-gray-400']) }}>
    @if(!isset($selected))
        <option value="">{{ __('Select') }}</option>
    @endif
    @foreach($options as $option)
        <option value="{{ $option->value }}"
            @if(isset($selected) && $option->value === $selected)
                selected
            @endif
            >{{ __($option->name) }}</option>
    @endforeach
</select>
