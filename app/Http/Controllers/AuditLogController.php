<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AuditLogController extends Controller
{
    /**
     * Display a listing of audit logs.
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $action = $request->get('action', '');
        $dateFrom = $request->get('date_from', '');
        $dateTo = $request->get('date_to', '');
        $userId = $request->get('user_id', '');
        $view = $request->get('view', 'system'); // 'system' or 'consumers'

        $query = AuditLog::with('user')
            ->orderBy('date_time', 'desc');

        // Search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                    ->orWhere('details', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Action filter
        if ($action) {
            $query->where('action', $action);
        }

        // Date range filter
        if ($dateFrom) {
            $query->whereDate('date_time', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('date_time', '<=', $dateTo);
        }

        // User filter
        if ($userId) {
            $query->where('user_id', $userId);
        }

        $logs = $query->paginate(25);

        // Get unique action types for filter dropdown
        $actionTypes = DB::table('audit_logs')
            ->select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        // Get all users for filter dropdown
        $users = DB::table('users')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        // Statistics
        $stats = $this->getStatistics();

        // Consumer Ledger Data
        $consumerLedger = $this->getConsumerLedger($request);

        return view('audit-logs.index', compact(
            'logs',
            'actionTypes',
            'users',
            'stats',
            'search',
            'action',
            'dateFrom',
            'dateTo',
            'userId',
            'view',
            'consumerLedger'
        ));
    }

    /**
     * Get audit log statistics.
     */
    private function getStatistics()
    {
        $totalLogs = AuditLog::count();

        $logsToday = AuditLog::whereDate('date_time', today())->count();

        $logsThisWeek = AuditLog::whereBetween('date_time', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->count();

        $logsThisMonth = AuditLog::whereYear('date_time', now()->year)
            ->whereMonth('date_time', now()->month)
            ->count();

        // Recent actions breakdown
        $recentActions = DB::table('audit_logs')
            ->select('action', DB::raw('count(*) as count'))
            ->whereDate('date_time', '>=', now()->subDays(7))
            ->groupBy('action')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        // Most active users (last 7 days)
        $activeUsers = DB::table('audit_logs')
            ->join('users', 'audit_logs.user_id', '=', 'users.id')
            ->select('users.name', DB::raw('count(*) as count'))
            ->whereDate('audit_logs.date_time', '>=', now()->subDays(7))
            ->groupBy('users.id', 'users.name')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        return [
            'total' => $totalLogs,
            'today' => $logsToday,
            'this_week' => $logsThisWeek,
            'this_month' => $logsThisMonth,
            'recent_actions' => $recentActions,
            'active_users' => $activeUsers,
        ];
    }

    /**
     * Export audit logs to CSV.
     */
    public function export(Request $request)
    {
        $query = AuditLog::with('user')->orderBy('date_time', 'desc');

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                    ->orWhere('details', 'like', "%{$search}%");
            });
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date_time', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date_time', '<=', $request->date_to);
        }

        $logs = $query->get();

        $filename = 'audit_logs_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');

            // Headers
            fputcsv($file, ['Date & Time', 'User', 'Action', 'Details']);

            // Data
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->date_time->format('Y-m-d H:i:s'),
                    $log->user->name ?? 'System',
                    $log->action,
                    $log->details,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get consumer ledger data.
     */
    private function getConsumerLedger(Request $request)
    {
        $search = $request->get('consumer_search', '');

        $query = DB::table('consumers')
            ->select(
                'consumers.id as consumer_id',
                'consumers.account_number',
                'consumers.first_name',
                'consumers.last_name',
                'consumers.connection_type'
            )
            ->where('consumers.connection_status', 'active')
            ->orderBy('consumers.account_number');

        // Search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('consumers.account_number', 'like', "%{$search}%")
                    ->orWhere('consumers.first_name', 'like', "%{$search}%")
                    ->orWhere('consumers.last_name', 'like', "%{$search}%");
            });
        }

        $consumers = $query->get();

        // Get detailed transactions for each consumer
        $consumerDetails = [];
        foreach ($consumers as $consumer) {
            $consumerDetails[$consumer->consumer_id] = $this->getConsumerTransactions($consumer->consumer_id);
        }

        return [
            'consumers' => $consumers,
            'details' => $consumerDetails,
        ];
    }

    /**
     * Get detailed transactions for a consumer.
     */
    private function getConsumerTransactions($consumerId)
    {
        $transactions = [];

        // Get billings
        $billings = DB::table('billings')
            ->where('consumer_id', $consumerId)
            ->orderBy('billing_month')
            ->get();

        foreach ($billings as $billing) {
            $transactions[] = [
                'date' => $billing->billing_month,
                'type' => 'Bill',
                'billing_month' => Carbon::parse($billing->billing_month)->format('M Y'),
                'amount' => $billing->amount,
                'payment' => 0,
                'balance' => 0, // Will calculate later
            ];

            // Get payments for this billing
            $payments = DB::table('payments')
                ->where('billing_id', $billing->id)
                ->orderBy('payment_date')
                ->get();

            foreach ($payments as $payment) {
                $transactions[] = [
                    'date' => $payment->payment_date,
                    'type' => 'Payment',
                    'billing_month' => Carbon::parse($payment->payment_date)->format('M d, Y'),
                    'amount' => 0,
                    'payment' => $payment->amount,
                    'balance' => 0, // Will calculate later
                ];
            }
        }

        // Calculate running balance
        $balance = 0;
        foreach ($transactions as &$transaction) {
            $balance += $transaction['amount'] - $transaction['payment'];
            $transaction['balance'] = $balance;
        }

        return $transactions;
    }
}

