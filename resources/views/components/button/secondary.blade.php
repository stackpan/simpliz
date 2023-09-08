<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-ghost']) }}>
    {{ $slot }}
</button>
