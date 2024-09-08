<?php

namespace Modules\Hr\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Hr\Models\Employee;
use Modules\Hr\Models\Payroll;
use Modules\Hr\Services\PayrollService;

class PayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()
            ->json(Payroll::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date'],
            'name'       => ['nullable', 'string'],
            'notes'      => ['nullable', 'string'],
        ]);

        $payroll = PayrollService::generatePayroll(
            $validated['start_date'],
            $validated['end_date'],
            $validated['notes'],
            $validated['name']
        );

        return response()
            ->json($payroll);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $payroll)
    {
        $payroll = Payroll::find($payroll);
        return response()
            ->json($payroll->load('payrollEmployees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $payroll)
    {
        $validated = $request->validate([
            'name'   => ['nullable', 'string'],
            'notes'  => ['nullable', 'string'],
            'status' => ['required', 'in:DRAFT,CANCELED,APPROVED,RELEASED'],
        ]);

        $payroll = Payroll::find($payroll);
        $payroll->update($validated);

        // Todo: on release do something about related data like debts, etc.

        return response()
            ->json($payroll);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $payroll)
    {
        $payroll = Payroll::find($payroll);
        $payroll->delete();

        return response()
            ->noContent();
    }
}
