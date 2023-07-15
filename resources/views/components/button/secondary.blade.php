<button {{ $attributes->merge(['type' => 'submit', 'class' => 'px-6 py-1 bg-gray-200 text-gray-700 text-md sm:text-lg font-bold text-center']) }}>
    {{ $slot }}
</button>
