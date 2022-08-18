<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product_Sale extends Model
{
	protected $table = 'product_sales';
    protected $fillable =[
        "sale_id", "product_id", "variant_id", "qty", "sale_unit_id", "net_unit_price", "discount", "tax_rate", "tax", "total",'is_dispatched',"received_person"
    ];

	public function product()
    {
    	return $this->belongsTo('App\Product','product_id','id');
    }
	public function sale()
    {
    	return $this->belongsTo('App\Sale');

    }
}
