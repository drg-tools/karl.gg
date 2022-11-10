@extends('layouts.app')

@section('header')
    Build a Loadout
@endsection

@section('content')
    <div class="bg-gray-700 text-gray-300 p-4 sm:rounded">
        <div class="flex justify-center my-2">
            <x-ads.horizontal id="3873388796" />
        </div>
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

