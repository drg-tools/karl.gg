@extends('layouts.app')

{{--
    Shared error page. Each status view sets $error_number and the `title`
    and `description` sections; the 404 view also sets `art`.
--}}
@php
    SEOMeta::setTitle('Error '.$error_number);
    $previousUrl = url()->previous();
    $canGoBack = ! in_array($previousUrl, [url()->current(), url('/')], true);
@endphp

@section('content')
    <div class="loadout-page error-page">
        <section class="error-hero" aria-labelledby="error-title">
            <div class="error-hero__copy">
                <p class="error-hero__code"><span class="sr-only">Error </span>{{ $error_number }}</p>
                <h1 class="error-hero__title" id="error-title">@yield('title', 'Something went wrong')</h1>
                <p class="error-hero__lede">@yield('description', 'The requested page could not be displayed.')</p>

                <nav class="error-hero__actions" aria-label="Error page navigation">
                    @if ($canGoBack)
                        <a class="loadout-button" href="{{ $previousUrl }}"><span aria-hidden="true">←</span> Go back</a>
                    @endif
                    <a class="loadout-button {{ $canGoBack ? 'is-secondary' : '' }}" href="{{ url('/') }}">Dashboard</a>
                    @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="browse-clear" type="submit">Sign out</button>
                        </form>
                    @else
                        <a class="browse-clear" href="{{ route('login') }}">Sign in</a>
                    @endauth
                </nav>
            </div>

            @hasSection('art')
                <div class="error-hero__art" aria-hidden="true">@yield('art')</div>
            @endif
        </section>
    </div>
@endsection
