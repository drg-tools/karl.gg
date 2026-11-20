@extends('errors.layout')

@php
    $error_number = 404;
@endphp

@section('title', 'Page not found')

@section('description')
    This page doesn't exist or was moved. Even the loot bugs couldn't find it.
@endsection

@section('art')
    <img src="{{ asset('assets/img/404.png') }}" alt="" width="600" height="600">
@endsection
