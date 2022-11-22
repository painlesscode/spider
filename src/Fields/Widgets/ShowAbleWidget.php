<?php

namespace Painlesscode\Spider\Fields\Widgets;

use Illuminate\Support\HtmlString;
use Illuminate\View\View;
use Painlesscode\Spider\Fields\Field;

abstract class ShowAbleWidget extends Field
{
    public $visibleOn = [
        'show'
    ];

    public function only($only = [])
    {
        throw new \RuntimeException('Trying to change visibility of '.static::class);
    }

    public function except($except = [])
    {
        throw new \RuntimeException('Trying to change visibility of '.static::class);
    }

    /**
     * @return string
     */
    public abstract function getComponentForShow();
}
