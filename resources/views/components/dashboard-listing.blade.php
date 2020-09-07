<div class="featuredLoadoutsContainer">
    <h1 class="uppercase text-center">{{$title}}</h1>
    <div class="cardGroups flex flex-wrap mb-4 -mx-1">
        @foreach($loadoutList as $loadout)
        <div class="loadoutCards w-full lg:w-1/2 px-1">
            <x-dashboard-loadout :loadout="$loadout"/>
        </div>
        @endforeach
    </div>
</div>