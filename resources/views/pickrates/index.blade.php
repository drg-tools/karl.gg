
@extends('layouts.app')

@section('header')
    Pickrates for Patch {{ $patch->patch_title }}
@endsection

@section('content')

    <div class="grid md:grid-cols-2 gap-10">
        @foreach($characters as $index => $character)
            <x-character-stats :character="$character" :comparison="$comparison[$index]" :patch="$patch" :totalLoadouts="$totalLoadouts" :previousTotal="$previousTotal" />
        @endforeach
    </div>

@endsection
