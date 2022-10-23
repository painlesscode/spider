<?php

namespace Painlesscode\Spider\Resource;

use Painlesscode\Spider\Fields\Field;

class Resource
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $model;

    /**
     * @var string
     */
    public $routeName;

    /**
     * @var array<Field>
     */
    public $fields = [];

    /**
     * @var string
     */
    public $layout = 'app-layout';

    /**
     * @var callable
     */
    public $afterStoreValidationCallback;

    /**
     * @var callable
     */
    public $afterUpdateValidationCallback;

    public function __construct($name, $model)
    {
        $this->name = $name;
        $this->model = $model;
        $this->routeName = strtolower($name);
    }

    /**
     * @param string $routeName
     * @return $this
     */
    public function routeName($routeName) {
        $this->routeName = $routeName;
        return $this;
    }

    public function fields($fields)
    {
        $this->fields = $fields;
        return $this;
    }

    public function useLayout($layout)
    {
        $this->layout = $layout;
        return $this;
    }

    public function afterStoreValidation(callable $callback)
    {
        $this->afterStoreValidationCallback = $callback;
        return $this;
    }

    public function afterUpdateValidation(callable $callback)
    {
        $this->afterUpdateValidationCallback = $callback;
        return $this;
    }
}
