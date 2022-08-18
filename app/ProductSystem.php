<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductSystem extends Model
{
    protected $guarded = [];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
