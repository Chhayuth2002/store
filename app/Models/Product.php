<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

    protected $fillable = ['name', 'description', 'brand_id', 'available_date', 'is_featured', 'is_new', 'status'];

    protected $appends = ['image_urls'];

    public function getImageUrlsAttribute()
    {
        return $this->getMedia('default')->map(function ($media) {
            return $media->getFullUrl();
        });
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function inventory()
    {
        return $this->variants()->selectRaw('sum(inventory_quantity) as total_inventory');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_products');
    }

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->nonQueued();
    }
}
