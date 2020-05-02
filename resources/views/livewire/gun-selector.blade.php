<div>
    @if($guns)
        <div class="grid grid-cols-4 gap-4 mt-4">
            @foreach($guns->groupBy('character_slot') as $slot)
                @foreach($slot as $gun)
                    <button wire:click="$set('selected{{ $gun->character_slot }}', {{ $gun->id }})" type="button" class="h-20 cursor-pointer bg-blue-100">
                        {{ $gun->name }} <br>
                        <i>{{ $gun->gun_class }}</i>
                    </button>
                @endforeach
            @endforeach
        </div>

            @if ($selected1)
                @foreach ($guns->firstWhere('id', $selected1)->mods->groupBy('row')->all() as $row)
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        @foreach($row as $mod)
                            <label for="{{ $mod->gun->character_slot }}-mod-{{ $mod->row }}-{{ $mod->column }}"
                                   class="cursor-pointer h-12 block bg-blue-100">
                                <input type="radio" value="{{ $mod->id }}" name="mods[{{ $mod->gun->character_slot }}][{{ $mod->row }}]" id="{{ $mod->gun->character_slot }}-mod-{{ $mod->row }}-{{ $mod->column }}"/>
                                {{ $mod->name }}
                            </label>
                        @endforeach
                    </div>
                @endforeach
            @endif

            @if ($selected2)
                @foreach ($guns->firstWhere('id', $selected2)->mods->groupBy('row')->all() as $row)
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        @foreach($row as $mod)
                            <label for="{{ $mod->gun->character_slot }}-mod-{{ $mod->row }}-{{ $mod->column }}"
                                   class="cursor-pointer h-12 block bg-blue-100">
                                <input type="radio" value="{{ $mod->id }}" name="mods[{{ $mod->gun->character_slot }}][{{ $mod->row }}]" id="{{ $mod->gun->character_slot }}-mod-{{ $mod->row }}-{{ $mod->column }}"/>
                                {{ $mod->name }}
                            </label>
                        @endforeach
                    </div>
                @endforeach
            @endif

    @endif

</div>
