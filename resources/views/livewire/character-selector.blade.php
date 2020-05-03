
<div>
    <p class="font-bold">Select a Character</p>
    <div class="grid grid-cols-4 gap-4 mt-4">
        @foreach($characters as $character)
            <div wire:click="$set('character_id', {{ $character->id }})" wire:key="{{ $character->id }}"
                 class="p-4 text-center cursor-pointer hover:bg-blue-100 border-2 select-none {{ $character->id == $character_id ? "bg-blue-100 border-blue-400" : "" }}">
                <x-character-icon name="{{ $character->name }}" size="w-10"/>
                <br>
                <p class="text-sm leading-5 text-gray-900 font-bold">{{ $character->name }}</p>
            </div>
        @endforeach
    </div>

    <input type="hidden" name="character_id" value="{{ $character_id }}">

    @if($selected)
        @livewire('gun-selector', ['guns' => $selected->guns], key($selected->id))
    @endif
</div>
