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
            ->json(Employee::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'firstname' => ['required', 'string'],
            'lastname' => ['required', 'string'],
            'email' => ['required', 'email'],
            'mobile_number' => ['nullable', 'string'],
            'telephone_number' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'birthday' => ['nullable', 'date'],
        ]);

        $employee = Employee::create($validated);

        return response()
            ->json($employee);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()
            ->json(Employee::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'firstname' => ['required', 'string'],
            'lastname' => ['required', 'string'],
            'email' => ['required', 'email'],
            'mobile_number' => ['nullable', 'string'],
            'telephone_number' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'birthday' => ['nullable', 'date'],
        ]);

        $employee = Employee::findOrFail($id);
        $employee->update($validated);

        return response()
            ->json($employee);
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
