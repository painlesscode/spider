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

    /**
     * @var callable
     */
    public $afterStoreCallback;

    /**
     * @var callable
     */
    public $afterUpdateCallback;

    /**
     * @var callable
     */
    public $afterDestroyCallback;

    /**
     * @var callable
     */
    public $indexQueryModifier;

    public function __construct($name, $model, $routeName = null)
    {
        $this->name = $name;
        $this->model = $model;
        $this->routeName = $routeName ?? strtolower($name);
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

    public function afterStore(callable $callback)
    {
        $this->afterStoreCallback = $callback;
        return $this;
    }

    public function afterUpdate(callable $callback)
    {
        $this->afterUpdateCallback = $callback;
        return $this;
    }

    public function afterDestroy(callable $callback)
    {
        $this->afterDestroyCallback = $callback;
        return $this;
    }

    public function indexQueryModifier(callable $callback)
    {
        $this->indexQueryModifier = $callback;
        return $this;
    }
}
