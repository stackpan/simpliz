<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-active btn-secondary']) }}>
    {{ $slot }}
</button>
