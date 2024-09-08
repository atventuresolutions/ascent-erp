<?php

namespace Modules\Hr\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'basic_pay',
        'overtime_pay',
        'holiday_pay',
        'total_deductions',
        'total_additions',
        'net_pay',
        'status',
        'notes',

    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
    ];

    /**
     * Get the payroll employees for the payroll
     * @return HasMany
     */
    public function payrollEmployees()
    {
        return $this->hasMany(PayrollEmployee::class);
    }

    /**
     * Override boot method
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payroll) {
            if(empty($payroll->name)) {
                $name = "{$payroll->start_date} to {$payroll->end_date}";
                $payroll->name = $name;
            }
        });
    }
}
