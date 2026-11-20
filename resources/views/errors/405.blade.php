@extends('errors.layout')

@php
    $error_number = 405;
@endphp

@section('title', 'Not allowed')

@section('description')
    That action isn't supported here. Go back and try again, or head to the dashboard.
@endsection
