<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Carbon;

class Employee extends Model
{
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['age'];

    /**
     * Get the age of the employee.
     *
     * @return int
     */
    public function getAgeAttribute()
    {
        return Carbon::parse($this->birthdate)->age;
    }
}
