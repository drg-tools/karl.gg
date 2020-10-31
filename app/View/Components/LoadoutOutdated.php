<?php

namespace App\View\Components;

use App\Patch;
use Illuminate\View\Component;

class LoadoutOutdated extends Component
{

    public $loadout;

    /**
     * Create a new component instance.
     *
     * @param $loadout
     */
    public function __construct($loadout)
    {
        $this->loadout = $loadout;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        $outdated = $this->loadout->patch_id !== Patch::current()->id;

        return view('components.loadout-outdated', [
            'outdated' => $outdated,
        ]);
    }
}
