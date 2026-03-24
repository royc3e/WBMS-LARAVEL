@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<div class="space-y-6">

    {{-- ============================================================ --}}
    {{-- REPORT CONTROLS CARD                                         --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 space-y-6">
        
        {{-- PAGE HEADER --}}
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Reports</h1>
            <p class="text-sm text-slate-500 mt-1">Generate and analyze water billing system reports</p>
        </div>

        {{-- 📊 REPORT FILTER SECTION --}}
        <form action="{{ route('reports.index') }}" method="GET" id="reportForm">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                
                {{-- Report Type --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-widest mb-2">Report Type</label>
                    <select name="report_type" onchange="this.form.submit()" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm text-slate-700 bg-white">
                        <option value="collection" {{ $reportType === 'collection' ? 'selected' : '' }}>Collection Summary Report</option>
                        <option value="accounts" {{ $reportType === 'accounts' ? 'selected' : '' }}>Accounts Summary Report</option>
                        <option value="billing" {{ $reportType === 'billing' ? 'selected' : '' }}>Billing Report</option>
                    </select>
                </div>

                {{-- Date From --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-widest mb-2">Date From</label>
                    <input type="date" name="date_from" value="{{ $dateFrom }}" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm text-slate-700 bg-white shadow-sm">
                </div>

                {{-- Date To --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-widest mb-2">Date To</label>
                    <input type="date" name="date_to" value="{{ $dateTo }}" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm text-slate-700 bg-white shadow-sm">
                </div>

                {{-- Dynamic Filter --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-widest mb-2">Filter</label>
                    <select name="filter" id="dynamicFilter" onchange="this.form.submit()" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm text-slate-700 bg-white">
                        <option value="">All</option>
                        @if($reportType === 'collection')
                            <option value="cash" {{ $filter === 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="check" {{ $filter === 'check' ? 'selected' : '' }}>Check</option>
                            <option value="online_transfer" {{ $filter === 'online_transfer' ? 'selected' : '' }}>Online Transfer</option>
                        @elseif($reportType === 'accounts')
                            <option value="active" {{ $filter === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $filter === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="disconnected" {{ $filter === 'disconnected' ? 'selected' : '' }}>Disconnected</option>
                        @elseif($reportType === 'billing')
                            <option value="paid" {{ $filter === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="pending" {{ $filter === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="overdue" {{ $filter === 'overdue' ? 'selected' : '' }}>Overdue</option>
                        @endif
                    </select>
                </div>
            </div>

            {{-- 🔘 ACTION BUTTONS --}}
            <div class="flex flex-col sm:flex-row flex-wrap gap-3 mt-6 pt-6 border-t border-slate-100">
                <button type="submit" class="w-full sm:w-auto bg-blue-600 text-white font-semibold text-sm px-6 py-2.5 rounded-lg hover:bg-blue-700 hover:shadow-md transition-all duration-200 flex items-center justify-center gap-2 shadow-sm">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Generate Report
                </button>
                <button type="button" onclick="window.print()" class="w-full sm:w-auto bg-white text-slate-700 font-semibold text-sm px-6 py-2.5 rounded-lg hover:bg-slate-50 hover:text-blue-600 transition-all duration-200 border border-slate-200 shadow-sm flex items-center justify-center gap-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print
                </button>
                <button type="button" class="w-full sm:w-auto bg-emerald-600 text-white font-semibold text-sm px-6 py-2.5 rounded-lg hover:bg-emerald-700 hover:shadow-md transition-all duration-200 flex items-center justify-center gap-2 shadow-sm sm:ml-auto">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                    </svg>
                    Export CSV
                </button>
            </div>
        </form>

    </div>

    {{-- ============================================================ --}}
    {{-- 📄 REPORT DISPLAY SECTION                                      --}}
    {{-- ============================================================ --}}
    <div class="bg-slate-50 border border-slate-200 rounded-xl p-6 min-h-[300px] shadow-sm flex flex-col items-center">
        
        <div class="w-full flex justify-between items-center mb-6">
            <h2 class="text-lg font-bold text-slate-800">
                @if($reportType === 'collection') Collection Summary Report
                @elseif($reportType === 'accounts') Accounts Summary Report
                @elseif($reportType === 'billing') Billing Report
                @endif
            </h2>
            <span class="text-xs font-semibold text-slate-400 bg-white px-3 py-1 rounded-full border border-slate-200">
                {{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('M d, Y') }}
            </span>
        </div>

        {{-- DYNAMIC REPORT CONTENT --}}
        <div class="w-full bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
            
            @if($reportType === 'collection')
                {{-- COLLECTION SUMMARY --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-6 bg-gradient-to-br from-slate-50 to-slate-100/50 border-b border-slate-100">
                    <div class="bg-white p-4 rounded-lg border border-slate-100 shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Collected</p>
                            <p class="text-2xl font-extrabold text-blue-600 mt-1">₱{{ number_format($data['total_collected'] ?? 0, 2) }}</p>
                        </div>
                        <div class="h-10 w-10 bg-blue-50 rounded-full flex items-center justify-center text-blue-600">
                            <span class="text-xl font-bold">₱</span>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-lg border border-slate-100 shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Transactions</p>
                            <p class="text-2xl font-extrabold text-slate-800 mt-1">{{ number_format($data['transaction_count'] ?? 0) }}</p>
                        </div>
                        <div class="h-10 w-10 bg-slate-100 rounded-full flex items-center justify-center text-slate-500">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left font-bold uppercase text-slate-400 tracking-wider text-[11px]">Payment Date</th>
                                <th class="px-6 py-3 text-left font-bold uppercase text-slate-400 tracking-wider text-[11px]">OR Number</th>
                                <th class="px-6 py-3 text-left font-bold uppercase text-slate-400 tracking-wider text-[11px]">Consumer</th>
                                <th class="px-6 py-3 text-left font-bold uppercase text-slate-400 tracking-wider text-[11px]">Method</th>
                                <th class="px-6 py-3 text-right font-bold uppercase text-slate-400 tracking-wider text-[11px]">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($data['payments'] ?? collect() as $payment)
                            <tr class="hover:bg-blue-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-slate-600">{{ $payment->payment_date->format('M d, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-800">{{ $payment->reference_number ?? '—' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-600">{{ $payment->billing->consumer->full_name ?? 'Unknown' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap"><span class="px-2.5 py-1 text-[11px] font-bold uppercase rounded-full bg-slate-100 text-slate-600">{{ $payment->payment_method }}</span></td>
                                <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-emerald-600">₱{{ number_format($payment->amount, 2) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="px-6 py-8 text-center text-slate-400">No payment records found for the selected period.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            @elseif($reportType === 'accounts')
                {{-- ACCOUNTS SUMMARY --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-6 bg-gradient-to-br from-slate-50 to-slate-100/50 border-b border-slate-100">
                    <div class="bg-white p-4 rounded-lg border border-slate-100 shadow-sm">
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Total Consumers</p>
                        <p class="text-2xl font-extrabold text-blue-600">{{ number_format($data['total_accounts'] ?? 0) }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg border border-slate-100 shadow-sm">
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Active Accounts</p>
                        <p class="text-2xl font-extrabold text-emerald-600">{{ number_format($data['active_accounts'] ?? 0) }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg border border-slate-100 shadow-sm">
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Inactive/Disconnected</p>
                        <p class="text-2xl font-extrabold text-rose-500">{{ number_format($data['inactive_accounts'] ?? 0) }}</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left font-bold uppercase text-slate-400 tracking-wider text-[11px]">Account No.</th>
                                <th class="px-6 py-3 text-left font-bold uppercase text-slate-400 tracking-wider text-[11px]">Consumer Name</th>
                                <th class="px-6 py-3 text-left font-bold uppercase text-slate-400 tracking-wider text-[11px]">Type</th>
                                <th class="px-6 py-3 text-center font-bold uppercase text-slate-400 tracking-wider text-[11px]">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($data['consumers'] ?? collect() as $consumer)
                            <tr class="hover:bg-blue-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap font-semibold text-blue-600">{{ $consumer->account_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-800 font-medium">{{ $consumer->first_name }} {{ $consumer->last_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-600">{{ ucfirst($consumer->connection_type) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center text-[10px] font-bold uppercase px-2 py-0.5 rounded-full border {{ $consumer->connection_status === 'active' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200' }}">
                                        {{ $consumer->connection_status }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="px-6 py-8 text-center text-slate-400">No account records found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            @elseif($reportType === 'billing')
                {{-- BILLING REPORT --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-6 bg-gradient-to-br from-slate-50 to-slate-100/50 border-b border-slate-100">
                    <div class="bg-white p-4 rounded-lg border border-slate-100 shadow-sm">
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Total Amount Billed</p>
                        <p class="text-2xl font-extrabold text-blue-600">₱{{ number_format($data['total_amount_billed'] ?? 0, 2) }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg border border-slate-100 shadow-sm">
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Total Unpaid Balance</p>
                        <p class="text-2xl font-extrabold text-amber-500">₱{{ number_format($data['total_pending'] ?? 0, 2) }}</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left font-bold uppercase text-slate-400 tracking-wider text-[11px]">Billing Month</th>
                                <th class="px-6 py-3 text-left font-bold uppercase text-slate-400 tracking-wider text-[11px]">Consumer</th>
                                <th class="px-6 py-3 text-right font-bold uppercase text-slate-400 tracking-wider text-[11px]">Amount</th>
                                <th class="px-6 py-3 text-left font-bold uppercase text-slate-400 tracking-wider text-[11px]">Due Date</th>
                                <th class="px-6 py-3 text-center font-bold uppercase text-slate-400 tracking-wider text-[11px]">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($data['billings'] ?? collect() as $billing)
                            <tr class="hover:bg-blue-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-700">{{ \Carbon\Carbon::parse($billing->billing_month)->format('M Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-600">{{ $billing->consumer->full_name ?? 'Unknown' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-slate-800">₱{{ number_format($billing->amount, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-500">{{ \Carbon\Carbon::parse($billing->due_date)->format('M d, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @php
                                        $sc = match($billing->status) {
                                            'paid' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                            'overdue' => 'bg-red-50 text-red-700 border-red-200',
                                            default => 'bg-slate-100 text-slate-500 border-slate-200'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center text-[10px] font-bold uppercase px-2 py-0.5 rounded-full border {{ $sc }}">
                                        {{ $billing->status }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="px-6 py-8 text-center text-slate-400">No billing records found for the selected period.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    </div>
</div>

<style>
    /* Add simple CSS to hide headers and navbar when printing */
    @media print {
        body { visibility: hidden; }
        .bg-slate-50.border.border-slate-200.rounded-xl { visibility: visible; position: absolute; left: 0; top: 0; min-width: 100vw; box-shadow: none; border: none; }
    }
</style>


@endsection
