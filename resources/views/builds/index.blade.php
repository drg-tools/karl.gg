@extends('layouts.app')

@section('content')

    <a href="/builds/create">Create Build</a>

    @foreach($builds as $build)

        <section>
            <h3>{{ $build->name }}</h3>
            <i>{{ $build->updated_at }}</i>
            <p>{{ $build->description }}</p>
        </section>

    @endforeach

@endsection
