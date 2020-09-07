<a href="{{ route('preview.show', $loadout->id) }}" class="w-full">
    <div class="myLoadoutsContainer">
	<div class="loadoutCardContainer">
	<div class="titleRow">
		<div class="titleContentLeft">
		    <img src="/assets/img/{{ $loadout->character->image }}.png" class="classIcon"/>
		    <h2 class="buildName">{{ Str::limit($loadout->name, 30) }}</h2>
                </div>
		<div class="titleContentRight">
		    <div class="weaponContainer">
			@if($weaponImages(0))
			    <img src="/assets/{{ $weaponImages(0) }}.svg">
			@endif
		    </div>
                </div>
            </div>
	    <div class="subtitleRow">
		<div class="titleContentLeft">
		    <h2 class="salutes">{{ $loadout->votes_count }}</h2>
		    <h2 class="author">{{ $loadout->creator->name ?? 'Anonymous' }}</h2>
		</div>
		<div class="titleContentRight">
		    <div class="weaponContainer">
			@if($weaponImages(1))
			    <img src="/assets/{{ $weaponImages(1) }}.svg">
			@endif
		    </div>
		</div>

	    </div>
        </div>
    </div>
</a>
