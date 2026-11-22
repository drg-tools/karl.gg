<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @if (config('app.google_analytics_id'))
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('app.google_analytics_id') }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('set', 'user_properties', { signed_in: @js(auth()->check() ? 'yes' : 'no') });
            gtag('config', @js(config('app.google_analytics_id')));
        </script>
    @endif

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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    @if (config('app.enable_ads'))
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ config('app.adsense_client') }}"
                crossorigin="anonymous"></script>
        {{-- Keep the Auto ads anchor at the bottom; a top anchor covers the nav on phones. --}}
        <script>
            (window.adsbygoogle = window.adsbygoogle || []).push({
                google_ad_client: @js(config('app.adsense_client')),
                enable_page_level_ads: true,
                overlays: { bottom: true },
            });
        </script>
    @endif
</head>
<body>
<div id="app" class="site-shell flex flex-col min-h-screen">
    @include('layouts.nav-v2')

    @hasSection('header')
        <header class="site-header">
            <div class="site-header__inner">
                <h1>@yield('header')</h1>
            </div>
        </header>
    @endif

    @include('layouts.banner')

    <main class="grow mb-4">
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>

    @include('layouts.footer')
</div>

@yield('scripts')

</body>
</html>
