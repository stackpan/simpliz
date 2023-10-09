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

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
<div class="drawer lg:drawer-open">
    <input id="manager-drawer" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content flex flex-col p-4">
        <!-- Page content here -->
        <div class="navbar">
            <div class="navbar-start">
                <label for="manager-drawer" class="btn btn-square btn-ghost drawer-button lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-5 h-5 stroke-current"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </label>
                @if(isset($breadcrumbs))
                    <nav class="text-sm breadcrumbs">
                        <ul>
                            <li><a href="{{ route('manager.index') }}">Manager</a></li>
                            {{ $breadcrumbs }}
                        </ul>
                    </nav>
                @endif
            </div>
            <div class="navbar-end">
                <x-profile-navigation />
            </div>
        </div>
        <main class="p-2">
            {{ $slot }}
        </main>
    </div>
    <div class="drawer-side">
        <label for="manager-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
        <ul class="menu p-4 w-80 min-h-full bg-base-300 text-base-content text-lg">
            <!-- Sidebar content here -->
            <li>
                <a href="{{ route('manager.index') }}" class="py-6 btn-disabled">
                    <x-application-logo class="block fill-current text-base-content"/>
                </a>
            </li>
            <li>
                <x-drawer-navigation href="{{ route('manager.home') }}" :active="request()->routeIs('manager.home')">
                    <x-icon.home />
                    <span>Home</span>
                </x-drawer-navigation>
            </li>
            <li>
                <x-drawer-navigation href="{{ route('manager.user') }}" :active="request()->routeIs('manager.user')">
                    <x-icon.users />
                    <span>User</span>
                </x-drawer-navigation>
            </li>
            <li>
                <x-drawer-navigation href="{{ route('manager.quiz') }}" :active="request()->routeIs('manager.quiz')">
                    <x-icon.document-text />
                    <span>Quiz</span>
                </x-drawer-navigation>
            </li>
            <li>
                <x-drawer-navigation href="{{ route('manager.result') }}" :active="request()->routeIs('manager.result')">
                    <x-icon.clipboard-document-list />
                    <span>Result</span>
                </x-drawer-navigation>
            </li>
        </ul>
    </div>
</div>
</body>
</html>
