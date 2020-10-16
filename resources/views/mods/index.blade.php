@extends('layouts.app')

@section('content')

    <div class="content-container">

        <h1>Mods</h1>

        <table class="table w-full table-auto">
            <thead class="text-left">
                <th>Name</th>
                <th>Image</th>
                <th>Gun</th>
            </thead>
            <tbody>
            @foreach($mods as $mod)
                <tr onclick="window.open('{{ route('guns.show', $mod->id) }}','_blank')">
                    <td>{{ $mod->mod_name }}</td>
                    <td><x-mod-image :mod="$mod" class="w-16"/></td>
                    <td>{{ $mod->gun->name }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $mods->links() }}

    </div>
@endsection
