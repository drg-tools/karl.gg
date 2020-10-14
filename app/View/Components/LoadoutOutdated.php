<?php

namespace App\View\Components;

use App\Patch;
use Illuminate\View\Component;

class LoadoutOutdated extends Component
{
    /**
     * @var Patch
     */
    private $patch;

    public $loadout;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Patch $patch, $loadout)
    {
        $this->patch = $patch;
        $this->loadout = $loadout;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        $outdated = $this->loadout->patch_id !== $this->patch->current()->id;

        return view('components.loadout-outdated', [
            'outdated' => $outdated,
        ]);
    }
}
