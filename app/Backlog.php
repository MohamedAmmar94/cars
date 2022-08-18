<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Backlog extends Model
{
    public function car()
    {
    	return $this->belongsTo('App\CustomerCar','car_id');
    }

}
