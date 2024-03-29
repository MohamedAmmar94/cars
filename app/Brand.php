<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable =[

        "title", "image", "is_active",'code'
    ];

    public function product()
    {
    	return $this->hasMany('App/Product');
    	
    }
}
