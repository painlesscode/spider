<?php

namespace Painlesscode\Spider\Fields\Utils;

class Option
{
    public $value;
    public $label;
    public $parent;

    public function __construct($value, $label = null)
    {
        $this->value = $value;
        $this->label = $label ?? $this->value;
    }

    public static function make($value, $label = null)
    {
        return new self($value, $label);
    }

    public function parent($parent)
    {
        $this->parent = $parent;
        return $this;
    }
}
