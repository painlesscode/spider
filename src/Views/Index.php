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

    public string $routeName;

    public array $viewData = [];

    public array $fields = [];

    public string $layout = 'app-layout';

    protected array $singleActions = [];

    protected bool $lengthAwarePaginator = false;

    protected ?string $search = null;

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
    public function routeName($routeName): Index
    {
        $this->routeName = $routeName;
        return $this;
    }

    public function fields($fields): Index
    {
        $this->fields = $fields;
        return $this;
    }

    public function useLayout($layout): Index
    {
        $this->layout = $layout;
        return $this;
    }

    public function singleActions($actions): Index
    {
        $this->singleActions = $actions;
        return $this;
    }

    public function useLengthAwarePaginator($shouldUse = true): Index
    {
        $this->lengthAwarePaginator = $shouldUse;
        return $this;
    }

    public function search(string $search): Index
    {
        $this->search = $search;
        return $this;
    }

    public function render()
    {
        return view('spider::index', $this->viewData + [
            'name' => $this->name,
            'routeName' => $this->routeName,
            'layout' => $this->layout,
            'fields' => $this->fields,
            'search' => $this->search,
            'singleActions' => $this->singleActions,
            'items' => $this->lengthAwarePaginator
                ? $this->query->paginate(min(request()->get('per_page', 10), 1000))
                : $this->query->simplePaginate(min(request()->get('per_page', 10), 1000))
        ]);
    }
}
