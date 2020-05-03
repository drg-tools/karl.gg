<label for="{{ $mod->gun->character_slot }}-mod-{{ $mod->row }}-{{ $mod->column }}"
       class="cursor-pointer mt-4 mb-4 pt-4 pb-4 border-2 block text-center">
    <input type="radio"
           value="{{ $mod->id }}"
           name="mods[{{ $mod->gun->character_slot }}][{{ $mod->row }}]"
           id="{{ $mod->gun->character_slot }}-mod-{{ $mod->row }}-{{ $mod->column }}"/>
    {{ $mod->name }}
</label>
