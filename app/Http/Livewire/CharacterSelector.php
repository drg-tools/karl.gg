<?php

namespace App\Http\Livewire;

use App\Character;
use Livewire\Component;

class CharacterSelector extends Component
{
    public $character_id;
    public $characters;

    public function mount($characters, $character_id = null)
    {
        $this->characters = $characters;
        $this->character_id = $character_id;
    }

    public function render()
    {
        $selected = Character::with('guns')
            ->find($this->character_id);

        return view('livewire.character-selector', compact('selected'));
    }
}
