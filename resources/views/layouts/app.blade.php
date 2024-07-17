<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="app-url" content="{{ url('/') }}">

        <title>{{ config('app.name') }}@yield('title')</title>

        <!-- Scripts -->
        @vite(['resources/js/app.js'])
        @yield('script')

        <!-- Styles -->
        @vite(['resources/sass/app.scss'])
        @yield('style')

        <link rel="icon" href="{{ asset('favicon.ico') }}">
    </head>
    <body>

        <div id="app">
            @include('navigation.index')

            <main>
                @yield('content')
            </main>
        </div>

        @include('elements.modal', [
            'id' => 'shareModal',
            'title' => 'Share content',
            'class' => 'centered',
            'bodyClass' => 'p-0'
        ])

    </body>
</html>
