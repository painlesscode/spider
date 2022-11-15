<?php

namespace Painlesscode\Spider\Fields;

class Select extends Field
{
    protected $options = [];

    public function options($options = []) {
        $this->options = $options;
        return $this;
    }

    public function getOptions() {
        return $this->options;
    }

    public function dependOn($parent)
    {
        $this->attributes['depend-on'] = $parent;
        return $this;
    }
}
