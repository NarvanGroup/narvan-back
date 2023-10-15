<?php

use App\Filament\Resources\BlogResource\Api\BlogApiService;
use App\Filament\Resources\CategoryResource\Api\CategoryApiService;
use App\Filament\Resources\ProductResource\Api\ProductApiService;
use App\Filament\Resources\SubCategoryResource\Api\SubCategoryApiService;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

ProductApiService::routes();
BlogApiService::routes();
CategoryApiService::routes();
SubCategoryApiService::routes();
