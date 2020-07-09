<?php

namespace App\View\Components;

use Illuminate\Support\Str;
use Illuminate\View\Component;

class CharacterIcon extends Component
{
    public $name;

    public $size;

    /**
     * Create a new component instance.
     *
     * @param $name
     * @param string $size
     */
    public function __construct($name, $size = '6')
    {
        $this->name = strtolower(Str::slug($name, '_'));
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
