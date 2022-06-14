<?php

namespace App\View\Components;

use App\Patch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;

class LoadoutStats extends Component
{
    /**
     * @var Model
     */
    public $entity;
    /**
     * @var Model
     */
    public $comparison;
    /**
     * @var Patch
     */
    public $patch;

    public $totalLoadouts;

    public $previousTotal;

    public $loadoutLink;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($entity, $comparison, Patch $patch, $totalLoadouts, $previousTotal, $loadoutLink)
    {
        $this->entity = $entity;
        $this->comparison = $comparison;
        $this->patch = $patch;
        $this->totalLoadouts = $totalLoadouts;
        $this->previousTotal = $previousTotal;
        $this->loadoutLink = $loadoutLink;
    }

    public function calculatePercentage($current, $previous)
    {
        if ($previous === 0) {
            return 0.0;
        }

        return round(($current / $previous) * 100, 2);
    }

    public function calculateDifference($current, $previous)
    {
        if ($previous == 0) {
            return 0.0;
        }

        $diff = (($current - $previous) / $previous) * 100;

        return round($diff, 2);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.loadout-stats');
    }
}
