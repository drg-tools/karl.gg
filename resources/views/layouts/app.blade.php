<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-KJ6GL0WN2F"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-KJ6GL0WN2F');
    </script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {!! SEO::generate() !!}

    @auth
        <meta name="user-id" content="{{ (Auth::user()->id) }}">
    @endauth
    @guest
        <meta name="user-id" content="0">
    @endguest

    <!-- Styles -->
    <script src="https://kit.fontawesome.com/87ca57c51d.js" crossorigin="anonymous"></script>
    <link href="{{ mix('css/app-v2.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
<div id="app" class="bg-gray-900 flex flex-col min-h-screen">
    @include('layouts.nav-v2')

    <header class="bg-gray-700 shadow-sm">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            <h1 class="text-lg leading-6 font-semibold text-white">
                @yield('header')
            </h1>
        </div>
    </header>

    @include('layouts.banner')

    <main class="flex-grow mb-4">
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>

    @include('layouts.footer')
</div>

<!-- Scripts -->
<script src="{{ mix("/js/manifest.js") }}"></script>
<script src="{{ mix("/js/vendor.js") }}"></script>
<script src="{{ mix("/js/app.js") }}"></script>

@yield('scripts')

</body>
</html>
