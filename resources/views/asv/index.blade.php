@extends('layouts.asv')

@section('content')

    <div class="featuredLoadoutsContainer">
        {{-- {{var_dump($build)}} --}}
        @foreach ($modMatrix['gun_mods'] as $key => $mod_tier)
        <p>
            {{$key}}
            @foreach ($mod_tier as $mod)
                @if($modMatrix['selected_index'][$key]['selected'])
                    @if ($modMatrix['selected_index'][$key]['value'] == $mod->mod_index)
                        TRUE
                        @else
                        FALSE
                    @endif
                @else
                FALSE
                @endif
            @endforeach
        </p>
        @endforeach
        
         </equipment-card> 
    </div>

@endsection