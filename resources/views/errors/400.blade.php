@extends('errors.layout')

@php
    $error_number = 400;
@endphp

@section('title', 'Bad request')

@section('description')
    The request couldn't be understood. Go back and try again, or head to the dashboard.
@endsection
