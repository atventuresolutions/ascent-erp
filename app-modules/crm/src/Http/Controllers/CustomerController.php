<?php

namespace Modules\Crm\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Crm\Models\Customer;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()
            ->json(Customer::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'firstname' => ['required', 'string'],
            'lastname' => ['nullable', 'string'],
            'mobile_number' => ['required', 'string'],
            'telephone_number' => ['nullable', 'string'],
            'email' => ['nullable', 'email'],
            'address' => ['required', 'string']
        ]);

        $customer = Customer::create($validated);

        return response()
            ->json($customer);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()
            ->json(Customer::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'firstname' => ['required', 'string'],
            'lastname' => ['nullable', 'string'],
            'mobile_number' => ['required', 'string'],
            'telephone_number' => ['nullable', 'string'],
            'email' => ['nullable', 'email'],
            'address' => ['required', 'string']
        ]);

        $customer = Customer::findOrFail($id);
        $customer->update($validated);

        return response()
            ->json($customer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return response()
            ->json(null, 204);
    }
}
