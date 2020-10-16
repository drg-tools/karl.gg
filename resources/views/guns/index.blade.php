@extends('layouts.app')

@section('content')

    <div class="content-container">

        <h1>Guns</h1>

        <table class="table w-full table-auto">
            <thead class="text-left">
                <th>Name</th>
                <th>Image</th>
                <th>Character</th>
            </thead>
            <tbody>
            @foreach($guns as $gun)
                <tr onclick="window.open('{{ route('guns.show', $gun->id) }}','_blank')">
                    <td>{{ $gun->name }}</td>
                    <td><x-gun-image :gun="$gun" class="w-16"/></td>
                    <td>{{ $gun->character->name }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $guns->links() }}

    </div>
@endsection
