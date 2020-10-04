@extends('layouts.app')

@section('content')

    <preview-header :page-url="{{ json_encode(Request::url()) }}"></preview-header>
    <loadout-preview></loadout-preview>

@endsection

