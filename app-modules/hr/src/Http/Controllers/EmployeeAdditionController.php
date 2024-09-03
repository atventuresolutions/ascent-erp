<?php

namespace Modules\Hr\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Hr\Models\Employee;
use Modules\Hr\Models\EmployeeAddition;

class EmployeeAdditionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $employee)
    {
        $items = EmployeeAddition::whereEmployeeId($employee)
            ->with('addition')
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
            'addition_id' => ['required', 'exists:additions,id'],
            'type'        => ['required', 'in:FIXED,PERCENTAGE'],
            'amount'      => ['required', 'numeric'],
            'start_date'  => ['required', 'date'],
            'end_date'    => ['nullable', 'date'],
            'notes'       => ['nullable', 'string'],
        ]);

        $employee = Employee::findOrFail($employee);
        $addition = $employee->employeeAdditions()->create($validated);

        return response()
            ->json($addition->load('addition'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $addition = EmployeeAddition::findOrFail($id);

        return response()
            ->json($addition->load('addition'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'addition_id' => ['required', 'exists:additions,id'],
            'type'        => ['required', 'in:FIXED,PERCENTAGE'],
            'amount'      => ['required', 'numeric'],
            'start_date'  => ['required', 'date'],
            'end_date'    => ['nullable', 'date'],
            'notes'       => ['nullable', 'string'],
        ]);

        $addition = EmployeeAddition::findOrFail($id);
        $addition->update($validated);

        return response()
            ->json($addition->load('addition'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $addition = EmployeeAddition::findOrFail($id);
        $addition->delete();

        return response()
            ->noContent();
    }
}
