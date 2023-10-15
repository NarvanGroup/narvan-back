<?php

namespace App\Filament\Resources\BlogResource\Api\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogTransformer extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->resource;
    }
}
