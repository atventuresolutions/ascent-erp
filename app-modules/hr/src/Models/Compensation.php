<?php

namespace Modules\Hr\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compensation extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'daily_rate',
        'daily_working_hours',

        'overtime_multiplier',
        'holiday_multiplier',
        'special_holiday_multiplier',

        'shift_start_time',
        'shift_end_time',
        'break_start_time',
        'break_end_time',
    ];

    protected $casts = [
        'working_days' => 'array',
    ];

    /**
     * Get the employee that owns the Compensation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee() {
        return $this->belongsTo(Employee::class);
    }
}
