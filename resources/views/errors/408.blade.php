@extends('errors.layout')

@php
    $error_number = 408;
@endphp

@section('title', 'Request timed out')

@section('description')
    The request took too long. Refresh the page and try again.
@endsection
