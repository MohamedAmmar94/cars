<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable =[
        "customer_group_id", "name", "company_name",'due_period',
        "email", "phone_number", "tax_no", "address", "city",
        "state", "postal_code", "country", "deposit", "expense", "is_active"
    ];

    public function cars()
    {
        return $this->hasMany(CustomerCar::class);
    }
}
