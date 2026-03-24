@extends('layouts.app')

@section('title', 'Payments')

@section('content')
<div class="space-y-6">

    {{-- ============================================================ --}}
    {{-- PAGE HEADER                                                   --}}
    {{-- ============================================================ --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Payment Management</h1>
            <p class="text-sm text-slate-500 mt-0.5">Process consumer payments and view transaction records</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-100 px-3 py-1.5 rounded-full">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                System Online
            </span>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- SUMMARY STAT TILES                                            --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-5 flex items-center gap-4">
            <div class="h-10 w-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Total Collected</p>
                <p class="text-xl font-extrabold text-slate-800">₱{{ number_format($totalPayments, 2) }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-5 flex items-center gap-4">
            <div class="h-10 w-10 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600 flex-shrink-0">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Today's Collection</p>
                <p class="text-xl font-extrabold text-slate-800">₱{{ number_format($paymentsToday, 2) }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-5 flex items-center gap-4">
            <div class="h-10 w-10 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 flex-shrink-0">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">This Month</p>
                <p class="text-xl font-extrabold text-slate-800">₱{{ number_format($paymentsThisMonth, 2) }}</p>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- PAYMENT RECORDS TABLE                                         --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded-xl border border-slate-100 shadow-sm">
        {{-- Table Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-6 py-4 border-b border-slate-100">
            <div>
                <h2 class="text-base font-bold text-slate-800">Payment Records</h2>
                <p class="text-xs text-slate-400 mt-0.5">All collected payment transactions</p>
            </div>
            {{-- Filter Row --}}
            <form action="{{ route('payments.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search name, OR #…"
                    class="text-xs rounded-lg border border-slate-200 px-3 py-2 text-slate-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-36">
                <select name="payment_method"
                    class="text-xs rounded-lg border border-slate-200 bg-white px-3 py-2 text-slate-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Methods</option>
                    <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="check" {{ request('payment_method') === 'check' ? 'selected' : '' }}>Check</option>
                    <option value="online_transfer" {{ request('payment_method') === 'online_transfer' ? 'selected' : '' }}>Online Transfer</option>
                    <option value="other" {{ request('payment_method') === 'other' ? 'selected' : '' }}>Other</option>
                </select>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                    class="text-xs rounded-lg border border-slate-200 bg-white px-3 py-2 text-slate-600 focus:ring-2 focus:ring-blue-500">
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                    class="text-xs rounded-lg border border-slate-200 bg-white px-3 py-2 text-slate-600 focus:ring-2 focus:ring-blue-500">
                <button type="submit"
                    class="text-xs bg-blue-600 text-white font-semibold px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    Filter
                </button>
                <a href="{{ route('payments.index') }}"
                    class="text-xs text-slate-500 hover:text-slate-700 font-medium px-3 py-2 rounded-lg hover:bg-slate-100 transition">
                    Reset
                </a>
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            @if($payments->count() > 0)
                <table class="min-w-full divide-y divide-slate-100">
                    <thead>
                        <tr class="bg-slate-50">
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-400">Payment Date</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-400">OR / Ref #</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-400">Consumer</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-400">Billing Month</th>
                            <th class="px-6 py-3 text-right text-xs font-bold uppercase tracking-wider text-slate-400">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-400">Method</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-400">Received By</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($payments as $payment)
                            <tr class="hover:bg-blue-50/30 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                    {{ $payment->payment_date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-blue-600">
                                        {{ $payment->reference_number ?? '—' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 text-xs font-bold flex-shrink-0">
                                            {{ strtoupper(substr($payment->billing->consumer->first_name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-slate-800">
                                                {{ $payment->billing->consumer->full_name ?? 'Unknown' }}
                                            </p>
                                            <p class="text-xs text-slate-400">
                                                {{ $payment->billing->consumer->account_number ?? '—' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    {{ optional($payment->billing->billing_month)->format('F Y') ?? '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <span class="text-sm font-bold text-emerald-600">
                                        ₱{{ number_format($payment->amount, 2) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700">
                                        {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    {{ $payment->receivedBy->name ?? 'System' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="h-14 w-14 rounded-xl bg-slate-100 flex items-center justify-center mb-4">
                        <svg class="h-7 w-7 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-700">No payment records found</p>
                    <p class="text-xs text-slate-400 mt-1">Try adjusting your search or filter criteria.</p>
                </div>
            @endif
        </div>

        {{-- Pagination --}}
        @if($payments->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $payments->links() }}
            </div>
        @endif
    </div>

</div>
@endsection