<?php

namespace App\View\Components;

use App\Character;
use App\Patch;
use Illuminate\View\Component;

class CharacterStats extends Component
{
    /**
     * @var Character
     */
    public $character;
    /**
     * @var Character
     */
    public $comparison;
    /**
     * @var Patch
     */
    public $patch;

    public $totalLoadouts;

    public $previousTotal;


    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Character $character, Character $comparison, Patch $patch, $totalLoadouts, $previousTotal)
    {

        $this->character = $character;
        $this->comparison = $comparison;
        $this->patch = $patch;
        $this->totalLoadouts = $totalLoadouts;
        $this->previousTotal = $previousTotal;
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
        return view('components.character-stats');
    }
}
