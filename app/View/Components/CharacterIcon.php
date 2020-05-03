<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CharacterIcon extends Component
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $size;

    /**
     * Create a new component instance.
     *
     * @param string $name
     * @param string $size
     */
    public function __construct($name, $size = "w-6")
    {
        $this->name = $name;
        $this->size = $size;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.character-icon');
    }
}
