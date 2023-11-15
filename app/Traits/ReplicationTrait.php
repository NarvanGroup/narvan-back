<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Str;

trait ReplicationTrait
{
    public static function replicate(Model $model)
    {
        $model->name_en = "New Record {$model->count()}";
        $model->name_fa = " رکورد جدید {$model->count()}";
        $model->slug = Str::slug($model->name_en);
        $model->save();
    }
}
