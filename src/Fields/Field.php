<?php

namespace Painlesscode\Spider\Fields;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * @method Field readonly()
 */

abstract class Field
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $column;

    /**
     * @var mixed
     */
    public $value;

    /**
     * @var array
     */
    public $attributes = [];

    /**
     * @var array
     */
    public $rules = [];

    /**
     * @var array
     */
    public $rulesForStore = [];

    /**
     * @var array
     */
    public $rulesForUpdate = [];


    public $indexValueResolver;

    public $showValueResolver;

    public $visibleOn = [
        'index', 'create', 'show', 'edit'
    ];

    public function __construct($name, $column = null)
    {
        $this->name = $name;
        $this->column = $column ?? Str::snake($name);
    }

    public static function make()
    {
        return new static(...func_get_args());
    }

    public function value($value)
    {
        $this->value = $value;
        return $this;
    }

    public function required($required = true)
    {
        $this->attributes['required'] = $required;
        return $this;
    }

    /**
     * @param  callable|array|string  $rules
     * @return $this
     */
    public function rules($rules)
    {
        $this->rules = ($rules instanceof Rule || is_string($rules)) ? func_get_args() : $rules;
        return $this;
    }

    /**
     * @param  callable|array|string  $rules
     * @return $this
     */
    public function rulesForStore($rules)
    {
        $this->rulesForStore = ($rules instanceof Rule || is_string($rules)) ? func_get_args() : $rules;
        return $this;
    }

    /**
     * @param  callable|array|string  $rules
     * @return $this
     */
    public function rulesForUpdate($rules)
    {
        $this->rulesForUpdate = ($rules instanceof Rule || is_string($rules)) ? func_get_args() : $rules;
        return $this;
    }

    /**
     * @return array
     */
    public function getRulesForStore()
    {
        return array_merge($this->rules, $this->rulesForStore);
    }

    /**
     * @return array
     */
    public function getRulesForUpdate()
    {
        return array_merge($this->rules, $this->rulesForUpdate);
    }

    /**
     * @param array $only
     * @return $this
     */
    public function only($only = [])
    {
        $this->visibleOn = $only;
        return $this;
    }

    /**
     * @param array $except
     * @return $this
     */
    public function except($except = [])
    {
        $this->visibleOn = array_diff($this->visibleOn, $except);
        return $this;
    }

    /**
     * @param $context
     * @return bool
     */
    public function isVisible($context)
    {
        return in_array($context, $this->visibleOn);
    }

    /**
     * @param  string $context
     * @return bool
     */
    public function isRequired($context)
    {
        if (isset($this->attributes['required']) && (
            is_callable($this->attributes['required'])
                ? call_user_func($this->attributes['required'], $context)
                : $this->attributes['required'])
        ) {
            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    public function getAttributes($context)
    {
        $this->attributes['required'] = $this->isRequired($context);
        return $this->attributes;
    }

    public function indexValueResolver(\Closure $callable)
    {
        $this->indexValueResolver = $callable;
        return $this;
    }

    public function showValueResolver(\Closure $callable)
    {
        $this->showValueResolver = $callable;
        return $this;
    }

    public function __call($name, $arguments)
    {
        $this->attributes[$name] = $arguments[0] ?? true;
        return $this;
    }

    public function when($value, $callback, $default = null)
    {
        if ($value) {
            return $callback($this, $value) ?: $this;
        } elseif ($default) {
            return $default($this, $value) ?: $this;
        }
        return $this;
    }
}
