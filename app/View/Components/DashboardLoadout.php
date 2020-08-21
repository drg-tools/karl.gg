<?php

namespace App\View\Components;

use App\Gun;
use Illuminate\Support\Arr;
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

    public function weaponImages($index)
    {
        $groupedByGun = $this->loadout->mods->groupBy('gun_id');
        $images = [];

        foreach ($groupedByGun as $gunId => $mods) {
            $images[] = $mods->first()->gun->image;
        }

        if (array_key_exists($index, $images)) {
            return $images[$index];
        }

        return null;
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
