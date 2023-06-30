@props(['type', 'content'])

<button
    @isset($type)
    type="{{ $type }}"
    @endisset
    class="px-10 py-2 bg-gray-500 font-bold text-xl text-white active:bg-gray-400"
    >{{ __($content) }}</button>