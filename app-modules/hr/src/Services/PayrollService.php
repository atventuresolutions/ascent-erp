<?php

namespace Modules\Hr\Services;

use Modules\Hr\Models\Employee;
use Modules\Hr\Models\Payroll;

class PayrollService
{
    /**
     * Generate payroll, with basic pay, overtime pay, holiday pay, deductions, and additions.
     * Also creates payroll employees.
     *
     * @param $startDate
     * @param $endDate
     * @param $notes
     * @param $name
     * @return mixed
     */
    public static function generatePayroll($startDate, $endDate, $notes, $name = null)
    {
        // Create a new payroll
        $payroll = Payroll::create([
            'name'             => $name,
            'start_date'       => $startDate,
            'end_date'         => $endDate,
            'notes'            => $notes,
            'status'           => 'DRAFT',
            'basic_pay'        => 0,
            'overtime_pay'     => 0,
            'holiday_pay'      => 0,
            'total_deductions' => 0,
            'total_additions'  => 0,
            'net_pay'          => 0,
        ]);

        $overallBasicPay    = 0;
        $overallOvertimePay = 0;
        $overallHolidayPay  = 0;
        $overallDeductions  = 0;
        $overallAdditions   = 0;

        // Get employees
        $employees = Employee::all();
        foreach ($employees as $employee) {
            $data = [
                'employee' => [
                    'code' => $employee->code,
                    'name' => "{$employee->firstname} {$employee->lastname}",
                ],
            ];

            // Get timekeeping
            $timekeepings   = $employee->timekeepings()
                ->where('date', '>=', $startDate)
                ->where('date', '<=', $endDate)
                ->where('status', 'APPROVED')
                ->get();
            $totalRendered  = $timekeepings->sum('total_rendered');
            $totalOvertime  = $timekeepings->sum('total_overtime');
            $totalLate      = $timekeepings->sum('total_late');
            $totalUndertime = $timekeepings->sum('total_undertime');

            $data['timekeepings'] = [
                'data'            => $timekeepings,
                'total_rendered'  => $totalRendered,
                'total_overtime'  => $totalOvertime,
                'total_late'      => $totalLate,
                'total_undertime' => $totalUndertime,
            ];

            // Compensation
            $compensation         = $employee->compensation;
            $ratePerMinute        = $compensation->daily_rate / ($compensation->daily_working_hours * 60);
            $data['compensation'] = $compensation;

            // Calculate basic pays
            $basicPay    = $totalRendered * $ratePerMinute;
            $overtimePay = $totalOvertime * ($ratePerMinute * ($compensation->overtime_multiplier + 100) / 100);
            $holidayPay  = 0; // todo: calculate multiplier for holiday and special holiday

            // Get deductions
            $deductions = $employee->employeeDeductions()
                ->where('start_date', '<=', $endDate)
                ->where('end_date', '>=', $startDate)
                ->get();

            $totalDeductions = 0;
            foreach ($deductions as $deduction) {
                if ($deduction->type === 'FIXED') {
                    $totalDeductions += $deduction->amount;
                } elseif ($deduction->type === 'PERCENTAGE') {
                    $totalDeductions += ($basicPay + $overtimePay + $holidayPay) * ($deduction->amount / 100);
                }

                $data['deductions'][] = $deduction;
            }

            // Get additions
            $additions = $employee->employeeAdditions()
                ->where('start_date', '<=', $endDate)
                ->where('end_date', '>=', $startDate)
                ->get();

            $totalAdditions = 0;
            foreach ($additions as $addition) {
                if ($addition->type === 'FIXED') {
                    $totalAdditions += $addition->amount;
                } elseif ($addition->type === 'PERCENTAGE') {
                    $totalAdditions += ($basicPay + $overtimePay + $holidayPay) * ($addition->amount / 100);
                }

                $data['additions'][] = $addition;
            }

            $netPay = ($basicPay + $overtimePay + $holidayPay + $totalAdditions) - $totalDeductions;

            $data['pay'] = [
                'basic_pay'        => $basicPay,
                'overtime_pay'     => $overtimePay,
                'holiday_pay'      => $holidayPay,
                'total_deductions' => $totalDeductions,
                'total_addition'   => $totalAdditions,
                'net_pay'          => $netPay,
            ];

            // Create payroll employee
            $payroll->payrollEmployees()
                ->create([
                    'employee_id'      => $employee->id,
                    'basic_pay'        => $basicPay,
                    'overtime_pay'     => $overtimePay,
                    'holiday_pay'      => $holidayPay,
                    'total_deductions' => $totalDeductions,
                    'total_additions'  => $totalAdditions,
                    'net_pay'          => $netPay,
                    'data'             => $data
                ]);

            $overallBasicPay    += $basicPay;
            $overallOvertimePay += $overtimePay;
            $overallHolidayPay  += $holidayPay;
            $overallDeductions  += $totalDeductions;
            $overallAdditions   += $totalAdditions;
        }

        $payroll->update([
            'basic_pay'        => $overallBasicPay,
            'overtime_pay'     => $overallOvertimePay,
            'holiday_pay'      => $overallHolidayPay,
            'total_deductions' => $overallDeductions,
            'total_additions'  => $overallAdditions,
            'net_pay'          => ($overallBasicPay + $overallOvertimePay + $overallHolidayPay + $overallAdditions) - $overallDeductions,
        ]);

        return $payroll;
    }
}
