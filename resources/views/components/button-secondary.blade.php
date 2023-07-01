<button
    {!! $attributes->merge(['class' => 'px-10 py-2 bg-gray-200 font-bold text-xl text-gray-500 active:bg-gray-100']) !!}
    >{{ $slot }}</button>