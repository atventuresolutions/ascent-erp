<?php

namespace Modules\Hr\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeAddition extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'addition_id',
        'type',
        'amount',
        'start_date',
        'end_date',
        'notes',
    ];

    /**
     * Get the employee that owns the addition.
     *
     * @return BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the addition that owns the employee.
     *
     * @return BelongsTo
     */
    public function addition()
    {
        return $this->belongsTo(Addition::class);
    }
}
