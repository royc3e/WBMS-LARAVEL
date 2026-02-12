<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of all payments.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Payment::with([
            'billing' => function ($q) {
                $q->with('consumer');
            }
        ])->latest('payment_date');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                    ->orWhereHas('billing', function ($q) use ($search) {
                        $q->whereHas('consumer', function ($q) use ($search) {
                            $q->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%")
                                ->orWhere('account_number', 'like', "%{$search}%");
                        });
                    });
            });
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        $payments = $query->paginate(20);

        // Summary statistics
        $totalPayments = Payment::sum('amount');
        $paymentsToday = Payment::whereDate('payment_date', today())->sum('amount');
        $paymentsThisMonth = Payment::whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->sum('amount');

        return view('payments.index', compact(
            'payments',
            'totalPayments',
            'paymentsToday',
            'paymentsThisMonth'
        ));
    }
}
