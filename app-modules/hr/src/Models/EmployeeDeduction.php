<?php

namespace Modules\Hr\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDeduction extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'deduction_id',
        'type',
        'amount',
        'start_date',
        'end_date',
        'notes',
    ];

    /*
     * Get the employee that owns the deduction.
     *
     * @return BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /*
     * Get the deduction that owns the employee.
     *
     * @return BelongsTo
     */
    public function deduction()
    {
        return $this->belongsTo(Deduction::class);
    }
}
