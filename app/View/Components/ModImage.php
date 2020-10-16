<?php

namespace App\View\Components;

use App\Mod;
use Illuminate\View\Component;

class ModImage extends Component
{
    /**
     * @var Mod
     */
    public $mod;

    /**
     * Create a new component instance.
     *
     * @param $mod
     */
    public function __construct($mod)
    {
        $this->mod = $mod;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.mod-image');
    }
}
