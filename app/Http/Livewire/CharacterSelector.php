<?php

namespace App\Http\Livewire;

use App\Build;
use App\Character;
use Livewire\Component;

class CharacterSelector extends Component
{
    public $character_id;
    public $characters;

    public function mount($characters, Build $build = null)
    {
        $this->characters = $characters;

        if ($build) {
            $this->character_id = $build->character_id;
        }
    }

    public function render()
    {
        $selected = Character::with('guns')
            ->find($this->character_id);

        return view('livewire.character-selector', compact('selected'));
    }
}
