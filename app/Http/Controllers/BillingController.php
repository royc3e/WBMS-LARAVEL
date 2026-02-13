<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Consumer;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BillingController extends Controller
{
    /**
     * Display a listing of the billings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $search = (string) $request->input('search', '');
        $status = (string) $request->input('status', '');
        $month = (string) $request->input('month', '');

        $allowedStatuses = ['pending', 'paid', 'overdue', 'cancelled'];

        $billings = Billing::with(['consumer', 'payments'])
            ->when($search !== '', function ($query) use ($search) {
                $normalized = Str::of($search)->lower()->squish();

                $query->whereHas('consumer', function ($q) use ($normalized, $search) {
                    $q->where('account_number', 'like', "%{$search}%")
                        ->orWhereRaw(
                            "LOWER(TRIM(first_name)) LIKE ?",
                            ['%' . $normalized . '%']
                        )
                        ->orWhereRaw(
                            "LOWER(TRIM(last_name)) LIKE ?",
                            ['%' . $normalized . '%']
                        )
                        ->orWhereRaw(
                            "LOWER(CONCAT(TRIM(first_name), ' ', TRIM(last_name))) LIKE ?",
                            ['%' . $normalized . '%']
                        )
                        ->orWhereRaw(
                            "LOWER(CONCAT(TRIM(last_name), ' ', TRIM(first_name))) LIKE ?",
                            ['%' . $normalized . '%']
                        );
                });
            })
            ->when(in_array($status, $allowedStatuses, true), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($month !== '' && preg_match('/^\d{4}-\d{2}$/', $month), function ($query) use ($month) {
                [$year, $monthNumber] = explode('-', $month);
                $query->whereYear('billing_month', $year)
                    ->whereMonth('billing_month', $monthNumber);
            })
            ->orderByDesc('billing_month')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('billings.index', compact('billings', 'search', 'status', 'month'));
    }

    /**
     * Show the billing generation options.
     */
    public function generate()
    {
        $consumerCounts = [
            'total' => Consumer::count(),
            'active' => Consumer::where('connection_status', 'active')->count(),
            'inactive' => Consumer::where('connection_status', '!=', 'active')->count(),
        ];

        $defaultRate = (float) config('billing.default_rate_per_unit', 0);

        return view('billings.generate', compact('consumerCounts', 'defaultRate'));
    }

    /**
     * Generate billing for all active consumers (Batch Processing).
     */
    public function generateAll(Request $request)
    {
        $validated = $request->validate([
            'billing_month' => 'required|date_format:Y-m',
            'due_date' => 'required|date|after:today',
            'connection_type' => 'nullable|in:residential,commercial,industrial,government',
        ]);

        $ratePerUnit = (float) config('billing.default_rate_per_unit', 25.00);
        $penaltyRate = (float) config('billing.penalty_rate', 0.02); // 2% penalty
        $billingDate = $validated['billing_month'] . '-01';
        $dueDate = $validated['due_date'];
        $connectionTypeFilter = $validated['connection_type'] ?? null;

        // Build query for active consumers
        $query = Consumer::where('connection_status', 'active');

        // Apply connection type filter if specified
        if ($connectionTypeFilter) {
            $query->where('connection_type', $connectionTypeFilter);
        }

        $activeConsumers = $query->get();

        if ($activeConsumers->isEmpty()) {
            $message = $connectionTypeFilter
                ? "No active {$connectionTypeFilter} consumers found to generate billing."
                : 'No active consumers found to generate billing.';
            return redirect()->back()->with('error', $message);
        }

        $created = 0;
        $skipped = 0;
        $skipReasons = [];

        DB::beginTransaction();
        try {
            foreach ($activeConsumers as $consumer) {
                // Skip if connection_type is null
                if (!$consumer->connection_type) {
                    $skipped++;
                    $skipReasons[] = "{$consumer->account_number}: No connection type";
                    continue;
                }

                // Get the most recent meter reading for this consumer
                $latestMeterReading = DB::table('meter_readings')
                    ->where('consumer_id', $consumer->id)
                    ->orderBy('reading_date', 'desc')
                    ->first();

                // Get latest billing to check if already billed for this month
                $existingBilling = Billing::where('consumer_id', $consumer->id)
                    ->where('billing_month', $billingDate)
                    ->first();

                if ($existingBilling) {
                    $skipped++;
                    $skipReasons[] = "{$consumer->account_number}: Already billed";
                    continue; // Skip if already billed for this month
                }

                // Get latest billing for previous reading
                $latestBilling = $consumer->billings()->latest('billing_month')->first();

                // Calculate previous balance (unpaid amount from previous billings)
                $previousBalance = $consumer->billings()
                    ->whereIn('status', ['pending', 'overdue'])
                    ->sum('amount');

                // Get readings - use meter reading if exists, otherwise simulate
                if ($latestMeterReading) {
                    $previousReading = $latestBilling?->current_reading ?? 0;
                    $currentReading = $latestMeterReading->current_reading;
                } else {
                    // Fallback to simulation if no meter readings exist
                    $previousReading = $latestBilling?->current_reading ?? 0;
                    $currentReading = $previousReading + rand(5, 30);
                }

                $consumption = max($currentReading - $previousReading, 0);

                // Calculate penalty if there's previous overdue balance
                $penalty = 0;
                if ($previousBalance > 0) {
                    $penalty = $previousBalance * $penaltyRate;
                }

                // Calculate consumption amount using new tiered pricing
                $consumptionAmount = $this->calculateConsumptionCharge($consumption, $consumer->connection_type);
                $totalAmount = $consumptionAmount + $previousBalance + $penalty;

                Billing::create([
                    'consumer_id' => $consumer->id,
                    'billing_month' => $billingDate,
                    'previous_reading' => $previousReading,
                    'current_reading' => $currentReading,
                    'consumption' => $consumption,
                    'amount' => $totalAmount,
                    'previous_balance' => $previousBalance,
                    'penalty' => $penalty,
                    'due_date' => $dueDate,
                    'status' => 'pending',
                    'created_by' => $request->user()->id,
                    'notes' => sprintf(
                        'Batch generated | Consumption: %.2fm³ | Charge: ₱%.2f | Type: %s | Prev Balance: ₱%.2f | Penalty: ₱%.2f',
                        $consumption,
                        $consumptionAmount,
                        ucfirst($consumer->connection_type),
                        $previousBalance,
                        $penalty
                    ),
                ]);

                $created++;
            }

            // Check if any bills were created
            if ($created === 0) {
                DB::rollBack();
                $errorMessage = "No bills were generated. ";
                if ($skipped > 0) {
                    $errorMessage .= "{$skipped} consumer(s) were skipped. Reasons: " . implode(', ', array_slice($skipReasons, 0, 5));
                    if (count($skipReasons) > 5) {
                        $errorMessage .= ', and ' . (count($skipReasons) - 5) . ' more...';
                    }
                }
                return redirect()->back()->with('error', $errorMessage);
            }

            // Log to audit log
            $typeInfo = $connectionTypeFilter ? " ({$connectionTypeFilter} only)" : '';
            $this->logAudit(
                'Batch Bill Generation',
                "Generated {$created} billing records for active consumers{$typeInfo} for " . Carbon::parse($billingDate)->format('F Y'),
                $request->user()->id
            );

            DB::commit();

            $successMessage = "Successfully generated {$created} billing record(s)" . ($connectionTypeFilter ? " for {$connectionTypeFilter} consumers" : '') . ".";
            if ($skipped > 0) {
                $successMessage .= " {$skipped} consumer(s) were skipped.";
            }

            return redirect()->route('billings.index')->with('success', $successMessage);


        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to generate billings: ' . $e->getMessage());
        }
    }

    /**
     * Generate billing for individual consumer.
     */
    public function generateIndividual(Request $request)
    {
        $validated = $request->validate([
            'consumer_id' => 'required|exists:consumers,id',
            'billing_month' => 'required|date_format:Y-m',
            'due_date' => 'required|date|after:today',
        ]);

        $ratePerUnit = (float) config('billing.default_rate_per_unit', 25.00);
        $penaltyRate = (float) config('billing.penalty_rate', 0.02);
        $billingDate = $validated['billing_month'] . '-01';
        $dueDate = $validated['due_date'];

        $consumer = Consumer::findOrFail($validated['consumer_id']);

        // Check if connection type exists
        if (!$consumer->connection_type) {
            return redirect()->back()
                ->with('error', 'Consumer does not have a connection type set. Please update consumer details first.');
        }

        DB::beginTransaction();
        try {
            // Check if already billed for this month
            $existingBilling = Billing::where('consumer_id', $consumer->id)
                ->where('billing_month', $billingDate)
                ->first();

            if ($existingBilling) {
                return redirect()->back()
                    ->with('error', 'Billing for this consumer already exists for ' . Carbon::parse($billingDate)->format('F Y'));
            }

            // Get the most recent meter reading for this consumer
            $latestMeterReading = DB::table('meter_readings')
                ->where('consumer_id', $consumer->id)
                ->orderBy('reading_date', 'desc')
                ->first();

            // Get latest billing
            $latestBilling = $consumer->billings()->latest('billing_month')->first();

            // Calculate previous balance
            $previousBalance = $consumer->billings()
                ->whereIn('status', ['pending', 'overdue'])
                ->sum('amount');

            // Get readings - use meter reading if exists, otherwise simulate
            if ($latestMeterReading) {
                $previousReading = $latestBilling?->current_reading ?? 0;
                $currentReading = $latestMeterReading->current_reading;
                $readingSource = 'meter reading';
            } else {
                // Fallback to simulation if no meter readings exist
                $previousReading = $latestBilling?->current_reading ?? 0;
                $currentReading = $previousReading + rand(5, 30);
                $readingSource = 'simulated';
            }

            $consumption = max($currentReading - $previousReading, 0);

            // Calculate penalty
            $penalty = 0;
            if ($previousBalance > 0) {
                $penalty = $previousBalance * $penaltyRate;
            }

            // Calculate consumption amount using new tiered pricing
            $consumptionAmount = $this->calculateConsumptionCharge($consumption, $consumer->connection_type);
            $totalAmount = $consumptionAmount + $previousBalance + $penalty;

            $billing = Billing::create([
                'consumer_id' => $consumer->id,
                'billing_month' => $billingDate,
                'previous_reading' => $previousReading,
                'current_reading' => $currentReading,
                'consumption' => $consumption,
                'amount' => $totalAmount,
                'previous_balance' => $previousBalance,
                'penalty' => $penalty,
                'due_date' => $dueDate,
                'status' => 'pending',
                'created_by' => $request->user()->id,
                'notes' => sprintf(
                    'Individual | Account: %s | Consumption: %.2fm³ | Charge: ₱%.2f | Type: %s | Source: %s',
                    $consumer->account_number,
                    $consumption,
                    $consumptionAmount,
                    ucfirst($consumer->connection_type),
                    $readingSource
                ),
            ]);

            // Log to audit log
            $this->logAudit(
                'Individual Bill Generation',
                "Generated billing for {$consumer->first_name} {$consumer->last_name} (Account: {$consumer->account_number}) for " . Carbon::parse($billingDate)->format('F Y'),
                $request->user()->id
            );

            DB::commit();

            return redirect()->route('billings.index')
                ->with('success', "Successfully generated billing for {$consumer->first_name} {$consumer->last_name}.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to generate billing: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new billing.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $consumers = Consumer::select('id', 'first_name', 'last_name', 'account_number')
            ->orderBy('first_name')
            ->get();

        return view('billings.create', compact('consumers'));
    }

    /**
     * Store a newly created billing in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'consumer_id' => 'required|exists:consumers,id',
            'billing_month' => 'required|date_format:Y-m',
            'previous_reading' => 'required|numeric|min:0',
            'current_reading' => 'required|numeric|min:0|gt:previous_reading',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date|after:today',
            'status' => 'required|in:pending,paid,overdue,cancelled',
        ]);

        // Calculate consumption
        $validated['consumption'] = $validated['current_reading'] - $validated['previous_reading'];

        // Set the current user as the created_by
        $validated['created_by'] = auth()->id();

        Billing::create($validated);

        return redirect()->route('billings.index')
            ->with('success', 'Billing record created successfully.');
    }

    /**
     * Display the specified billing.
     *
     * @param  \App\Models\Billing  $billing
     * @return \Illuminate\View\View
     */
    public function show(Billing $billing)
    {
        $billing->load('consumer', 'payments');
        return view('billings.show', compact('billing'));
    }

    /**
     * Show the form for editing the specified billing.
     *
     * @param  \App\Models\Billing  $billing
     * @return \Illuminate\View\View
     */
    public function edit(Billing $billing)
    {
        $consumers = Consumer::select('id', 'first_name', 'last_name', 'account_number')
            ->orderBy('first_name')
            ->get();

        return view('billings.edit', compact('billing', 'consumers'));
    }

    /**
     * Update the specified billing in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Billing  $billing
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Billing $billing)
    {
        $validated = $request->validate([
            'consumer_id' => 'required|exists:consumers,id',
            'billing_month' => 'required|date_format:Y-m',
            'previous_reading' => 'required|numeric|min:0',
            'current_reading' => 'required|numeric|min:0|gt:previous_reading',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,paid,overdue,cancelled',
        ]);

        // Calculate consumption
        $validated['consumption'] = $validated['current_reading'] - $validated['previous_reading'];

        $billing->update($validated);

        return redirect()->route('billings.index')
            ->with('success', 'Billing record updated successfully.');
    }

    /**
     * Remove the specified billing from storage.
     *
     * @param  \App\Models\Billing  $billing
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Billing $billing)
    {
        // Prevent deletion if there are payments
        if ($billing->payments()->exists()) {
            return redirect()->back()
                ->with('error', 'Cannot delete billing record with existing payments.');
        }

        $billing->delete();

        return redirect()->route('billings.index')
            ->with('success', 'Billing record deleted successfully.');
    }

    /**
     * Show the payment form for a billing.
     *
     * @param  \App\Models\Billing  $billing
     * @return \Illuminate\View\View
     */
    public function createPayment(Billing $billing)
    {
        // Calculate the remaining balance
        $totalPaid = $billing->payments()->sum('amount');
        $remainingBalance = $billing->amount - $totalPaid;

        return view('billings.payment', compact('billing', 'remainingBalance'));
    }

    /**
     * Process payment for a billing.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Billing  $billing
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processPayment(Request $request, Billing $billing)
    {
        $validated = $request->validate([
            'amount_paid' => 'required|numeric|min:0.01|max:' . ($billing->amount - $billing->payments()->sum('amount')),
            'payment_date' => 'required|date|before_or_equal:today',
            'payment_method' => 'required|in:cash,check,online_transfer,other',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        // Create payment record
        $billing->payments()->create([
            'amount' => $validated['amount_paid'],
            'payment_date' => $validated['payment_date'],
            'payment_method' => $validated['payment_method'],
            'reference_number' => $validated['reference_number'],
            'notes' => $validated['notes'],
            'received_by' => auth()->id(),
        ]);

        // Update billing status if fully paid
        $totalPaid = $billing->payments()->sum('amount');
        if ($totalPaid >= $billing->amount) {
            $billing->update(['status' => 'paid']);
        }

        return redirect()->route('billings.show', $billing)
            ->with('success', 'Payment processed successfully.');
    }

    /**
     * Display a printable billing statement.
     */
    public function print(Billing $billing)
    {
        $billing->load(['consumer', 'payments.receivedBy']);

        return view('billings.print', compact('billing'));
    }

    /**
     * Display the printable receipt for a payment.
     */
    public function receipt(Payment $payment)
    {
        $payment->load(['billing.consumer', 'receivedBy']);

        return view('billings.receipt', compact('payment'));
    }

    /**
     * Calculate consumption charge based on tiered pricing.
     */
    private function calculateConsumptionCharge(float $consumption, string $connectionType): float
    {
        $MINIMUM_CHARGE = 200.00;
        $MINIMUM_CONSUMPTION = 10.00;
        $RESIDENTIAL_RATE = 15.00;
        $COMMERCIAL_RATE = 20.00;

        // If consumption is 10 m³ or below, charge minimum
        if ($consumption <= $MINIMUM_CONSUMPTION) {
            return $MINIMUM_CHARGE;
        }

        // Calculate excess consumption
        $excess = $consumption - $MINIMUM_CONSUMPTION;

        // Determine rate based on connection type
        $rate = strtolower($connectionType) === 'commercial' ? $COMMERCIAL_RATE : $RESIDENTIAL_RATE;

        // Return minimum charge + excess charge
        return $MINIMUM_CHARGE + ($excess * $rate);
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
