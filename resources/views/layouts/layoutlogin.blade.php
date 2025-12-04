<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="">
        <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
        <meta name="generator" content="Hugo 0.84.0">
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sign-in/">
        <link href="{{asset('assets/dist/css/bootstrap.min.css')}}" rel="stylesheet">
        <link href="{{asset('assets/dist/css/bootstrap.css')}}" rel="stylesheet">

        <!-- Scripts -->
        {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    </head>
    <body style="background-image: url({{asset('assets/dist/images/background/background-4.jpg')}}); background-repeat: no-repeat;background-size: 100%;">
        {{-- <div class="min-h-screen bg-gray-100 dark:bg-gray-900"> --}}
            {{-- @include('layouts.head') --}}

            <!-- Page Heading -->
            {{-- @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main> --}}

            @yield('content')
        {{-- </div> --}}
    </body>
    <script src="{{('assets/dist/js/dist/dom/event-handler.js')}}"></script>
    <script src="{{('assets/dist/js/dist/dom/selector-engine.js')}}"></script>
    <script src="{{('assets/dist/js/dist/dom/data.js')}}"></script>
    <script src="{{('assets/dist/js/dist/base-component.js')}}"></script>
    <script src="{{('assets/dist/js/dist/alert.js')}}"></script>
</html>
