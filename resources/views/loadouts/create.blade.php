@extends('layouts.app')

@section('header')
    Build a Loadout
@endsection

@section('content')
    <div class="bg-gray-700 text-gray-300 p-4 sm:rounded">
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3237881937260629"
            crossorigin="anonymous"></script>
        <!-- Builder unit -->
        <ins class="adsbygoogle"
            style="display:block"
            data-ad-client="ca-pub-3237881937260629"
            data-ad-slot="3873388796"
            data-ad-format="auto"
            data-full-width-responsive="true"></ins>
        <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
        <loadout-builder />
    </div>

@endsection

@section('scripts')
@if (Auth::check())
     <script>window.authUser={!! json_encode(Auth::user()); !!};</script>
@else
     <script>window.authUser=null;</script>
@endif
@endsection

