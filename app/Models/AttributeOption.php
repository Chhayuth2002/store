<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeOption extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'attribute_options';

    protected $fillable = ['attribute_id', 'slug', 'value'];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function productAttributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }
}
