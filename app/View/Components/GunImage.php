<?php

namespace App\View\Components;

use App\Gun;
use Illuminate\View\Component;

class GunImage extends Component
{

    /**
     * @var Gun
     */
    public $gun;

    /**
     * Create a new component instance.
     *
     * @param $gun
     */
    public function __construct($gun)
    {
        $this->gun = $gun;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.gun-image');
    }
}
