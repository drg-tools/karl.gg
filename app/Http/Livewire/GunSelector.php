<?php

namespace App\Http\Livewire;

use Livewire\Component;

class GunSelector extends Component
{

    public $guns;
    public $selected1;
    public $selected2;

    public function mount($guns)
    {
        $this->guns = $guns;
    }

    public function render()
    {
        return view('livewire.gun-selector');
    }
}
