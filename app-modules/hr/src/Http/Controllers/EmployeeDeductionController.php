<?php

namespace Modules\Hr\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Hr\Models\Employee;
use Modules\Hr\Models\EmployeeDeduction;

class EmployeeDeductionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $employee)
    {
        $items = EmployeeDeduction::whereEmployeeId($employee)
            ->with('deduction')
            ->paginate();

        return response()
            ->json($items);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $employee)
    {
        $validated = $request->validate([
            'deduction_id' => ['required', 'exists:deductions,id'],
            'type'        => ['required', 'in:FIXED,PERCENTAGE'],
            'amount'      => ['required', 'numeric'],
            'start_date'  => ['required', 'date'],
            'end_date'    => ['nullable', 'date'],
            'notes'       => ['nullable', 'string'],
        ]);

        $employee = Employee::findOrFail($employee);
        $addition = $employee->employeeDeductions()->create($validated);

        return response()
            ->json($addition->load('deduction'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $deduction = EmployeeDeduction::findOrFail($id);

        return response()
            ->json($deduction->load('deduction'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'deduction_id' => ['required', 'exists:deductions,id'],
            'type'        => ['required', 'in:FIXED,PERCENTAGE'],
            'amount'      => ['required', 'numeric'],
            'start_date'  => ['required', 'date'],
            'end_date'    => ['nullable', 'date'],
            'notes'       => ['nullable', 'string'],
        ]);

        $addition = EmployeeDeduction::findOrFail($id);
        $addition->update($validated);

        return response()
            ->json($addition->load('deduction'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $addition = EmployeeDeduction::findOrFail($id);
        $addition->delete();

        return response()
            ->noContent();
    }
}
