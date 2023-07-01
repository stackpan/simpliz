<button
    {!! $attributes->merge(['class' => 'px-10 py-2 bg-gray-500 font-bold text-xl text-white active:bg-gray-400']) !!}
    >{{ $slot }}</button>