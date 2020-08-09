@extends(backpack_user() && (Str::startsWith(\Request::path(), config('backpack.base.route_prefix'))) ? 'backpack::layouts.top_left' : 'backpack::layouts.plain')
{{-- show error using sidebar layout if logged in AND on an admin page; otherwise use a blank page --}}

@php
  $title = 'Error '.$error_number;
@endphp

@section('after_styles')
  <style>
    body {
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

@section('content')
<div class="row">
  <div class="col-md-12 text-center">
    <div class="error_number">
      <small>ERROR</small><br>
      {{ $error_number }}
      <hr>
    </div>
    <div class="error_title">
      @yield('title')
    </div>
    <div class="error_description">
      <small>
        @yield('description')
     </small>
    </div>
  </div>
</div>
@endsection
