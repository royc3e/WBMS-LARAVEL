<?php

namespace App\Http\Controllers;

use App\Models\Consumer;
use App\Models\ServiceStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ServiceStatusController extends Controller
{
    /**
     * Disconnect a consumer's water service.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Consumer  $consumer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disconnect(Request $request, Consumer $consumer)
    {
        // Only admin and staff can perform this action
        $user = $request->user();
        if (!in_array($user->role, ['admin', 'staff'])) {
            return redirect()->back()
                ->with('error', 'You do not have permission to disconnect services.');
        }

        // Validate the consumer has unpaid or overdue bills
        $unpaidBillings = $consumer->billings()
            ->whereIn('status', ['pending', 'overdue'])
            ->count();

        if ($unpaidBillings === 0) {
            return redirect()->back()
                ->with('error', 'Cannot disconnect this consumer. They have no unpaid or overdue bills.');
        }

        // Check if already disconnected
        if ($consumer->connection_status === 'disconnected') {
            return redirect()->back()
                ->with('error', 'This consumer is already disconnected.');
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            // Create disconnection notice record
            ServiceStatus::create([
                'consumer_id' => $consumer->id,
                'status' => 'disconnected',
                'action_type' => 'disconnection',
                'reason' => $validated['reason'],
                'status_date' => Carbon::today(),
                'processed_by' => $user->id,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Update consumer connection status
            $consumer->update([
                'connection_status' => 'disconnected',
            ]);

            // Audit log
            $this->logAudit(
                'Service Disconnected',
                "Disconnected service for {$consumer->first_name} {$consumer->last_name} (Account: {$consumer->account_number}). Reason: {$validated['reason']}",
                $user->id
            );

            DB::commit();

            return redirect()->route('consumers.show', $consumer)
                ->with('success', "Service has been disconnected for {$consumer->first_name} {$consumer->last_name}.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to disconnect service: ' . $e->getMessage());
        }
    }

    /**
     * Reconnect a consumer's water service.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Consumer  $consumer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reconnect(Request $request, Consumer $consumer)
    {
        // Only admin and staff can perform this action
        $user = $request->user();
        if (!in_array($user->role, ['admin', 'staff'])) {
            return redirect()->back()
                ->with('error', 'You do not have permission to reconnect services.');
        }

        // Validate the consumer has settled all balances
        $outstandingBalance = $consumer->billings()
            ->whereIn('status', ['pending', 'overdue'])
            ->get()
            ->sum(function ($billing) {
                $paid = $billing->payments()->sum('amount');
                return $billing->amount - $paid;
            });

        if ($outstandingBalance > 0) {
            return redirect()->back()
                ->with('error', 'Cannot reconnect this consumer. They still have an outstanding balance of ₱' . number_format($outstandingBalance, 2) . '. All balances must be fully settled before reconnection.');
        }

        // Check if already active
        if ($consumer->connection_status === 'active') {
            return redirect()->back()
                ->with('error', 'This consumer is already active.');
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            // Create reconnection request record
            ServiceStatus::create([
                'consumer_id' => $consumer->id,
                'status' => 'active',
                'action_type' => 'reconnection',
                'reason' => $validated['reason'],
                'status_date' => Carbon::today(),
                'processed_by' => $user->id,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Update consumer connection status
            $consumer->update([
                'connection_status' => 'active',
            ]);

            // Audit log
            $this->logAudit(
                'Service Reconnected',
                "Reconnected service for {$consumer->first_name} {$consumer->last_name} (Account: {$consumer->account_number}). Reason: {$validated['reason']}",
                $user->id
            );

            DB::commit();

            return redirect()->route('consumers.show', $consumer)
                ->with('success', "Service has been reconnected for {$consumer->first_name} {$consumer->last_name}.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to reconnect service: ' . $e->getMessage());
        }
    }

    /**
     * Log action to audit log.
     */
    private function logAudit(string $action, string $details, int $userId): void
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
