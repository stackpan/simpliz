<button {{ $attributes->merge(['type' => 'submit', 'class' => 'px-6 py-1 bg-gray-500 text-md sm:text-lg font-bold text-center text-white']) }}>
    {{ $slot }}
</button>
