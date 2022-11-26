<?php

namespace Painlesscode\Spider\Fields;

use Painlesscode\Spider\Fields\Widgets\InputWidget;

class InputAbleField extends InputWidget
{
    protected $component;

    public function __construct($component)
    {
        $this->component = $component;
    }

    public function getComponentForCreate()
    {
        return $this->component;
    }

    public function getComponentForUpdate()
    {
        return $this->component;
    }
}
