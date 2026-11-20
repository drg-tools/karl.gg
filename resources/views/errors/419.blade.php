@extends('errors.layout')

@php
    $error_number = 419;
@endphp

@section('title', 'Page expired')

@section('description')
    Your session timed out before the form was sent. Go back, refresh the page and try again.
@endsection
