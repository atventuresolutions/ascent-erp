<?php

namespace Modules\Hr\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Timekeeping extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'first_time_in',
        'first_time_out',
        'break_start_time',
        'break_end_time',
        'second_time_in',
        'second_time_out',
        'total_rendered',
        'total_overtime',
        'total_late',
        'total_undertime',
        'status',
        'notes',
    ];

    /**
     * Get the employee that owns the timekeeping.
     *
     * @return BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
