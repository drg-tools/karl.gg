<?php

namespace Hazzard\Comments\Events;

use s9e\TextFormatter\Configurator;

class FormatterConfigurator
{
    /**
     * @var \s9e\TextFormatter\Configurator
     */
    public $configurator;

    /**
     * Create a new event instance.
     *
     * @param  \s9e\TextFormatter\Configurator  $configurator
     */
    public function __construct(Configurator $configurator)
    {
        $this->configurator = $configurator;
    }
}
