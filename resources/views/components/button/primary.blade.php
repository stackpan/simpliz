<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-active btn-primary']) }}>
    {{ $slot }}
</button>
