<?php

namespace App\Http\Controllers;

use App\Models\Consumer;
use App\Models\MeterReading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MeterReadingController extends Controller
{
    /**
     * Display a listing of meter readings.
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $month = $request->get('month', '');

        $query = MeterReading::with(['consumer', 'recordedBy'])
            ->orderBy('reading_date', 'desc');

        if ($search) {
            $query->whereHas('consumer', function ($q) use ($search) {
                $q->where('account_number', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('meter_number', 'like', "%{$search}%");
            });
        }

        if ($month) {
            $query->whereYear('reading_date', '=', date('Y', strtotime($month)))
                ->whereMonth('reading_date', '=', date('m', strtotime($month)));
        }

        $readings = $query->paginate(15);

        return view('meter-readings.index', compact('readings', 'search', 'month'));
    }

    /**
     * Show the form for creating a new meter reading.
     */
    public function create()
    {
        $consumers = Consumer::where('connection_status', 'active')
            ->orderBy('first_name')
            ->get();

        return view('meter-readings.create', compact('consumers'));
    }

    /**
     * Store a newly created meter reading in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'consumer_id' => 'required|exists:consumers,id',
            'current_reading' => 'required|numeric|min:0',
            'reading_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $consumer = Consumer::findOrFail($validated['consumer_id']);

            // Get the latest reading for this consumer
            $latestReading = MeterReading::where('consumer_id', $consumer->id)
                ->orderBy('reading_date', 'desc')
                ->first();

            $previousReading = $latestReading?->current_reading ?? 0;
            $currentReading = $validated['current_reading'];
            $consumption = max($currentReading - $previousReading, 0);

            // Validate that current reading is not less than previous
            if ($currentReading < $previousReading) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Current reading cannot be less than previous reading (' . number_format($previousReading, 2) . ' m³)');
            }

            MeterReading::create([
                'consumer_id' => $consumer->id,
                'meter_number' => $consumer->meter_number,
                'previous_reading' => $previousReading,
                'current_reading' => $currentReading,
                'consumption' => $consumption,
                'reading_date' => $validated['reading_date'],
                'notes' => $validated['notes'],
                'recorded_by' => $request->user()->id,
            ]);

            // Log to audit log
            $this->logAudit(
                'Meter Reading Entry',
                "Recorded meter reading for {$consumer->first_name} {$consumer->last_name} (Account: {$consumer->account_number}) - Reading: {$currentReading} m³, Consumption: {$consumption} m³",
                $request->user()->id
            );

            DB::commit();

            return redirect()->route('meter-readings.index')
                ->with('success', 'Meter reading recorded successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to record meter reading: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified meter reading.
     */
    public function show(MeterReading $meterReading)
    {
        $meterReading->load(['consumer', 'recordedBy']);
        return view('meter-readings.show', compact('meterReading'));
    }

    /**
     * Show the form for editing the specified meter reading.
     */
    public function edit(MeterReading $meterReading)
    {
        $meterReading->load('consumer');
        return view('meter-readings.edit', compact('meterReading'));
    }

    /**
     * Update the specified meter reading in storage.
     */
    public function update(Request $request, MeterReading $meterReading)
    {
        $validated = $request->validate([
            'current_reading' => 'required|numeric|min:0',
            'reading_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Validate that current reading is not less than previous
        if ($validated['current_reading'] < $meterReading->previous_reading) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Current reading cannot be less than previous reading (' . number_format($meterReading->previous_reading, 2) . ' m³)');
        }

        $consumption = max($validated['current_reading'] - $meterReading->previous_reading, 0);

        $meterReading->update([
            'current_reading' => $validated['current_reading'],
            'consumption' => $consumption,
            'reading_date' => $validated['reading_date'],
            'notes' => $validated['notes'],
        ]);

        // Log to audit log
        $this->logAudit(
            'Meter Reading Update',
            "Updated meter reading for {$meterReading->consumer->first_name} {$meterReading->consumer->last_name} (ID: {$meterReading->id})",
            $request->user()->id
        );

        return redirect()->route('meter-readings.index')
            ->with('success', 'Meter reading updated successfully.');
    }

    /**
     * Remove the specified meter reading from storage.
     */
    public function destroy(MeterReading $meterReading)
    {
        $consumerName = $meterReading->consumer->first_name . ' ' . $meterReading->consumer->last_name;
        $meterReading->delete();

        // Log to audit log
        $this->logAudit(
            'Meter Reading Deletion',
            "Deleted meter reading for {$consumerName} (ID: {$meterReading->id})",
            auth()->id()
        );

        return redirect()->route('meter-readings.index')
            ->with('success', 'Meter reading deleted successfully.');
    }

    /**
     * Get consumer details for meter reading entry.
     */
    public function getConsumerDetails($consumerId)
    {
        $consumer = Consumer::findOrFail($consumerId);

        $latestReading = MeterReading::where('consumer_id', $consumer->id)
            ->orderBy('reading_date', 'desc')
            ->first();

        return response()->json([
            'consumer' => $consumer,
            'previous_reading' => $latestReading?->current_reading ?? 0,
            'meter_number' => $consumer->meter_number,
        ]);
    }

    /**
     * Log action to audit log.
     */
    private function logAudit(string $action, string $details, int $userId)
    {
        DB::table('audit_logs')->insert([
            'user_id' => $userId,
            'action' => $action,
            'details' => $details,
            'date_time' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
