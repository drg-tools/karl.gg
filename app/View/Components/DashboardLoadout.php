<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DashboardLoadout extends Component
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

    public function weapons($index)
    {
        $groupedByGun = $this->loadout->mods->groupBy('gun_id');
        $weapons = [];

        foreach ($groupedByGun as $gunId => $mods) {
            $weapons[] = $mods->first()->gun;
        }

        if (array_key_exists($index, $weapons)) {
            return $weapons[$index];
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.dashboard-loadout');
    }
}
