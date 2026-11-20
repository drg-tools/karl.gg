@extends('errors.layout')

@php
    $error_number = 500;
@endphp

@section('title', 'Something went wrong')

@section('description')
    An unexpected error stopped this page from loading. Try again in a moment; if it keeps happening, let us know on Discord.
@endsection
