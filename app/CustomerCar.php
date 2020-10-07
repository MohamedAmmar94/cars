<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerCar extends Model
{
    protected $fillable =[
        "customer_id", "chassis", "model", "mileage", "plate"
    ];
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
