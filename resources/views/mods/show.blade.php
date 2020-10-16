@extends('layouts.app')

@section('content')

    <div class="content-container">
        <div class="border-b  mx-4">
            <div class="flex mb-4">
                <div class="mt-4">
                    <img src="/assets/img/{{ $mod->image }}.png" alt="{{ $mod->name }} icon">
                </div>
                <h2 class="ml-4 pb-2 border-b-4">{{ $mod->name }}</h2>
            </div>


        </div>
    </div>
@endsection
