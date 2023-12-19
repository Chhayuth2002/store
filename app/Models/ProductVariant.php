<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ProductVariant extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

    protected $fillable = ['product_id', 'sku', 'name', 'slug', 'price', 'inventory_quantity'];

    protected $appends = ['image_urls'];


    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getImageUrlsAttribute()
    {
        return $this->getMedia('default')->map(function ($media) {
            return $media->getFullUrl();
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function coupons()
    {
        return $this->product->coupons();
    }

    public function attributeOptions()
    {
        return $this->belongsToMany(AttributeOption::class, 'product_attributes');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function productAttributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }
}
