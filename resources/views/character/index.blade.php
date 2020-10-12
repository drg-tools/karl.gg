@extends('layouts.app')

@section('content')

    <div class="content-container">
        @foreach($characters as $character)
            <div class="border-b">
                <div class="flex mb-4">
                    <img src="/assets/img/{{ $character->image }}.png" alt="{{ $character->name }} icon">
                    <h2 class="ml-4">{{ $character->name }}</h2>
                </div>

                <h3>Guns</h3>
                <div class="grid grid-flow-row grid-cols-2 gap-4">
                    @foreach($character->guns as $gun)
                        <div class="p-4">
                            <h4>{{ $gun->name }}</h4>
                            <img src="/assets/{{ $gun->image }}.svg" alt="{{ $gun->name }} icon"
                                 class="w-16 img-filter-white">
                            <table>
                                {{-- Stats here --}}
                            </table>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endsection
