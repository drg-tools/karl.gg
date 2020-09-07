<div class="featuredLoadoutsContainer">
    <h1 class="uppercase text-center">{{$title}}</h1>
    <div class="cardGroups flex flex-wrap mb-4 -mx-1">
        @foreach($loadoutList as $characterLoadouts)
        <div class="loadoutCards w-full lg:w-1/2 px-1">
            @foreach($characterLoadouts as $loadout)
            <x-dashboard-loadout :loadout="$loadout"/>
            @endforeach
        </div>
        @endforeach
    </div>
</div>