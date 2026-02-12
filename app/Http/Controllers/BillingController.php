<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Consumer;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
     * Generate billing for all active consumers.
     */
    public function generateActive(Request $request)
    {
        $validated = $request->validate([
            'billing_month' => 'required|date_format:Y-m',
            'due_date' => 'required|date|after:today',
        ]);

        $ratePerUnit = (float) config('billing.default_rate_per_unit', 0);
        $billingDate = $validated['billing_month'] . '-01';
        $dueDate = $validated['due_date'];

        $activeConsumers = Consumer::where('connection_status', 'active')->get();

        if ($activeConsumers->isEmpty()) {
            return redirect()->back()->with('info', 'No active consumers found to generate billing.');
        }

        $created = 0;

        foreach ($activeConsumers as $consumer) {
            $latestBilling = $consumer->billings()->latest('billing_month')->first();

            $previousReading = $latestBilling?->current_reading ?? 0;
            $currentReading = $previousReading; // Replace with actual reading logic when available
            $consumption = max($currentReading - $previousReading, 0);
            $amount = $consumption * $ratePerUnit;

            Billing::create([
                'consumer_id' => $consumer->id,
                'billing_month' => $billingDate,
                'previous_reading' => $previousReading,
                'current_reading' => $currentReading,
                'consumption' => $consumption,
                'amount' => $amount,
                'due_date' => $dueDate,
                'status' => 'pending',
                'created_by' => $request->user()->id,
                'notes' => sprintf('Generated for active consumers at %.2f rate', $ratePerUnit),
            ]);

            $created++;
        }

        return redirect()->route('billings.index')
            ->with('success', "Generated {$created} billing records for active consumers.");
    }

    /**
     * Generate billing for selected consumers.
     */
    public function generateSelected(Request $request)
    {
        $validated = $request->validate([
            'consumer_ids' => 'required|array|min:1',
            'consumer_ids.*' => 'exists:consumers,id',
            'billing_month' => 'required|date_format:Y-m',
            'due_date' => 'required|date|after:today',
            'rate_per_unit' => 'required|numeric|min:0',
        ]);

        $consumers = Consumer::whereIn('id', $validated['consumer_ids'])->get();

        $created = 0;
        $billingDate = $validated['billing_month'] . '-01';
        $dueDate = $validated['due_date'];

        foreach ($consumers as $consumer) {
            $latestBilling = $consumer->billings()->latest('billing_month')->first();

            $previousReading = $latestBilling?->current_reading ?? 0;
            $currentReading = $previousReading; // Replace with actual reading logic when available
            $consumption = max($currentReading - $previousReading, 0);
            $amount = $consumption * $validated['rate_per_unit'];

            Billing::create([
                'consumer_id' => $consumer->id,
                'billing_month' => $billingDate,
                'previous_reading' => $previousReading,
                'current_reading' => $currentReading,
                'consumption' => $consumption,
                'amount' => $amount,
                'due_date' => $dueDate,
                'status' => 'pending',
                'created_by' => $request->user()->id,
                'notes' => 'Generated via custom selection',
            ]);

            $created++;
        }

        return redirect()->route('billings.index')
            ->with('success', "Generated {$created} billing records for selected consumers.");
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
}
