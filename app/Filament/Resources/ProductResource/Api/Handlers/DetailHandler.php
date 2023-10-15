<?php

namespace App\Filament\Resources\ProductResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\ProductResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = ProductResource::class;
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
