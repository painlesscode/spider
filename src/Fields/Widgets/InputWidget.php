<?php

namespace Painlesscode\Spider\Fields\Widgets;

use Illuminate\Support\HtmlString;
use Illuminate\View\View;
use Painlesscode\Spider\Fields\Field;

abstract class InputWidget extends Field
{
    protected $visibleOn = [
        'create','edit'
    ];

    public function only($only = [])
    {
        throw_if(array_diff($only, $this->visibleOn), 'InputWidget only be available in create or edit context');
    }

    /**
     * @return string
     */
    public abstract function getComponentForCreate();

    /**
     * @return string
     */
    public abstract function getComponentForEdit();
}
