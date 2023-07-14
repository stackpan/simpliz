<button {{ $attributes->merge(['type' => 'submit', 'class' => 'px-8 py-2 bg-gray-400 text-xl font-bold text-white text-center']) }}>
    {{ $slot }}
</button>
