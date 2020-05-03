<div>
    @if($guns)
        <div class="grid grid-cols-2 gap-4 mt-4">
            @foreach($guns->groupBy('character_slot') as $character_slot => $slot)
                <div>
                    <h2 class="font-bold mb-2">Character Slot: {{ $character_slot }}</h2>
                    <div class="grid grid-cols-2 gap-4">
                    @foreach($slot as $gun)
                        <div wire:click="$set('selected{{ $gun->character_slot }}', {{ $gun->id }})"
                                type="button"
                                class="p-4 bg-gray-200 text-center cursor-pointer hover:bg-blue-100 border-2 select-none {{ $gun->id == $selected1 || $gun->id == $selected2 ? "bg-blue-100 border-blue-400" : "" }}">
                            <x-gun-icon :name="$gun->name" size="20" />
                            <br>
                            {{ $gun->name }} <br>
                            <i>{{ $gun->gun_class }}</i>
                        </div>
                    @endforeach
                    </div>
                </div>
            @endforeach
        </div>
        <div class="grid grid-cols-2 gap-4 mt-4">
            <div>
            @if ($selected1)
                @foreach ($guns->firstWhere('id', $selected1)->mods->groupBy('row')->all() as $rowNum => $row)
                    <div>
                        <p>Tier {{ $rowNum }}</p>
                        <div class="grid grid-cols-{{ count($row) }} gap-4">
                            @foreach($row as $mod)
                                @include('builds.partials.mod', ['mod' => $mod])
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @endif
            </div>

            <div>
            @if ($selected2)
                @foreach ($guns->firstWhere('id', $selected2)->mods->groupBy('row')->all() as $rowNum => $row)
                        <div>
                            <p>Tier {{ $rowNum }}</p>
                            <div class="grid grid-cols-{{ count($row) }} gap-4">
                                @foreach($row as $mod)
                                    @include('builds.partials.mod', ['mod' => $mod])
                                @endforeach
                            </div>
                        </div>
                @endforeach
            @endif
            </div>
        </div>
    @endif

</div>
