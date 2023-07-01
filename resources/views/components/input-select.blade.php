@props(['options'])

<select {{ $attributes->merge(['class' => 'border-none bg-gray-200 focus:border-gray-500 focus:ring-gray-500 shadow-sm']) }}>
    <option value="">{{ __('Select') }}</option>
    @foreach($options as $option)
    <option value="{{ $option->value }}">{{ __($option->name) }}</option>
    @endforeach
</select>