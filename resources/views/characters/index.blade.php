@extends('layouts.app')

@section('content')

    <div class="content-container">
        @foreach($characters as $character)
            <div class="border-b mx-4">
                <div class="flex mb-4">
                    <div class="mt-4">
                        <img src="/assets/img/{{ $character->image }}.png" alt="{{ $character->name }} icon">
                    </div>
                    <h2 class="ml-4 pb-2 border-b-4">{{ $character->name }}</h2>
                </div>

                <h3>Guns</h3>
                <div class="grid grid-flow-row grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($character->guns as $gun)
                        <div class="p-4">
                            <h4><a href="{{ route('guns.show', $gun->id) }}">{{ $gun->name }}</a></h4>
                            <img src="/assets/{{ $gun->image }}.svg" alt="{{ $gun->name }} icon"
                                 class="w-16 img-filter-white">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <th class="px-6 py-3 text-left text-xs leading-4 font-medium text-orange uppercase tracking-wider">Stat</th>
                                    <th class="px-6 py-3 text-left text-xs leading-4 font-medium text-orange uppercase tracking-wider">Value</th>
                                </thead>
                                <tbody>
                                    @foreach($gun->json_stats as $stat)
                                    <tr>
                                        <td>{{ $stat['name'] }}</td>
                                        <td>{{ $stat['value'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endsection
