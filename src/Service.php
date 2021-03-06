<?php

namespace Sands\Asasi;

use Illuminate\Database\Eloquent\Model;
use Sands\Asasi\Service\Exceptions\ServiceException;

class Service
{
    public static function create(Model $model, $data, $params = [])
    {
        $model->fill($data);
        $model->fill($params);

        if (!$model->save()) {
            throw new ServiceException('Creating ' . Model::class, $data);
        }

        if (method_exists(static::class, 'created')) {
            return forward_static_call_array([static::class, 'created'], func_get_args());
        }

        return $model;
    }

    public static function update(Model $model, $data, $params = [])
    {
        $model->fill($data);
        $model->fill($params);

        if (!$model->save()) {
            throw new ServiceException('Updating ' . Model::class, $data);
        }

        if (method_exists(static::class, 'updated')) {
            return forward_static_call_array([static::class, 'updated'], func_get_args());
        }

        return $model;
    }

    public static function duplicate(Model $model, $params = [])
    {
        $model = $model->replicate();
        if (in_array('Cviebrock\EloquentSluggable\SluggableTrait', class_uses($model))) {
            $model->resluggify();
        }

        $model->fill($params);
        if (!$model->save()) {
            throw new ServiceException('Duplicating ' . Model::class, $data);
        }

        if (method_exists(static::class, 'duplicated')) {
            $parameters = func_get_args();
            $parameters[0] = $model;
            return forward_static_call_array([static::class, 'duplicated'], $parameters);
        }

        return $model;
    }

    public static function delete(Model $model)
    {
        if (!$model->delete()) {
            throw new ServiceException('Removing ' . Model::class);
        }

        if (method_exists(static::class, 'deleted')) {
            return forward_static_call_array([static::class, 'deleted'], func_get_args());
        }

        return $model;
    }

    public static function restore(Model $model)
    {
        if (!in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($model)) || !$model->restore()) {
            throw new ServiceException('Restoring ' . Model::class);
        }

        if (method_exists(static::class, 'restored')) {
            return forward_static_call_array([static::class, 'restored'], func_get_args());
        }

        return $model;
    }
}
