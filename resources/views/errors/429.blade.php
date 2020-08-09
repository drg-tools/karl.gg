@extends('errors.layout')

@php
  $error_number = 429;
@endphp

@section('title')
  Too many requests.
@endsection

@section('description')
  @php
    $default_error_message = "Please <a href='javascript:history.back()''>go back</a> and try again, or return to <a href='".url('')."'>our homepage</a>.";
  @endphp
  {!! isset($exception)? ($exception->getMessage()?$exception->getMessage():$default_error_message): $default_error_message !!}
@endsection

@section('after_styles')
  <style>
    body {
      background-image: radial-gradient(circle, rgba(255, 255, 255, 0.6) -50%, rgba(255, 255, 255, 0) 50%), url("../assets/img/429.png");
      background-color: #352e1e;
      background-blend-mode: overlay;
      background-repeat: no-repeat;
      background-size: 45%;
      background-position: right -10% bottom -10%;
    }
    div {
      font-family: BebasNeue, sans-serif;
    }
    .app-footer {
      font-family: BebasNeue, sans-serif;
      color: #ADA195;
    }
    .app-footer a {
      color: #fc9e00;
    }
    .error_number {
      color: #fc9e00;
      font-size: 156px;
      font-weight: 600;
      line-height: 100px;
    }
    .error_number small {
      color: #fc9e00;
      font-size: 56px;
      font-weight: 700;
    }

    .error_number hr {
      margin-top: 60px;
      margin-bottom: 0;
      width: 50px;
    }

    .error_title {
      color: #ADA195;
      margin-top: 40px;
      font-size: 36px;
      font-weight: 400;
    }

    .error_description {
      color: #ADA195;
      font-size: 24px;
      font-weight: 400;
    }
    .error_description a {
      color: #fc9e00;
    }
  </style>
@endsection
