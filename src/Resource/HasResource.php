<?php

namespace Painlesscode\Spider\Resource;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Painlesscode\Reporter\Facades\Reporter;
use Painlesscode\Spider\Fields\Field;
use Painlesscode\Spider\Views\Create;
use Painlesscode\Spider\Views\Edit;
use Painlesscode\Spider\Views\Index;
use Painlesscode\Spider\Views\Show;

/**
 * @property Resource $resource
 */
trait HasResource
{
    public function index()
    {
        return (new Index($this->resource->name, $this->resource->model::query()))
            ->fields(array_filter($this->resource->fields, function (Field $field) {
                return $field->isVisible('index');
            }))
            ->useLayout($this->resource->layout)
            ->routeName($this->resource->routeName)
            ->render();
    }


    public function create()
    {
        return (new Create($this->resource->name))
            ->fields(array_filter($this->resource->fields, function (Field $field) {
                return $field->isVisible('create');
            }))
            ->useLayout($this->resource->layout)
            ->routeName($this->resource->routeName)
            ->render();
    }


    public function store(Request $request)
    {
        $validated = $request->validate(
            collect($this->resource->fields)->filter->isVisible('create')->mapWithKeys(function (Field $field) {
                return [$field->column => $field->rules + $field->rulesForStore];
            })->all()
        );

        $validated = $this->resource->afterStoreValidationCallback
            ? call_user_func($this->resource->afterStoreValidationCallback, $validated)
            : $validated;

        return response()->report($this->resource->model::create($validated), Str::title($this->resource->name).' Created Successfully');
    }


    public function show($modelKey)
    {
        $model = $this->resource->model::findOrFail($modelKey);

        return (new Show($this->resource->name, $model))
            ->fields(array_filter($this->resource->fields, function (Field $field) {
                return $field->isVisible('show');
            }))
            ->useLayout($this->resource->layout)
            ->routeName($this->resource->routeName)
            ->render();
    }


    public function edit($modelKey)
    {
        $model = $this->resource->model::findOrFail($modelKey);

        return (new Edit($this->resource->name, $model))
            ->fields(array_filter($this->resource->fields, function (Field $field) {
                return $field->isVisible('edit');
            }))
            ->useLayout($this->resource->layout)
            ->routeName($this->resource->routeName)
            ->render();
    }


    public function update(Request $request, $modelKey)
    {
        $model = $this->resource->model::findOrFail($modelKey);

        $validated = $request->validate(
            collect($this->resource->fields)->filter->isVisible('edit')->mapWithKeys(function (Field $field) {
                return [$field->column => $field->rules + $field->rulesForUpdate];
            })->all()
        );

        $validated = $this->resource->afterUpdateValidationCallback
            ? call_user_func($this->resource->afterUpdateValidationCallback, $validated)
            : $validated;

        return response()->report($model->update($validated), Str::title($this->resource->name).' Updated Successfully');
    }


    public function destroy($modelKey)
    {
        $model = $this->resource->model::findOrFail($modelKey);

        $deleted = false;
        try {
            $deleted = $model->delete();
        } catch (QueryException $ex) {
            if (str_contains($ex->getMessage(), 'Integrity constraint violation')) {
                Reporter::error('Entity already is being used somewhere else');
            }
        }

        return response()->report($deleted, Str::title($this->resource->name).' Deleted Successfully');
    }
}
