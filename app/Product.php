<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable =[

        "name", "tst","code", "type", "part_number", "barcode_symbology","brand_id", "product_system_id", "category_id", "supplier_id", "system_id", "unit_id", "purchase_unit_id", "sale_unit_id", "cost", "price", "qty", "alert_quantity", "promotion", "promotion_price", "starting_date", "last_date", "tax_id", "tax_method", "image", "file", "is_variant", "featured", "product_list", "qty_list", "price_list", "product_details", "is_active"
    ];

    public function category()
    {
    	return $this->belongsTo('App\Category');
    }

    public function brand()
    {
    	return $this->belongsTo('App\Brand');
    }

    public function unit()
    {
        return $this->belongsTo('App\Unit');
    }

    public function variant()
    {
        return $this->belongsToMany('App\Variant', 'product_variants')->withPivot('id', 'item_code', 'additional_price');
    }

    public function scopeActiveStandard($query)
    {
        return $query->where([
            ['is_active', true],
            ['type', 'standard']
        ]);
    }

    public function scopeActiveFeatured($query)
    {
        return $query->where([
            ['is_active', true],
            ['featured', 1]
        ]);
    }

    public function getLastPurchaseCostAttribute()
    {
        $last_purchase=  ProductPurchase::where('product_id',$this->id)->orderByDesc('updated_at')->first();
        return $last_purchase?$last_purchase->net_unit_cost:0;
    }

}
