<a href="{{ route('preview.show', $loadout->id) }}" class="w-full">
    <div class="myLoadoutsContainer">
        <div class="loadoutCardContainer">
            <div class="titleRow">
                <div class="titleContentLeft">
                    <img src="/assets/img/{{ $loadout->character->image }}.png" class="classIcon"
                         alt="{{ $loadout->character->name }} icon"/>
                    <h2 class="buildName text-lg">{{ Str::limit($loadout->name, 30) }}</h2>
                </div>
                <div class="titleContentRight">
                    <div class="weaponContainer">
                        @if($weapons(0))
                            <img src="/assets/{{ $weapons(0)->image }}.svg" alt="{{ $weapons(0)->name }} icon" />
                        @endif
                    </div>
                </div>
            </div>
            <div class="subtitleRow">
                <div class="titleContentLeft">
                    <h2 class="salutes">{{ $loadout->votes_count }}</h2>
                    <h2 class="author text-sm">{{ $loadout->creator->name ?? 'Anonymous' }}</h2>
                </div>
                <div class="titleContentRight">
                    <div class="weaponContainer">
                        @if($weapons(1))
                            <img src="/assets/{{ $weapons(1)->image }}.svg" alt="{{ $weapons(1)->name }} icon" />
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</a>
