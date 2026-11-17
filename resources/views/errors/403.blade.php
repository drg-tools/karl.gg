@extends('errors.layout')

@php
    $error_number = 403;
@endphp

@section('title', 'Access denied')

@section('description')
    Your account does not have permission to access this page. Sign in with an authorized account or ask an administrator to grant access.
@endsection
