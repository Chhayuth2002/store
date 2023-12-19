<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryProduct extends Model
{
    use HasFactory;

    protected $table = 'category_products';
    public $timestamps = false;

    protected $fillable = ['category_id', 'product_id'];

    /**
     * Get the category associated with the pivot.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the product associated with the pivot.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
