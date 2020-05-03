<?php

namespace App\Http\Livewire;

use Livewire\Component;

class GunSelector extends Component
{

    public $guns;
    public $selected;
    public $build;

    public function mount($guns, $selected = [], $build = null)
    {
        $this->guns = $guns;
        $this->selected = $selected;
        $this->build = $build;
    }

    public function setSelected($character_slot, $gun_id)
    {
        $this->selected[$character_slot] = $gun_id;
        $this->emit('gunSelected');
    }

    public function render()
    {
        return view('livewire.gun-selector');
    }
}
