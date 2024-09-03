<?php

namespace Modules\Hr\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Hr\Models\Timekeeping;
use Modules\Hr\Services\TimekeepingService;

class TimekeepingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()
            ->json(Timekeeping::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id'     => ['required', 'exists:employees,id'],
            'date'            => ['required', 'date'],
            'first_time_in'   => ['required', 'date_format:H:i:s'],
            'first_time_out'  => ['required', 'date_format:H:i:s'],
            'second_time_in'  => ['required', 'date_format:H:i:s'],
            'second_time_out' => ['required', 'date_format:H:i:s'],
            'notes'           => ['nullable'],
        ]);

        $timings = TimekeepingService::calculateTimings($validated['employee_id'], [
            'first_time_in'   => $validated['first_time_in'],
            'first_time_out'  => $validated['first_time_out'],
            'second_time_in'  => $validated['second_time_in'],
            'second_time_out' => $validated['second_time_out'],
        ]);

        $timekeeping = Timekeeping::create([
            'employee_id'     => $validated['employee_id'],
            'date'            => $validated['date'],
            'first_time_in'   => $validated['first_time_in'],
            'first_time_out'  => $validated['first_time_out'],
            'second_time_in'  => $validated['second_time_in'],
            'second_time_out' => $validated['second_time_out'],
            'status'          => 'PENDING',
            'notes'           => $validated['notes'],

            'break_start_time' => $timings['breakStartTime'],
            'break_end_time'   => $timings['breakEndTime'],
            'total_rendered'   => $timings['totalRendered'],
            'total_overtime'   => $timings['totalOvertime'],
            'total_late'       => $timings['totalLate'],
            'total_undertime'  => $timings['totalUndertime'],
        ]);

        return response()
            ->json($timekeeping, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $timekeeping)
    {
        return response()
            ->json(Timekeeping::findOrFail($timekeeping));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $timekeeping)
    {
        $validated = $request->validate([
            'employee_id'     => ['required', 'exists:employees,id'],
            'date'            => ['required', 'date'],
            'first_time_in'   => ['required', 'date_format:H:i:s'],
            'first_time_out'  => ['required', 'date_format:H:i:s'],
            'second_time_in'  => ['required', 'date_format:H:i:s'],
            'second_time_out' => ['required', 'date_format:H:i:s'],
            'notes'           => ['nullable'],
        ]);

        $timings = TimekeepingService::calculateTimings($validated['employee_id'], [
            'first_time_in'   => $validated['first_time_in'],
            'first_time_out'  => $validated['first_time_out'],
            'second_time_in'  => $validated['second_time_in'],
            'second_time_out' => $validated['second_time_out'],
        ]);

        $timekeeping = Timekeeping::findOrFail($timekeeping);

        $timekeeping->update([
            'employee_id'     => $validated['employee_id'],
            'date'            => $validated['date'],
            'first_time_in'   => $validated['first_time_in'],
            'first_time_out'  => $validated['first_time_out'],
            'second_time_in'  => $validated['second_time_in'],
            'second_time_out' => $validated['second_time_out'],
            'notes'           => $validated['notes'],

            'break_start_time' => $timings['breakStartTime'],
            'break_end_time'   => $timings['breakEndTime'],
            'total_rendered'   => $timings['totalRendered'],
            'total_overtime'   => $timings['totalOvertime'],
            'total_late'       => $timings['totalLate'],
            'total_undertime'  => $timings['totalUndertime'],
        ]);

        return response()
            ->json($timekeeping, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $timekeeping)
    {
        Timekeeping::findOrFail($timekeeping)
            ->delete();

        return response()
            ->json(null, 204);
    }
}
