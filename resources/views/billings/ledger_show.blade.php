@extends('layouts.app')

@section('title', 'Consumer Ledger')

@section('content')
<div class="space-y-6">

    {{-- ============================================================ --}}
    {{-- BREADCRUMB + PAGE TITLE                                       --}}
    {{-- ============================================================ --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <nav class="flex items-center gap-1.5 text-sm text-slate-400 mb-1">
                <a href="{{ route('billing.ledger') }}" class="text-slate-600 font-medium hover:text-blue-600 transition">Accounts Ledger</a>
                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-slate-500 font-medium truncate max-w-[150px] sm:max-w-xs">{{ $consumer->first_name }} {{ $consumer->last_name }}</span>
            </nav>
            <h1 class="text-2xl font-bold text-slate-800">Consumer Ledger</h1>
            <p class="text-sm text-slate-400 mt-0.5">Tracking financial history and statements</p>
        </div>
        <div>
            <a href="{{ route('billing.ledger') }}" class="inline-flex items-center justify-center gap-2 bg-white hover:bg-slate-50 text-slate-700 font-semibold px-4 py-2 rounded-lg border border-slate-200 transition-colors text-sm shadow-sm">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to List
            </a>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- 📄 CONSUMER LEDGER CARD (No Edit/View buttons)               --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        {{-- LEFT: Consumer Details --}}
        <div class="flex items-center gap-5">
            <div class="h-16 w-16 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white font-bold text-2xl flex-shrink-0 shadow-sm">
                {{ strtoupper(substr($consumer->first_name, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-800">{{ $consumer->first_name }} {{ $consumer->last_name }}</h2>
                <div class="flex flex-wrap gap-x-6 gap-y-1 mt-1 text-sm">
                    <p><span class="text-slate-500">Account #:</span> <span class="font-semibold text-slate-700">{{ $consumer->account_number }}</span></p>
                    <p><span class="text-slate-500">Meter #:</span> <span class="font-semibold text-slate-700">{{ $consumer->meter_number ?? 'N/A' }}</span></p>
                    <p><span class="text-slate-500">Type:</span> <span class="font-semibold text-slate-700">{{ ucfirst($consumer->connection_type) }}</span></p>
                    <p><span class="text-slate-500">Address:</span> <span class="font-semibold text-slate-700">{{ $consumer->address ?? 'N/A' }}</span></p>
                    <p><span class="text-slate-500">Status:</span> 
                        <span class="inline-flex items-center gap-1 font-semibold {{ $consumer->connection_status === 'active' ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ ucfirst($consumer->connection_status) }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
        {{-- RIGHT: Actions --}}
        <div class="flex gap-2">
            <button type="button" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm px-4 py-2 rounded-lg transition-colors border border-slate-200 flex items-center gap-2">
                <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Export
            </button>
            <button type="button" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm px-4 py-2 rounded-lg transition-colors border border-slate-200 flex items-center gap-2">
                <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print
            </button>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- 🎛 LEDGER FILTERS                                             --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4">
        <form action="{{ route('billing.ledger.show', $consumer) }}" method="GET" class="flex flex-wrap items-center gap-3">
            <select name="year" class="text-sm rounded-lg border border-slate-200 px-3 py-2 text-slate-600 focus:ring-2 focus:ring-blue-500">
                <option value="">All Years</option>
                @for($y = date('Y'); $y >= 2020; $y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            <select name="month" class="text-sm rounded-lg border border-slate-200 px-3 py-2 text-slate-600 focus:ring-2 focus:ring-blue-500">
                <option value="">All Months</option>
                @foreach(range(1, 12) as $m)
                    <option value="{{ sprintf('%02d', $m) }}" {{ $month == sprintf('%02d', $m) ? 'selected' : '' }}>
                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                    </option>
                @endforeach
            </select>
            <select name="status" class="text-sm rounded-lg border border-slate-200 bg-white px-3 py-2 text-slate-600 focus:ring-2 focus:ring-blue-500">
                <option value="">All Status</option>
                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="paid" {{ $status === 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="overdue" {{ $status === 'overdue' ? 'selected' : '' }}>Overdue</option>
            </select>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 border border-blue-600 rounded-lg text-sm transition shadow-sm">
                Filter
            </button>
            @if($year || $month || $status)
                <a href="{{ route('billing.ledger.show', $consumer) }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium px-2 py-2 transition">
                    Clear
                </a>
            @endif
        </form>
    </div>

    {{-- ============================================================ --}}
    {{-- 📊 LEDGER TABLE SECTION                                        --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center gap-2">
            <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="text-base font-bold text-slate-800">Ledger Statement</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-6 py-3 text-left font-bold uppercase text-slate-400 tracking-wider text-[11px]">Month</th>
                        <th class="px-6 py-3 text-right font-bold uppercase text-slate-400 tracking-wider text-[11px]">Bill (Due)</th>
                        <th class="px-6 py-3 text-right font-bold uppercase text-slate-400 tracking-wider text-[11px]">Payment</th>
                        <th class="px-6 py-3 text-right font-bold uppercase text-slate-400 tracking-wider text-[11px]">Balance</th>
                        <th class="px-6 py-3 text-left font-bold uppercase text-slate-400 tracking-wider text-[11px]">Payment Date</th>
                        <th class="px-6 py-3 text-left font-bold uppercase text-slate-400 tracking-wider text-[11px]">OR Number</th>
                        <th class="px-6 py-3 text-center font-bold uppercase text-slate-400 tracking-wider text-[11px]">Status</th>
                        <th class="px-6 py-3 text-right font-bold uppercase text-slate-400 tracking-wider text-[11px]">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($billings as $bill)
                        @php
                            $totalPayments = $bill->payments->sum('amount');
                            $latestPayment = $bill->payments->sortByDesc('payment_date')->first();
                            
                            $statusColors = [
                                'paid' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                'overdue' => 'bg-red-50 text-red-700 border-red-200',
                                'cancelled' => 'bg-slate-100 text-slate-500 border-slate-200',
                            ];
                            $sc = $statusColors[$bill->status] ?? $statusColors['pending'];
                        @endphp
                        <tr class="hover:bg-blue-50/30 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-700">
                                {{ \Carbon\Carbon::parse($bill->billing_month)->format('M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-amber-600">
                                ₱{{ number_format($bill->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-emerald-600">
                                {{ $totalPayments > 0 ? '₱' . number_format($totalPayments, 2) : '—' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-slate-800">
                                ₱{{ number_format($bill->balance, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-500">
                                {{ $latestPayment ? $latestPayment->payment_date->format('M d, Y') : '—' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-500 font-medium">
                                {{ $latestPayment && $latestPayment->reference_number ? $latestPayment->reference_number : '—' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center text-[11px] font-bold uppercase px-2 py-0.5 rounded-full border {{ $sc }}">
                                    {{ $bill->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <a href="{{ route('billings.show', $bill) }}" class="text-blue-600 hover:text-blue-800 font-semibold">View</a>
                                @if(in_array($bill->status, ['pending', 'overdue']))
                                    <span class="text-slate-300 mx-1">|</span>
                                    <a href="{{ route('billings.payments.create', $bill) }}" class="text-emerald-600 hover:text-emerald-800 font-semibold">Pay</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="h-10 w-10 text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    No billing or payment records found for this consumer.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
