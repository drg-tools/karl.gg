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

    public function firstWeaponImage()
    {
        $possibleGunIds = $this->loadout->mods->groupBy('gun_id')->keys();
        $gunId = Arr::get($possibleGunIds, 0);

        if (! $gunId) {
            return null;
        }

        return Gun::find($gunId)->image;
    }

    public function secondWeaponImage()
    {
        $possibleGunIds = $this->loadout->mods->groupBy('gun_id')->keys();
        $gunId = Arr::get($possibleGunIds, 1);

        if (! $gunId) {
            return null;
        }

        return Gun::find($gunId)->image;
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
