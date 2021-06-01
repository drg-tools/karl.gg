<div>
    <h2 class="text-gray-300">{{ $title }}</h2>
    <div class="shadow overflow-hidden bg-gray-700 text-gray-300 sm:rounded-md">
        <ul class="divide-y divide-gray-900">
            @foreach($loadoutList as $loadout)
                <li>
                    <x-clickable-loadout :loadout="$loadout"/>
                </li>
            @endforeach
        </ul>
    </div>
</div>
