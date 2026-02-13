<?php

namespace App\Http\Controllers;

use App\Models\Consumer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class ConsumerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search', '');
        $status = $request->input('status', '');

        $query = Consumer::query()
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('account_number', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($status && in_array($status, ['active', 'inactive', 'disconnected', 'pending']), function ($q) use ($status) {
                $q->where('connection_status', $status);
            })
            ->latest();

        $consumers = $query->paginate($perPage);

        return view('consumers.index', [
            'consumers' => $consumers,
            'filters' => [
                'search' => $search,
                'status' => $status,
                'per_page' => $perPage
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('consumers.create', [
            'connectionTypes' => $this->getConnectionTypes(),
            'statuses' => $this->getStatuses()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate($this->validationRules());

        // Generate a unique account number if not provided
        if (empty($validated['account_number'])) {
            $validated['account_number'] = $this->generateAccountNumber();
        }

        $consumer = Consumer::create($validated);

        // Log to audit
        $this->logAudit(
            'Consumer Created',
            "New consumer registered: {$consumer->first_name} {$consumer->last_name} (Account: {$consumer->account_number})",
            $request->user()->id
        );

        return redirect()
            ->route('consumers.show', $consumer->id)
            ->with('success', 'Consumer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Consumer $consumer)
    {
        $bills = $consumer->bills ?? collect();
        $payments = $consumer->payments ?? collect();

        $outstandingBalance = $bills->sum('amount') - $payments->sum('amount');
        $lastBill = $bills->isNotEmpty() ? $bills->sortByDesc('billing_date')->first() : null;
        $recentActivity = $consumer->activity ? $consumer->activity()->latest()->take(5)->get() : collect();

        return view('consumers.show', [
            'consumer' => $consumer,
            'bills' => $bills,
            'payments' => $payments,
            'outstandingBalance' => $outstandingBalance,
            'lastBill' => $lastBill,
            'recentActivity' => $recentActivity,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Consumer $consumer)
    {
        return view('consumers.edit', [
            'consumer' => $consumer,
            'connectionTypes' => $this->getConnectionTypes(),
            'statuses' => $this->getStatuses()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Consumer $consumer)
    {
        $validated = $request->validate($this->validationRules($consumer->id));

        $oldStatus = $consumer->connection_status;
        $consumer->update($validated);

        // Log to audit
        $details = "Updated consumer: {$consumer->first_name} {$consumer->last_name} (Account: {$consumer->account_number})";
        if ($oldStatus !== $validated['connection_status']) {
            $details .= " | Status changed from {$oldStatus} to {$validated['connection_status']}";
        }
        $this->logAudit('Consumer Updated', $details, $request->user()->id);

        return redirect()->route('consumers.show', $consumer)
            ->with('success', 'Consumer updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Consumer $consumer)
    {
        $accountNumber = $consumer->account_number;
        $name = $consumer->first_name . ' ' . $consumer->last_name;

        $consumer->delete();

        // Log to audit
        $this->logAudit(
            'Consumer Deleted',
            "Deleted consumer: {$name} (Account: {$accountNumber})",
            request()->user()->id
        );

        return redirect()->route('consumers.index')
            ->with('success', 'Consumer deleted successfully!');
    }

    /**
     * Generate a unique account number
     */
    protected function generateAccountNumber()
    {
        do {
            $accountNumber = 'WB' . date('Y') . strtoupper(Str::random(6));
        } while (Consumer::where('account_number', $accountNumber)->exists());

        return $accountNumber;
    }

    /**
     * Get the validation rules for the consumer
     */
    protected function validationRules($consumerId = null)
    {
        return [
            'account_number' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('consumers', 'account_number')->ignore($consumerId)
            ],
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => [
                'nullable',
                'email',
                'max:100',
                Rule::unique('consumers', 'email')->ignore($consumerId)
            ],
            'phone' => 'nullable|string|max:20',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'connection_type' => 'required|in:residential,commercial,industrial,government',
            'connection_status' => 'required|in:active,inactive,disconnected,pending',
            'meter_number' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('consumers', 'meter_number')->ignore($consumerId)
            ],
            'connection_date' => 'required|date',
            'notes' => 'nullable|string',
        ];
    }

    /**
     * Get the available connection types
     */
    protected function getConnectionTypes()
    {
        return [
            'residential' => 'Residential',
            'commercial' => 'Commercial',
            'industrial' => 'Industrial',
            'government' => 'Government',
        ];
    }

    /**
     * Get the available statuses
     */
    protected function getStatuses()
    {
        return [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'disconnected' => 'Disconnected',
            'pending' => 'Pending',
        ];
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
