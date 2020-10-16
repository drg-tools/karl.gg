@extends('layouts.app')

@section('content')

    <div class="content-container">
        @foreach($characters as $character)
            <div class="border-b  mx-4">
                <div class="flex mb-4">
                    <div class="mt-4">
                        <img src="/assets/img/{{ $character->image }}.png" alt="{{ $character->name }} icon">
                    </div>
                    <h2 class="ml-4 pb-2 border-b-4">{{ $character->name }}</h2>
                </div>

                <h3>Recommended Builds</h3>
                <div class="grid grid-flow-row grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($loadouts as $loadout)
                        <div class="p-4">
                            <h4><a href="{{ route('preview.show', $loadout->id) }}">{{ $loadout->name }}</a></h4>
                            <p>This build is really good for blah blah blah...lorem Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid blanditiis delectus dicta ducimus excepturi expedita fugiat in incidunt, modi nesciunt nulla optio, recusandae repellendus soluta, tempora tenetur ullam ut voluptate! </p>
                            <div class="flex justify-around">
                                <div class="w-1/4"><img class="img-filter-white" src="/assets/E_P1_Warthog.svg" alt="Warthog icon" /></div>
                                <div class="w-1/4"><img class="img-filter-white" src="/assets/E_P1_Warthog.svg" alt="Warthog icon" /></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endsection
