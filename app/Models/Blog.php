<?php

namespace App\Models;

use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Blog extends Model
{
    use HasFactory;

    /**
     * @return string
     */

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected $hidden = ['id'];

    protected $fillable = [
        'name', 'slug', 'content', 'images', 'description', 'user_id', 'category_id', 'sub_category_id', 'keywords',
        'tags'
    ];

    public static array $allowedIncludes = ['user', 'category', 'subCategory'];

    protected $casts = [
        'images' => 'array',
        'keywords' => 'json',
        'tags' => 'array',
    ];

    protected function images(): Attribute
    {
        return Attribute::make(
            get: static fn(string $value) => collect($value)->map(static fn($image
            ) => env('APP_URL').'/images/'.$image),
        );
    }

    /**
     * Get product category
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

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
