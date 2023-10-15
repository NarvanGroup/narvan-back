<?php

namespace App\Filament\Resources\SubCategoryResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\SubCategoryResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = SubCategoryResource::class;
    protected static string $keyName = 'slug';



    public function handler($id)
    {
        $model = static::getModel()::query();

        $query = QueryBuilder::for(
            $model->where(static::getKeyName(), $id)
        )
            ->allowedIncludes(static::getModel()::$allowedIncludes ?? [])
            ->first();

        if (!$query) return static::sendNotFoundResponse();

        $transformer = static::getApiTransformer();

        return new $transformer($query);
    }
}
