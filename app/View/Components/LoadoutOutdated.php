<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class LoadoutOutdated extends Component
{
    public function __construct(public bool $outdated) {}

    public function render(): View
    {
        return view('components.loadout-outdated');
    }
}
