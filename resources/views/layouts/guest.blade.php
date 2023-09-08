<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="text-primary-content antialiased">
        <div class="min-h-screen pt-6 sm:pt-0 bg-neutral flex flex-col justify-center align-center">
            <div class="self-center">
                <a href="/">
                    <x-application-logo class="fill-current text-primary-content" />
                </a>
            </div>

            <div class="w-96 mx-auto mt-6 px-6 py-4 bg-white sm:rounded">
                {{ $slot }}
            </div>
            @isset($footer)
                <div class="mx-auto">
                    {{ $footer }}
                </div>
            @endisset
        </div>
    </body>
</html>
