@extends('layouts.app')

@php
    $title = 'Error '.$error_number;
@endphp

@section('header', 'Error '.$error_number)

@push('styles')
    <style>
        .error-page {
            max-width: 40rem;
            margin: 3rem auto;
            padding: 2rem;
            border: 1px solid #374151;
            border-radius: 0.5rem;
            background: #1f2937;
            color: #f3f4f6;
        }
        .error-page h2 { font-size: 2rem; font-weight: 600; margin-bottom: 1rem; }
        .error-page p { line-height: 1.6; }
        .error-page a, .error-page button { color: #fbbf24; text-decoration: underline; }
        .error-page nav { display: flex; flex-wrap: wrap; gap: 1.5rem; margin-top: 1.5rem; }
    </style>
@endpush

@section('content')
    <section class="error-page" aria-labelledby="error-title">
        <h2 id="error-title">@yield('title', 'Error '.$error_number)</h2>
        <p>@yield('description', 'The requested page could not be displayed.')</p>
        <nav aria-label="Error page navigation">
            <a href="{{ url('/') }}">Return to homepage</a>
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Sign out</button>
                </form>
            @else
                <a href="{{ route('login') }}">Sign in</a>
            @endauth
        </nav>
    </section>
@endsection
