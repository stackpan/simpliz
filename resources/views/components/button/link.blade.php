<button {{ $attributes->merge(['href', 'class' => "btn btn-active btn-link text-primary-content/80"]) }} >
    {{ $slot }}
</button>
