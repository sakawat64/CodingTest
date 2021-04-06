<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariantPrice extends Model
{
    protected $fillable = [
        'product_variant_one', 'product_variant_two', 'product_variant_three','price','stock','product_id'
    ];
    public function product()
    {
        return $this->belongsTo('App\Models\Product','product_id');
    }
    public function product_variant_one()
    {
        return $this->belongsTo('App\Models\ProductVariant','product_variant_one');
    }
    public function product_variant_two()
    {
        return $this->belongsTo('App\Models\ProductVariant','product_variant_two');
    }
    public function product_variant_three()
    {
        return $this->belongsTo('App\Models\ProductVariant','product_variant_three');
    }
}
