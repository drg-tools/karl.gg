<div>
    <h2 class="text-gray-300">{{ $title }}</h2>
    <div class="shadow overflow-hidden text-gray-300 sm:rounded-md">
        <ul>
            @foreach($loadoutList as $loadout)
                <x-profile-loadout :loadout="$loadout"/>
            @endforeach
        </ul>
    </div>
</div>
