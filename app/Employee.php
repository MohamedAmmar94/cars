<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable =[
        "name", "image", "department_id", "email", "phone_number",
        "user_id", "address", "city", "country", "is_active", "national_id", "insurance_id"
    ];

    public function payroll()
    {
    	return $this->hasMany('App\Payroll');
    }
    public function documents()
    {
    	return $this->hasMany(EmployeeDocument::class);
    }
    
}
