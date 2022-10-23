<?php

namespace Painlesscode\Spider\Fields;

class ID extends Field
{
    public function __construct($name = 'id', $column = null)
    {
        parent::__construct($name, $column);
    }

    public static function make($name = 'id', $column = null)
    {
        return parent::make($name, $column);
    }
}
