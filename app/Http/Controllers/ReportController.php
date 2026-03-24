<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Consumer;
use App\Models\Payment;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display the Reports page and handle report generation.
     */
    public function index(Request $request)
    {
        $reportType = $request->input('report_type', 'collection');
        $dateFrom = $request->input('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->input('date_to', now()->toDateString());
        $filter = $request->input('filter', '');

        $data = [];

        switch ($reportType) {
            case 'collection':
                $query = Payment::with(['billing.consumer', 'receivedBy'])
                    ->whereDate('payment_date', '>=', $dateFrom)
                    ->whereDate('payment_date', '<=', $dateTo);

                if ($filter) {
                    $query->where('payment_method', strtolower($filter));
                }

                $payments = $query->orderByDesc('payment_date')->get();
                $data = [
                    'payments' => $payments,
                    'total_collected' => $payments->sum('amount'),
                    'transaction_count' => $payments->count(),
                ];
                break;

            case 'accounts':
                $query = Consumer::query();

                // Using date_from and date_to as creation date filters for accounts
                // if they want to see new accounts in a period, otherwise it just filters
                if ($filter) {
                    $query->where('connection_status', strtolower($filter));
                }

                $consumers = $query->orderBy('first_name')->get();
                $data = [
                    'consumers' => $consumers,
                    'total_accounts' => $consumers->count(),
                    'active_accounts' => $consumers->where('connection_status', 'active')->count(),
                    'inactive_accounts' => $consumers->where('connection_status', '!=', 'active')->count(),
                ];
                break;

            case 'billing':
                $query = Billing::with('consumer')
                    // Using billing_month for date filters
                    ->whereDate('billing_month', '>=', substr($dateFrom, 0, 7) . '-01')
                    ->whereDate('billing_month', '<=', substr($dateTo, 0, 7) . '-31');

                if ($filter) {
                    $query->where('status', strtolower($filter));
                }

                $billings = $query->orderByDesc('billing_month')->get();
                $data = [
                    'billings' => $billings,
                    'total_amount_billed' => $billings->sum('amount'),
                    'total_pending' => $billings->whereIn('status', ['pending', 'overdue'])->sum('amount'), 
                    // We sum amounts of pending/overdue to simulate unpaid unless they have partial payments
                ];
                break;
        }

        return view('reports.index', compact('reportType', 'dateFrom', 'dateTo', 'filter', 'data'));
    }
}
