<button {{ $attributes->merge(['type' => 'submit', 'class' => 'px-8 py-2 bg-gray-500 text-lg sm:text-xl font-bold text-center text-white']) }}>
    {{ $slot }}
</button>
