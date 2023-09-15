<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

    @livewireStyles

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
<div class="drawer drawer-end">
    <input id="drawer" type="checkbox" class="drawer-toggle"/>
    <div class="drawer-content flex flex-col">
        <!-- Navbar -->
        <nav class="bg-base-300">
            <div class="max-w-7xl px-4 sm:px-6 lg:px-8 mx-auto navbar">
                <div class="flex-1">
                    <a href="{{ route('home') }}">
                        <x-application-logo class="block fill-current text-base-content"/>
                    </a>
                </div>
                <div class="flex-none hidden lg:block">
                    <ul class="menu menu-horizontal">
                        <!-- Navbar menu content here -->
                        <li>
                            <details>
                                <summary>{{ auth()->user()->name }}</summary>
                                <ul class="p-2 bg-base-100">
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
                </div>
                <div class="flex-none lg:hidden">
                    <label for="drawer" class="btn btn-square btn-ghost">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             class="inline-block w-6 h-6 stroke-current">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </label>
                </div>
            </div>
        </nav>
        <!-- Page Heading -->
        @if (isset($header))
            <header {!! $attributes->merge(['class' => '']) !!}>
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 text-2xl text-base-content leading-tight">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main {!! $attributes->merge(['class' => 'grow']) !!}>
            {{ $slot }}
        </main>
    </div>
    <div class="drawer-side">
        <label for="drawer" class="drawer-overlay"></label>
        <ul class="menu w-80 min-h-full bg-base-200 p-0 [&_li>*]:rounded-none space-y-2">
            <!-- Sidebar content here -->
            <li class="menu-title bg-base-300 py-4">
                <div class="font-medium text-base text-base-content py-0">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-base-content/60 py-0">{{ Auth::user()->email }}</div>
            </li>
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
    </div>
</div>
@livewireScriptConfig
</body>
</html>
