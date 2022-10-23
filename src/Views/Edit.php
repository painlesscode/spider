<?php

namespace Painlesscode\Spider\Views;

use Illuminate\Database\Eloquent\Model;
use Painlesscode\Spider\Fields\Field;

class Edit
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var Model
     */
    public $model;

    /**
     * @var string
     */
    public $routeName;

    /**
     * @var array
     */
    public $viewData = [];

    /**
     * @var array<Field>
     */
    public $fields = [];

    /**
     * @var string
     */
    public $layout = 'app-layout';

    public function __construct($name, Model $model)
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

    public function render()
    {
        return view('spider::edit', $this->viewData + [
            'name' => $this->name,
            'model' => $this->model,
            'routeName' => $this->routeName,
            'layout' => $this->layout,
            'fields' => $this->fields,
        ]);
    }
}
