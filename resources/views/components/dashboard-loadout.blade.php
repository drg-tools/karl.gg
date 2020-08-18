<div class="myLoadoutsContainer">
    <div class="loadoutCardContainer">
        <div class="titleRow">
            <div class="titleContentLeft">
                <img src="/assets/img/{{ $loadout->character->image }}.png" class="classIcon"/>
                <h2 class="buildName">{{ $loadout->name }}</h2>
            </div>
            <div class="titleContentRight">
                <div class="weaponContainer">
                    @if($firstWeaponImage())
                    <img src="/assets/{{ $firstWeaponImage() }}.svg">
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
                    @if($secondWeaponImage())
                        <img src="/assets/{{ $secondWeaponImage() }}.svg">
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
