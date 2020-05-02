
<div>
    <p>Select a Character</p>
    <div class="grid grid-cols-4 gap-4 mt-4">
        @foreach($characters as $character)
            <button wire:click="$set('character_id', {{ $character->id }})" type="button" class="h-20 cursor-pointer bg-blue-100">{{ $character->name }}</button>
        @endforeach
    </div>

    <input type="hidden" name="character_id" value="{{ $character_id }}">

    @if($character_id)
        @livewire('gun-selector', ['guns' => $selected->guns])
    @endif
</div>
