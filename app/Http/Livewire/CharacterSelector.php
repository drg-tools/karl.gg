<?php

namespace App\Http\Livewire;

use App\Character;
use Livewire\Component;

class CharacterSelector extends Component
{
    public $character_id;
    public $characters;
    public $build;

    public function mount($characters, $build = null)
    {
        $this->characters = $characters;
        $this->build = $build;

        if ($build) {
            $this->character_id = $build->character_id;
        }
    }

    public function getSelectedCharacterProperty()
    {
        return Character::with('guns')
            ->find($this->character_id);
    }

    public function render()
    {
        return view('livewire.character-selector');
    }
}
