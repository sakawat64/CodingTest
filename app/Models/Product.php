<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title', 'sku', 'description'
    ];
    public function product_variant_prices()
    {
        return $this->hasMany('App\Models\ProductVariantPrice','id');
    }
    public function product_variant()
    {
        return $this->hasMany('App\Models\ProductVariant','id');
    }

}
