<?php

namespace Painlesscode\Spider\Resource;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Painlesscode\Reporter\Facades\Reporter;
use Painlesscode\Spider\Fields\Field;
use Painlesscode\Spider\SingleAction;
use Painlesscode\Spider\Views\Create;
use Painlesscode\Spider\Views\Edit;
use Painlesscode\Spider\Views\Index;
use Painlesscode\Spider\Views\Show;

/**
 * @property Resource $resource
 */
trait HasResource
{
    public function index(Request $request)
    {
        if (
            $request->filled('_action')
            && $request->filled('token')
            && $request->get('token') === csrf_token()
            && $model = $this->resource->model::find($request->get('id'))
        ) {
            $action = last(
                array_filter(
                    $this->resource->getSingleActions(),
                    fn(SingleAction $action) => $action->title === $request->get('_action')
                )
            );
            if ($action) {
                $result = $action->callUsing($model);
                if (is_null($result)) return response()->success('Action '.$action->title.' executed successfully');
                return  is_bool($result) ? response()->report($result, 'Action '.$action->title.' executed successfully') : $result;
            }
        }

        return (new Index($this->resource->name, $this->resource->model::query()->when(
            $this->resource->indexQueryModifier, $this->resource->indexQueryModifier
        )))
            ->fields(array_filter($this->resource->fields, fn(Field $field) => $field->isVisible('index')))
            ->singleActions($this->resource->getSingleActions())
            ->useLayout($this->resource->layout)
            ->routeName($this->resource->routeName)
            ->render();
    }


    public function create()
    {
        return (new Create($this->resource->name))
            ->fields(array_filter($this->resource->fields, fn(Field $field) => $field->isVisible('create')))
            ->useLayout($this->resource->layout)
            ->routeName($this->resource->routeName)
            ->render();
    }


    public function store(Request $request)
    {
        $validated = $request->validate(
            collect($this->resource->fields)->filter->isVisible('create')->mapWithKeys(
                fn(Field $field) => [$field->column => $field->rules + $field->rulesForStore]
            )->all()
        );

        try {
            DB::beginTransaction();

            $validated = $this->resource->afterStoreValidationCallback
                ? call_user_func($this->resource->afterStoreValidationCallback, $validated)
                : $validated;

            $model = $this->resource->model::create($validated);

            if($this->resource->afterStoreCallback) {
                call_user_func($this->resource->afterStoreCallback, $model);
            }

            $success = (bool) $model;

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            if ($exception instanceof ValidationException) throw $exception;
            report($exception);
            $success = false;
        }

        return response()->report($success, Str::title($this->resource->name).' Created Successfully');
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
        if (
            $request->filled('__action')
            && $action = last(array_filter($this->resource->getSingleActions(), fn($action) => $action['title'] === $request->get('__action')))
        ) {
            dd($action);
        }

        $model = $this->resource->model::findOrFail($modelKey);

        $validated = $request->validate(
            collect($this->resource->fields)->filter->isVisible('edit')->mapWithKeys(function (Field $field) {
                return [$field->column => $field->rules + $field->rulesForUpdate];
            })->all()
        );

        try {
            DB::beginTransaction();

            $validated = $this->resource->afterUpdateValidationCallback
                ? call_user_func($this->resource->afterUpdateValidationCallback, $validated)
                : $validated;

            $updated = $model->update($validated);

            if($updated && $this->resource->afterUpdateCallback) {
                call_user_func($this->resource->afterUpdateCallback, $model);
            }

            $success = $updated;

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            if ($exception instanceof ValidationException) throw $exception;
            report($exception);
            $success = false;
        }

        return response()->report($success, Str::title($this->resource->name).' Updated Successfully');
    }


    public function destroy($modelKey)
    {
        $model = $this->resource->model::findOrFail($modelKey);

        $deleted = false;
        try {
            DB::beginTransaction();
            $deleted = $model->delete();
            if ($deleted && $this->resource->afterDestroyCallback) {
                ($this->resource->afterDestroyCallback)();
            }
            DB::commit();
        } catch (QueryException $ex) {
            DB::rollBack();
            if (str_contains($ex->getMessage(), 'Integrity constraint violation')) {
                Reporter::error('Entity already is being used somewhere else');
            }
        }

        return response()->report($deleted, Str::title($this->resource->name).' Deleted Successfully');
    }
}
