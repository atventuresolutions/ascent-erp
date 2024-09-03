<?php

namespace Modules\Hr\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'mobile_number',
        'telephone_number',
        'address',
        'birthday',
    ];

    /**
     * Get the compensation associated with the Employee
     *
     * @return HasOne
     */
    public function compensation()
    {
        return $this->hasOne(Compensation::class);
    }

    /**
     * Get the timekeeping records associated with the Employee
     *
     * @return HasMany
     */
    public function timekeepings()
    {
        return $this->hasMany(Timekeeping::class);
    }

    /**
     * Get the deductions associated with the Employee
     *
     * @return HasMany
     */
    public function employeeDeductions()
    {
        return $this->hasMany(EmployeeDeduction::class);
    }

    /**
     * Get the additions associated with the Employee
     *
     * @return HasMany
     */
    public function employeeAdditions()
    {
        return $this->hasMany(EmployeeAddition::class);
    }
}
