<?php

namespace Modules\Hr\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Hr\Models\Employee;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()
            ->json(Employee::with('compensation')->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // employee
            'firstname'        => ['required', 'string'],
            'lastname'         => ['required', 'string'],
            'email'            => ['required', 'email'],
            'mobile_number'    => ['nullable', 'string'],
            'telephone_number' => ['nullable', 'string'],
            'address'          => ['nullable', 'string'],
            'birthday'         => ['nullable', 'date'],

            // compensation information
            'daily_rate'          => ['required', 'numeric'],
            'daily_working_hours' => ['required', 'numeric'],
            'working_days'        => ['required', 'array'],
            'working_days.*'      => ['required', 'in:SUNDAY,MONDAY,TUESDAY,WEDNESDAY,THURSDAY,FRIDAY,SATURDAY'],
            'shift_start_time'    => ['required', 'date_format:H:i'],
            'shift_end_time'      => ['required', 'date_format:H:i'],
            'break_start_time'    => ['nullable', 'date_format:H:i'],
            'break_end_time'      => ['nullable', 'date_format:H:i'],
        ]);

        $employee = Employee::create([
            'firstname'        => $validated['firstname'],
            'lastname'         => $validated['lastname'],
            'email'            => $validated['email'],
            'mobile_number'    => $validated['mobile_number'],
            'telephone_number' => $validated['telephone_number'],
            'address'          => $validated['address'],
            'birthday'         => $validated['birthday'],
        ]);

        $employee->compensation()->create([
            'daily_rate'          => $validated['daily_rate'],
            'daily_working_hours' => $validated['daily_working_hours'],
            'working_days'        => $validated['working_days'],
            'shift_start_time'    => $validated['shift_start_time'],
            'shift_end_time'      => $validated['shift_end_time'],
            'break_start_time'    => $validated['break_start_time'],
            'break_end_time'      => $validated['break_end_time'],
        ]);

        return response()
            ->json($employee->load('compensation'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $employee = Employee::findOrFail($id);
        return response()
            ->json($employee->load('compensation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            // employee information
            'firstname'        => ['required', 'string'],
            'lastname'         => ['required', 'string'],
            'email'            => ['required', 'email'],
            'mobile_number'    => ['nullable', 'string'],
            'telephone_number' => ['nullable', 'string'],
            'address'          => ['nullable', 'string'],
            'birthday'         => ['nullable', 'date'],

            // compensation information
            'daily_rate'          => ['required', 'numeric'],
            'daily_working_hours' => ['required', 'numeric'],
            'working_days'        => ['required', 'array'],
            'working_days.*'      => ['required', 'in:SUNDAY,MONDAY,TUESDAY,WEDNESDAY,THURSDAY,FRIDAY,SATURDAY'],
            'shift_start_time'    => ['required', 'date_format:H:i'],
            'shift_end_time'      => ['required', 'date_format:H:i'],
            'break_start_time'    => ['nullable', 'date_format:H:i'],
            'break_end_time'      => ['nullable', 'date_format:H:i'],
        ]);

        $employee = Employee::findOrFail($id);
        $employee->update([
            'firstname'        => $validated['firstname'],
            'lastname'         => $validated['lastname'],
            'email'            => $validated['email'],
            'mobile_number'    => $validated['mobile_number'],
            'telephone_number' => $validated['telephone_number'],
            'address'          => $validated['address'],
            'birthday'         => $validated['birthday'],
        ]);

        $employee->compensation()->update([
            'daily_rate'          => $validated['daily_rate'],
            'daily_working_hours' => $validated['daily_working_hours'],
            'working_days'        => $validated['working_days'],
            'shift_start_time'    => $validated['shift_start_time'],
            'shift_end_time'      => $validated['shift_end_time'],
            'break_start_time'    => $validated['break_start_time'],
            'break_end_time'      => $validated['break_end_time'],
        ]);

        return response()
            ->json($employee->load('compensation'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return response()
            ->json($employee);
    }
}
