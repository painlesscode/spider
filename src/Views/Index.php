<?php

namespace Painlesscode\Spider\Views;

use Painlesscode\Spider\Fields\Field;

class Index
{
    /**
     * @var mixed
     */
    public $query;

    /**
     * @var string
     */
    public $name;

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

    public function __construct($name, $query)
    {
        $this->query = $query;
        $this->name = $name;
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
        return view('spider::index', $this->viewData + [
            'name' => $this->name,
            'routeName' => $this->routeName,
            'layout' => $this->layout,
            'fields' => $this->fields,
            'items' => $this->query->paginate(min(request()->get('per_page', 10), 1000))
        ]);
    }
}
