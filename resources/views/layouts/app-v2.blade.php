<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-165794980-1"></script>
    <script data-ad-client="ca-pub-3760169257343113" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>

    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', 'UA-165794980-1');
    </script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {!! SEO::generate() !!}

    <!-- Styles -->
    <script src="https://kit.fontawesome.com/87ca57c51d.js" crossorigin="anonymous"></script>
    <link href="{{ mix('css/app-v2.css') }}" rel="stylesheet">
    @yield('styles')
</head>
<body>
<div id="app" class="bg-gray-900 min-h-screen">
    @include('layouts.nav-v2')

    <header class="bg-gray-700 shadow-sm">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            <h1 class="text-lg leading-6 font-semibold text-white">
                @yield('header')
            </h1>
        </div>
    </header>
    <main>
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>
</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}" defer></script>

@yield('scripts')

</body>
</html>