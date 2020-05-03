<label for="{{ $mod->gun->character_slot }}-mod-{{ $mod->row }}-{{ $mod->column }}"
       class="flex justify-evenly cursor-pointer mt-4 mb-4 pt-4 pb-4 border-2 block text-center bg-gray-200">
    <span class="text-black inline-black w-6">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
            <path d="M0 10a10 10 0 1 1 20 0 10 10 0 0 1-20 0zm16.32-4.9L5.09 16.31A8 8 0 0 0 16.32 5.09zm-1.41-1.42A8 8 0 0 0 3.68 14.91L14.91 3.68z"></path></svg>
    </span>
    <input type="radio"
           value="{{ $mod->id }}"
           name="mods[{{ $mod->gun->character_slot }}][{{ $mod->row }}]"
           id="{{ $mod->gun->character_slot }}-mod-{{ $mod->row }}-{{ $mod->column }}"/>
    <span>
        {{ $mod->name }} <br>
        <span class="text-gray-700 leading-5">{{ $mod->effect }}</span>
    </span>
</label>
