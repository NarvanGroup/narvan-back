<?php

namespace App\Models;

use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected $hidden = ['id'];

    protected $fillable = [
        'name', 'slug', 'image', 'description', 'category_id', 'sub_category_id', 'attributes', 'tags'
    ];

    protected $casts = [
        'attributes' => 'json',
        'tags' => 'array'
    ];



    public static array $allowedIncludes = ['category', 'subCategory'];

    /**
     * Get product category
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Get product subcategory
     */
    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }
}
