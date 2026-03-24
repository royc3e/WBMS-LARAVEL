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
    {{-- PROCESS PAYMENT FORM                                          --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded-xl border border-slate-100 shadow-sm">
        {{-- Card Header --}}
        <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-100">
            <div class="h-9 w-9 rounded-lg bg-blue-600 flex items-center justify-center flex-shrink-0">
                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-800">Process Payment</h2>
                <p class="text-xs text-slate-400">Enter and process consumer payments</p>
            </div>
        </div>

        <div class="p-6 space-y-6">

            {{-- ── SEARCH BAR ── --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">Find Consumer / Bill</label>
                <form action="{{ route('payments.index') }}" method="GET" class="flex gap-2">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search by Consumer ID, Account Number, or Name…"
                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-200 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        >
                    </div>
                    <button type="submit"
                        class="flex-shrink-0 inline-flex items-center gap-2 bg-blue-600 text-white text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Search
                    </button>
                    @if(request('search'))
                        <a href="{{ route('payments.index') }}"
                            class="flex-shrink-0 inline-flex items-center gap-1 text-sm font-medium text-slate-500 bg-slate-100 hover:bg-slate-200 px-4 py-2.5 rounded-lg transition-colors">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            {{-- ── BILLING & PAYMENT INFO GRID ── --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- LEFT COLUMN: Billing Information --}}
                <div class="space-y-4">
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400 flex items-center gap-2">
                        <span class="h-px flex-1 bg-slate-100"></span>
                        Billing Information
                        <span class="h-px flex-1 bg-slate-100"></span>
                    </p>

                    <div class="grid grid-cols-1 gap-3">
                        {{-- Account Number --}}
                        <div class="bg-slate-50 border border-slate-100 rounded-lg p-4">
                            <p class="text-xs font-medium text-slate-400 mb-1">Account Number</p>
                            <p class="text-base font-semibold text-slate-800">
                                {{ $billing->consumer->account_number ?? '—' }}
                            </p>
                        </div>

                        {{-- Billing Period --}}
                        <div class="bg-slate-50 border border-slate-100 rounded-lg p-4">
                            <p class="text-xs font-medium text-slate-400 mb-1">Billing Period</p>
                            <p class="text-base font-semibold text-slate-800">
                                {{ optional($billing->billing_month ?? null)?->format('F Y') ?? '—' }}
                            </p>
                        </div>

                        {{-- Current Amount Due --}}
                        <div class="bg-blue-50 border border-blue-100 rounded-lg p-4">
                            <p class="text-xs font-medium text-blue-500 mb-1">Current Amount Due</p>
                            <p class="text-xl font-extrabold text-blue-700">
                                ₱{{ number_format($billing->amount ?? 0, 2) }}
                            </p>
                        </div>

                        {{-- Total Amount Due --}}
                        <div class="bg-amber-50 border border-amber-100 rounded-lg p-4">
                            <p class="text-xs font-medium text-amber-600 mb-1">Total Amount Due (with Balance)</p>
                            <p class="text-xl font-extrabold text-amber-700">
                                ₱{{ number_format(($billing->amount ?? 0) + ($billing->previous_balance ?? 0), 2) }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN: Consumer & Payment Details --}}
                <div class="space-y-4">
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400 flex items-center gap-2">
                        <span class="h-px flex-1 bg-slate-100"></span>
                        Consumer & Payment
                        <span class="h-px flex-1 bg-slate-100"></span>
                    </p>

                    <div class="grid grid-cols-1 gap-3">
                        {{-- Consumer Name --}}
                        <div class="bg-slate-50 border border-slate-100 rounded-lg p-4">
                            <p class="text-xs font-medium text-slate-400 mb-1">Consumer Name</p>
                            <p class="text-base font-semibold text-slate-800">
                                {{ $billing->consumer->full_name ?? '—' }}
                            </p>
                        </div>

                        {{-- Current Consumption --}}
                        <div class="bg-slate-50 border border-slate-100 rounded-lg p-4">
                            <p class="text-xs font-medium text-slate-400 mb-1">Current Consumption</p>
                            <p class="text-base font-semibold text-slate-800">
                                {{ number_format($billing->consumption ?? 0, 2) }} m³
                            </p>
                        </div>

                        {{-- Previous Balance --}}
                        <div class="bg-slate-50 border border-slate-100 rounded-lg p-4">
                            <p class="text-xs font-medium text-slate-400 mb-1">Previous Balance</p>
                            <p class="text-base font-semibold text-slate-800">
                                ₱{{ number_format($billing->previous_balance ?? 0, 2) }}
                            </p>
                        </div>

                        {{-- Payment Method --}}
                        <div class="bg-slate-50 border border-slate-100 rounded-lg p-4">
                            <p class="text-xs font-medium text-slate-400 mb-2">Payment Method</p>
                            <select id="payment_method" name="payment_method"
                                class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <option value="">Select method…</option>
                                @foreach(\App\Models\Payment::PAYMENT_METHODS as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── PAYMENT INPUT SECTION ── --}}
            <div class="bg-gradient-to-br from-slate-50 to-blue-50/30 border border-slate-100 rounded-xl p-5">
                <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-4">Payment Entry</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Payment Amount --}}
                    <div>
                        <label for="payment_amount" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Payment Amount <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 font-semibold text-sm">₱</span>
                            <input
                                type="number"
                                id="payment_amount"
                                name="amount"
                                step="0.01"
                                min="0"
                                placeholder="0.00"
                                class="w-full pl-8 pr-4 py-2.5 border border-slate-200 rounded-lg text-sm text-slate-800 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            >
                        </div>
                    </div>

                    {{-- Reference Number --}}
                    <div>
                        <label for="reference_number" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Reference / OR Number
                            <span class="text-slate-400 font-normal">(optional)</span>
                        </label>
                        <input
                            type="text"
                            id="reference_number"
                            name="reference_number"
                            placeholder="e.g. OR-2026-00123"
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        >
                    </div>
                </div>

                {{-- Notes --}}
                <div class="mt-4">
                    <label for="notes" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Notes <span class="text-slate-400 font-normal">(optional)</span>
                    </label>
                    <textarea
                        id="notes"
                        name="notes"
                        rows="2"
                        placeholder="Additional payment notes…"
                        class="w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition resize-none"
                    ></textarea>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-center gap-3 mt-5 pt-5 border-t border-slate-200">
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-8 py-2.5 rounded-lg shadow-sm shadow-blue-200 transition-all duration-200 hover:-translate-y-0.5">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Process Payment
                    </button>
                    <button type="reset"
                        class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold px-6 py-2.5 rounded-lg transition-colors duration-200">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Clear
                    </button>
                </div>
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