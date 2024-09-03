<?php

namespace Modules\Hr\Services;

use Modules\Hr\Models\Employee;

class TimekeepingService
{
    /**
     * Calculate the total rendered, total overtime, total late,
     * and total undertime of an employee based on the recorded timings.
     *
     * @param int $employeeId
     * @param array $timings
     * @return array
     */
    public static function calculateTimings(int $employeeId, array $timings): array
    {
        // Calculate total rendered, total overtime, total late, and total undertime
        $secondsInMinutes = 60; // Defined for readability
        $totalOvertime    = 0; // In minutes

        // Get the employee and compensation information
        $employee     = Employee::findOrFail($employeeId);
        $compensation = $employee->compensation;

        // Convert the employee shift start time, shift end time, break start time, and break end time to seconds
        $employeeShiftStartTime = strtotime($compensation->shift_start_time);
        $employeeShiftEndTime   = strtotime($compensation->shift_end_time);
        $employeeBreakStartTime = strtotime($compensation->break_start_time);
        $employeeBreakEndTime   = strtotime($compensation->break_end_time);

        // Convert the recorded time in and time out to seconds
        $recordedFirstTimeIn   = strtotime($timings['first_time_in']);
        $recordedFirstTimeOut  = strtotime($timings['first_time_out']);
        $recordedSecondTimeIn  = strtotime($timings['second_time_in']);
        $recordedSecondTimeOut = strtotime($timings['second_time_out']);

        // Determine the official time in and time out
        $recordedFirstTimeIn   = max($recordedFirstTimeIn, $employeeShiftStartTime);
        $recordedFirstTimeOut  = min($recordedFirstTimeOut, $employeeBreakStartTime);
        $recordedSecondTimeIn  = max($recordedSecondTimeIn, $employeeBreakEndTime);
        $recordedSecondTimeOut = min($recordedSecondTimeOut, $employeeShiftEndTime);
        // Calculate total rendered

        // Calculate total rendered
        $firstShiftRendered  = ($recordedFirstTimeOut - $recordedFirstTimeIn) / $secondsInMinutes;
        $secondShiftRendered = ($recordedSecondTimeOut - $recordedSecondTimeIn) / $secondsInMinutes;
        $totalRendered       = $firstShiftRendered + $secondShiftRendered;

        // todo: calculate total overtime, will do it manually for now

        // Calculate total late
        $firstShiftLate  = max(0, ($recordedFirstTimeIn - $employeeShiftStartTime) / $secondsInMinutes);
        $secondShiftLate = max(0, ($recordedSecondTimeIn - $employeeBreakEndTime) / $secondsInMinutes);
        $totalLate       = $firstShiftLate + $secondShiftLate;

        // Calculate total undertime
        $firstShiftUndertime  = max(0, ($employeeBreakStartTime - $recordedFirstTimeOut) / $secondsInMinutes);
        $secondShiftUndertime = max(0, ($employeeShiftEndTime - $recordedSecondTimeOut) / $secondsInMinutes);
        $totalUndertime       = $firstShiftUndertime + $secondShiftUndertime;

        return [
            'breakStartTime' => $compensation->break_start_time,
            'breakEndTime'   => $compensation->break_end_time,
            'totalRendered'   => $totalRendered,
            'totalOvertime'   => $totalOvertime,
            'totalLate'       => $totalLate,
            'totalUndertime'  => $totalUndertime,
        ];
    }
}
