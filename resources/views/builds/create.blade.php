@extends('layouts.app')

@section('title', 'Create a Build')

@section('content')
    {!! Form::open(['route' => 'builds.store', 'method' => 'post', 'class' => 'px-8 pt-6 pb-8 mb-4']) !!}

    <div class="mb-4">
        {!! Form::label('name', 'Name', ['class' => 'block text-gray-700 text-sm font-bold mb-2']) !!}
        {!! Form::text('name', null, ['required', 'class' => 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline']) !!}
    </div>

    <div class="mb-4">
        {!! Form::label('description', 'Description', ['class' => 'block text-gray-700 text-sm font-bold mb-2']) !!}
        {!! Form::textarea('description', null, ['class' => 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline']) !!}
    </div>

    <div id="character-selections">
        @livewire('character-selector', ['characters' => $characters])
    </div>

    <div class="mt-4">
    {!! Form::submit('Create', ['class' => 'bg-blue-500 w-full hover:bg-blue-700 text-white font-bold py-4 px-4 rounded focus:outline-none focus:shadow-outline']) !!}
    </div>

    {!! Form::close() !!}

    <script src="https://code.jquery.com/jquery-3.5.0.min.js" integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script>
    <script>
        $('#character-selections').on('change', 'input:radio', function() {

            let classes = "bg-blue-100 border-blue-400"

            // Remove checked from all labels in row
            $(this).closest('div').find('label').removeClass(classes);

            // Apply checked to radio that is selected
            $(this).closest('label').addClass(classes);
        });
    </script>
@endsection

