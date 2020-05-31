<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-165794980-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-165794980-1');
    </script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    @livewireStyles
</head>
<body class="bg-gray-100 h-screen antialiased leading-none">
    <div id="app">

        @include('layouts.nav')

        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold leading-tight text-gray-900">
                    @yield('title')
                </h1>
            </div>
        </header>
        <main>
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>
        <footer class='w-full text-center border-t border-grey p-4 pin-b mt-4 text-gray-500 flex flex-1 justify-center'>
            <div class="p-2 mr-2" id="copyright">Copyright @ {{ \Carbon\Carbon::today()->year }} DRG Builds</div>
            <div class="p-2"><a href="/privacy-policy">Privacy Policy</a></div>
        </footer>

        @livewireScripts
        @yield('scripts')
        <script>
            (function() {
                $('#mobile-menu-button').on('click', function () {
                    $('#mobile-menu').toggleClass('hidden md:hidden')
                })
            })();
        </script>
    </div>
</body>
</html>
