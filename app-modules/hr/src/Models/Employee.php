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

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
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

    /**
     * Generate a unique code for the Employee
     * @return string
     */
    public static function generateCode()
    {
        // Generate a 6 char alphanumeric code
        $generatedCode = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
        // Check if existing
        if (Employee::whereCode($generatedCode)->exists()) {
            return Employee::generateCode();
        } else {
            return $generatedCode;
        }
    }

    /**
     * Override boot method
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // Generate code before creating
        static::creating(function ($employee) {
            $employee->code = Employee::generateCode();
        });
    }
}
