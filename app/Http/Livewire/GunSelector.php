<?php

namespace App\Http\Livewire;

use Livewire\Component;

class GunSelector extends Component
{

    public $guns;
    public $selected1;
    public $selected2;

    public function mount($guns, $selected1 = null, $selected2 = null)
    {
        $this->guns = $guns;
        $this->selected1 = $selected1;
        $this->selected2 = $selected2;
    }

    public function render()
    {
        return view('livewire.gun-selector');
    }
}
