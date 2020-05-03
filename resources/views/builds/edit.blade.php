@extends('layouts.app')

@section('title', 'Create a Build')

@section('content')
    {!! Form::open(['route' => 'builds.store', 'method' => 'post', 'class' => 'px-8 pt-6 pb-8 mb-4']) !!}

    <div class="mb-4">
        {!! Form::label('name', 'Name', ['class' => 'block text-gray-700 text-sm font-bold mb-2']) !!}
        {!! Form::text('name', $build->name, ['required', 'class' => 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline']) !!}
    </div>

    <div class="mb-4">
        {!! Form::label('description', 'Description', ['class' => 'block text-gray-700 text-sm font-bold mb-2']) !!}
        {!! Form::textarea('description', $build->description, ['class' => 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline']) !!}
    </div>

    @livewire('character-selector', ['characters' => $characters, 'character_id' => $build->character_id])

    <div class="mt-4">
    {!! Form::submit('Create', ['class' => 'bg-blue-500 w-full hover:bg-blue-700 text-white font-bold py-4 px-4 rounded focus:outline-none focus:shadow-outline']) !!}
    </div>

    {!! Form::close() !!}
@endsection

