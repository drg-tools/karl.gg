
<div class="shadow overflow-hidden bg-gray-700 text-gray-300 sm:rounded-md">
    <ul class="divide-y divide-gray-900">
        @foreach($loadoutList as $loadout)
            <x-dashboard-loadout :loadout="$loadout"/>
        @endforeach
    </ul>
</div>
