<button {{ $attributes->merge(['type' => 'submit', 'class' => 'px-8 py-2 bg-gray-200 text-lg sm:text-xl font-bold text-center']) }}>
    {{ $slot }}
</button>
