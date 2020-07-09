<label for="{{ $mod->gun->character_slot }}-mod-{{ $mod->row }}-{{ $mod->column }}"
       class="flex justify-evenly cursor-pointer mt-4 mb-4 pt-4 pb-4 border-2 block text-center bg-gray-200">
    <x-mod-icon :mod="$mod" size="10" />
    <input type="radio"
           value="{{ $mod->id }}"
           {{ $checked ? "checked" : "" }}
           name="mods[{{ $mod->gun->character_slot }}][{{ $mod->row }}]"
           id="{{ $mod->gun->character_slot }}-mod-{{ $mod->row }}-{{ $mod->column }}"/>
    <span class="text-left">
        {{ $mod->name }} <br>
        <span class="text-green-700 leading-5">{{ $mod->effect }}</span>
    </span>
</label>
