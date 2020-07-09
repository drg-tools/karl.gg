<?php

namespace App\View\Components;

use App\Mod;
use Illuminate\View\Component;

class ModIcon extends Component
{
    public $mod;

    public $size;

    /**
     * Create a new component instance.
     *
     * @param Mod $mod
     * @param string $size
     */
    public function __construct(Mod $mod, $size = '6')
    {
        $this->mod = $mod;
        $this->size = $size;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.mod-icon');
    }
}
