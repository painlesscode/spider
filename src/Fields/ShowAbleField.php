<?php

namespace Painlesscode\Spider\Fields;

use Painlesscode\Spider\Fields\Widgets\ShowAbleWidget;

class ShowAbleField extends ShowAbleWidget
{
    protected $component;

    public function __construct($component)
    {
        $this->component = $component;
    }

    public function getComponentForShow()
    {
        return $this->component;
    }
}
