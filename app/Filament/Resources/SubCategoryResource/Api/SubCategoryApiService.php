<?php
namespace App\Filament\Resources\SubCategoryResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\SubCategoryResource;
use Illuminate\Routing\Router;


class SubCategoryApiService extends ApiService
{
    protected static string | null $resource = SubCategoryResource::class;

    public static function allRoutes(Router $router)
    {
//        Handlers\UpdateHandler::route($router);
//        Handlers\DeleteHandler::route($router);
        Handlers\PaginationHandler::route($router);
        Handlers\DetailHandler::route($router);
    }
}
