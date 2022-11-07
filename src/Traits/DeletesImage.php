<?php

namespace Painlesscode\Spider\Traits;

use Illuminate\Database\Eloquent\Model;
use Painlesscode\Spider\Fields\Utils\Image;

/**
 * @method static deleting(\Closure $param)
 */
trait DeletesImage
{
    protected static function booted()
    {
        static::deleting(function (Model $model) {
            $imageFields = array_keys(
                array_filter($model->getCasts(), function ($cast) {
                    return str_contains($cast, 'ImageField');
                })
            );
            foreach ($imageFields as $field) {
                Image::delete($model, $field);
            }
        });
    }
}

