<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'variant', 'variant_id', 'product_id'
    ];
    public function product()
    {
        return $this->belongsTo('App\Models\Product','product_id');
    }
    public function variant()
    {
        return $this->belongsTo('App\Models\Variant','variant_id');
    }
    public function product_variant_prices()
    {
        return $this->hasMany('App\Models\ProductVariantPrice','id');
    }
}
