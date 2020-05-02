<?php

namespace App\Http\Livewire;

use App\Character;
use Livewire\Component;

class CharacterSelector extends Component
{
    public $character_id;
    public $characters;

    public function mount($characters)
    {
        $this->characters = $characters;
    }

    public function render()
    {
        $selected = Character::with('guns')
            ->find($this->character_id);

        return view('livewire.character-selector', compact('selected'));
    }
}
