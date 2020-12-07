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
    @auth
        <meta name="user-id" content="{{ (Auth::user()->id) }}">
    @endauth
    @guest
        <meta name="user-id" content="0">
@endguest
<!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://kit.fontawesome.com/87ca57c51d.js" crossorigin="anonymous"></script>
    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body class="max-w-screen-lg mx-auto bg-gray-100 h-screen antialiased leading-none @yield('body_class')">
<div id="app">

@if(\Request::is('asv/*'))
@include('layouts.asv-nav' )
@else 
@include('layouts.nav' )
@endif


@yield('content')

<!-- todo: footer -->
    <footer
        class='footerBorder w-full text-center border-t border-grey p-4 pin-b mt-4 text-gray-500 flex flex-1 justify-center'>
        <div class="p-2 mr-2" id="copyright">Copyright @ {{ \Carbon\Carbon::today()->year }} karl.gg</div>
        <div class="p-2"><a href="/privacy-policy">Privacy Policy</a></div>
        <a href="https://discord.gg/pdh5RMf" class="p-2">
            <div class="footer-disc"><i class="fab fa-discord"></i> JOIN OUR DISCORD</div>
        </a>
    </footer>

</div>
<!-- todo: mobile menu without jquery/bootstrap -->
<script
    src="https://code.jquery.com/jquery-3.5.1.min.js"
    integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
    crossorigin="anonymous"></script>
<script>
    $(function () {
        $('#mobile-menu-button').on('click', function () {
            $('#mobile-menu').toggleClass('hidden')
        })
    });
</script>
@yield('scripts')

</body>
</html>
