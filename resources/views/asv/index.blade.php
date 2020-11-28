@extends('layouts.asv')

@section('content')

    <div class="featuredLoadoutsContainer">
        {{-- {{var_dump($build)}} --}}
        <h2 class="equipmentCardTitle">{{ $gun[0]->name }}</h2>
        <div class="flexboxWeaponSelect">
            <svg xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 0 180 90"
                 class="asvIcon"
                 height="90%"
                 preserveAspectRatio="xMidYMid meet"
                 v-html="'{{ asset('/assets/'.$gun[0]->image.'.svg') }}'"></svg>

            {{-- <img src={{ asset('/assets/'.$gun[0]->image.'.svg') }} class="equipmentIcon" width="180px" height="90%"/> --}}
        </div>
        <div class="modMatrixContainer">
            
            @foreach ($modMatrix['gun_mods'] as $key => $mod_tier)
            <div class="modMatrixRow">
                @foreach ($mod_tier as $mod)
                <div class="mod" v-tooltip="{
                    content:'<h3>{{$mod->mod_name}}</h3><br><span>{{addslashes($mod->text_description)}}</span></p>',
                    placement: 'right',
                    trigger: 'hover'
                  }">
                    
                        @if ($modMatrix['selected_index'][$key]['value'] == $mod->mod_index)
                                <svg viewBox="0 0 80 50"
                                        height="100%"
                                        role="img"
                                        class="modActive"
                                >
                                    <path
                                        d=" M 0.3679663,25 13.7826,0.609756 H 66.221625 L 79.636259,25 66.221625,49.390244 H 13.7826L 0.3679663,25"/>
                                </svg>
                    @else
                    <svg viewBox="0 0 80 50"
                                        height="100%"
                                        role="img"
                                        class="modInactive"
                                >
                                    {{-- <title>{{ mod.mod_name }}</title> --}}
                                    {{-- <desc>{{ mod.text_description }}</desc> --}}
                                    <path
                                        d=" M 0.3679663,25 13.7826,0.609756 H 66.221625 L 79.636259,25 66.221625,49.390244 H 13.7826L 0.3679663,25"/>
                                </svg>
                    @endif
                            </div><!-- end mod  -->
                @endforeach
                </div><!-- end mod matrix row -->
            @endforeach
        </div><!-- end mod matrix container -->
    </div>

@endsection