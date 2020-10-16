@extends('layouts.app')

@section('content')
    <div class="content-container py-4">
        <header>
            <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold leading-tight">
                    Gear
                </h1>
            </div>
        </header>
        <main class="">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-flow-row grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="">
                        <h2>Characters</h2>
                        <div class="">
                            <ul>
                                <li><a href="{{ route('characters.index') }}">List of Characters</a></li>
                                <li><a href="{{ route('character-loadouts.index') }}">Builds per Character</a></li>
                                <li><a href="#">Equipment per Character</a></li>
                                <li><a href="#">Throwables per Character</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="">
                        <h2>Guns</h2>
                        <div class="">
                            <ul>
                                <li><a href="{{ route('guns.index') }}">List of Guns</a></li>
                                <li><a href="#">Builds per Gun</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="">
                        <h2>Misc</h2>
                        <div class="">
                            <ul>
                                <li><a href="#">List of Overclocks</a></li>
                                <li><a href="#">List of Equipment</a></li>
                                <li><a href="#">List of Throwables</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>


@endsection
