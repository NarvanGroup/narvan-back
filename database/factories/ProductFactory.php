<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        $name = $this->faker->unique()->name;
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'image' => 'path/to/default/image.jpg',
            'description' => $this->faker->paragraph,
            'attributes' => json_encode(['attribute1' => 'value1', 'attribute2' => 'value2']),
            'tags' => json_encode(['tag1', 'tag2', 'tag3']),
        ];
    }
}
