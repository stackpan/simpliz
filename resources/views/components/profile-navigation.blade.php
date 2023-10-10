<ul {!! $attributes->merge(['class' => 'menu menu-horizontal']) !!}>
    <!-- Navbar menu content here -->
    <li>
        <details>
            <summary>{{ auth()->user()->name }}</summary>
            <ul class="p-2 bg-base-100 z-10">
                <li><a href="{{ route('profile.edit') }}">{{ __('Profile') }}</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a>
                            <button type="submit">{{ __('Logout') }}</button>
                        </a>
                    </form>
                </li>
            </ul>
        </details>
    </li>
</ul>
