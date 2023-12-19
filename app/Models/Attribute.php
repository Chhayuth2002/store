<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'attributes';
    protected $fillable = ['name', 'slug'];

    public function options()
    {
        return $this->hasMany(AttributeOption::class, 'attribute_id');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
