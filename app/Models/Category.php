<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $with = ['children'];

    protected $fillable = ['name', 'slug', 'is_visible', 'description', 'order', 'parent_id'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'category_products', 'category_id', 'product_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id')->where('parent_id', null);
    }
}
