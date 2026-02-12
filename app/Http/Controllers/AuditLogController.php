<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuditLogController extends Controller
{
    /**
     * Display a listing of audit logs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // For now, we'll show recent database activity from payments and billings
        // In a production app, you might use a package like spatie/laravel-activitylog

        $query = DB::table('payments')
            ->join('billings', 'payments.billing_id', '=', 'billings.id')
            ->join('consumers', 'billings.consumer_id', '=', 'consumers.id')
            ->join('users', 'payments.received_by', '=', 'users.id')
            ->select(
                'payments.created_at as date_time',
                'users.name as user_name',
                DB::raw("'Payment Recorded' as action"),
                DB::raw("CONCAT('Payment of ₱', payments.amount, ' for ', consumers.name, ' - OR#: ', payments.reference_number) as details")
            )
            ->orderBy('payments.created_at', 'desc');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                    ->orWhere('consumers.name', 'like', "%{$search}%")
                    ->orWhere('payments.reference_number', 'like', "%{$search}%");
            });
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('payments.created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('payments.created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(50);

        // Activity statistics
        $totalLogs = $logs->total();
        $logsToday = DB::table('payments')
            ->whereDate('created_at', today())
            ->count();
        $logsThisWeek = DB::table('payments')
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        return view('audit-logs.index', compact(
            'logs',
            'totalLogs',
            'logsToday',
            'logsThisWeek'
        ));
    }
}
