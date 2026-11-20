@extends('errors.layout')

@php
    $error_number = 429;
@endphp

@section('title', 'Too many requests')

@section('description')
    You're moving faster than the drop pod. Wait a moment, then try again.
@endsection
