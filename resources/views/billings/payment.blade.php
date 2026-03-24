@extends('layouts.app')

@section('title', 'Process Payment')

@section('content')
<div class="space-y-6">

    {{-- ============================================================ --}}
    {{-- BREADCRUMB + PAGE TITLE                                       --}}
    {{-- ============================================================ --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-1.5 text-sm text-slate-400 mb-1">
                <a href="{{ route('billings.index') }}" class="hover:text-blue-600 transition-colors font-medium">Billing</a>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <a href="{{ route('billings.show', $billing) }}" class="hover:text-blue-600 transition-colors">Bill #{{ $billing->id }}</a>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-slate-600 font-medium">Process Payment</span>
            </nav>
            <h1 class="text-2xl font-bold text-slate-800">Process Payment</h1>
            <p class="text-sm text-slate-400 mt-0.5">Enter and process consumer payment</p>
        </div>
        <a href="{{ route('billings.index') }}"
            class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-slate-700 bg-white border border-slate-200 rounded-lg px-4 py-2 shadow-sm hover:shadow transition-all">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Billing
        </a>
    </div>

    {{-- ============================================================ --}}
    {{-- SESSION ALERTS                                                --}}
    {{-- ============================================================ --}}
    @if(session('error'))
        <div class="flex items-start gap-3 rounded-xl bg-red-50 border border-red-100 p-4">
            <svg class="h-5 w-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm font-medium text-red-700">{{ session('error') }}</p>
        </div>
    @endif

    {{-- ============================================================ --}}
    {{-- MAIN PROCESS PAYMENT CARD                                     --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-100">

        {{-- Card Header --}}
        <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-100">
            <div class="h-9 w-9 rounded-lg bg-blue-600 flex items-center justify-center flex-shrink-0">
                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-800">Process Payment</h2>
                <p class="text-xs text-slate-400">Review billing details and enter payment information</p>
            </div>
            {{-- Status badge --}}
            <div class="ml-auto">
                @php
                    $statusColors = [
                        'pending'   => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                        'overdue'   => 'bg-red-50 text-red-700 border-red-200',
                        'paid'      => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'cancelled' => 'bg-slate-100 text-slate-500 border-slate-200',
                    ];
                    $sc = $statusColors[$billing->status] ?? $statusColors['cancelled'];
                @endphp
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1 rounded-full border {{ $sc }}">
                    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                    {{ ucfirst($billing->status) }}
                </span>
            </div>
        </div>

        <div class="p-6 space-y-6">

            {{-- ── BILLING & CONSUMER DETAIL GRID ── --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- LEFT: Billing Information --}}
                <div class="space-y-3">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 flex items-center gap-2">
                        <span class="inline-block w-4 h-px bg-slate-200"></span>
                        Billing Information
                        <span class="flex-1 h-px bg-slate-100"></span>
                    </p>

                    {{-- Account Number --}}
                    <div class="flex items-start justify-between bg-slate-50 border border-slate-100 rounded-lg px-4 py-3">
                        <div>
                            <p class="text-xs font-medium text-slate-400">Account Number</p>
                            <p class="text-sm font-semibold text-slate-800 mt-0.5">
                                {{ $billing->consumer->account_number ?? '—' }}
                            </p>
                        </div>
                        <div class="h-7 w-7 rounded-md bg-slate-200 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                            </svg>
                        </div>
                    </div>

                    {{-- Billing Period --}}
                    <div class="flex items-start justify-between bg-slate-50 border border-slate-100 rounded-lg px-4 py-3">
                        <div>
                            <p class="text-xs font-medium text-slate-400">Billing Period</p>
                            <p class="text-sm font-semibold text-slate-800 mt-0.5">
                                {{ \Carbon\Carbon::parse($billing->billing_month)->format('F Y') }}
                            </p>
                        </div>
                        <div class="h-7 w-7 rounded-md bg-slate-200 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>

                    {{-- Current Amount Due --}}
                    <div class="bg-blue-50 border border-blue-100 rounded-lg px-4 py-3">
                        <p class="text-xs font-medium text-blue-500">Current Amount Due</p>
                        <p class="text-xl font-extrabold text-blue-700 mt-0.5">
                            ₱{{ number_format($billing->amount, 2) }}
                        </p>
                    </div>

                    {{-- Total Amount Due (with previous balance) --}}
                    <div class="bg-amber-50 border border-amber-100 rounded-lg px-4 py-3">
                        <p class="text-xs font-medium text-amber-600">Total Amount Due</p>
                        <p class="text-xl font-extrabold text-amber-700 mt-0.5">
                            ₱{{ number_format($billing->balance, 2) }}
                        </p>
                        <p class="text-[10px] text-amber-500 mt-0.5">Includes previous balance of ₱{{ number_format($billing->previous_balance ?? 0, 2) }}</p>
                    </div>
                </div>

                {{-- RIGHT: Consumer & Payment Details --}}
                <div class="space-y-3">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 flex items-center gap-2">
                        <span class="inline-block w-4 h-px bg-slate-200"></span>
                        Consumer Details
                        <span class="flex-1 h-px bg-slate-100"></span>
                    </p>

                    {{-- Consumer Name --}}
                    <div class="flex items-start gap-3 bg-slate-50 border border-slate-100 rounded-lg px-4 py-3">
                        <div class="h-9 w-9 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                            {{ strtoupper(substr($billing->consumer->first_name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-xs font-medium text-slate-400">Consumer Name</p>
                            <p class="text-sm font-semibold text-slate-800 mt-0.5">
                                {{ $billing->consumer->first_name }} {{ $billing->consumer->last_name }}
                            </p>
                            <p class="text-xs text-slate-400">{{ ucfirst($billing->consumer->connection_type ?? 'residential') }}</p>
                        </div>
                    </div>

                    {{-- Current Consumption --}}
                    <div class="flex items-start justify-between bg-slate-50 border border-slate-100 rounded-lg px-4 py-3">
                        <div>
                            <p class="text-xs font-medium text-slate-400">Current Consumption</p>
                            <p class="text-sm font-semibold text-slate-800 mt-0.5">
                                {{ number_format($billing->consumption, 2) }} m³
                            </p>
                        </div>
                        <div class="h-7 w-7 rounded-md bg-blue-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="h-3.5 w-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                    </div>

                    {{-- Previous Balance --}}
                    <div class="flex items-start justify-between bg-slate-50 border border-slate-100 rounded-lg px-4 py-3">
                        <div>
                            <p class="text-xs font-medium text-slate-400">Previous Balance</p>
                            <p class="text-sm font-semibold {{ ($billing->previous_balance ?? 0) > 0 ? 'text-red-600' : 'text-slate-800' }} mt-0.5">
                                ₱{{ number_format($billing->previous_balance ?? 0, 2) }}
                            </p>
                        </div>
                        <div class="h-7 w-7 rounded-md bg-slate-200 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>

                    {{-- Due Date --}}
                    <div class="flex items-start justify-between bg-slate-50 border border-slate-100 rounded-lg px-4 py-3">
                        <div>
                            <p class="text-xs font-medium text-slate-400">Due Date</p>
                            <p class="text-sm font-semibold {{ \Carbon\Carbon::parse($billing->due_date)->isPast() && $billing->status !== 'paid' ? 'text-red-600' : 'text-slate-800' }} mt-0.5">
                                {{ \Carbon\Carbon::parse($billing->due_date)->format('F d, Y') }}
                                @if(\Carbon\Carbon::parse($billing->due_date)->isPast() && $billing->status !== 'paid')
                                    <span class="ml-1 text-xs font-bold text-red-500">(Overdue)</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── PAYMENT FORM ── --}}
            <form action="{{ route('billings.payment.store', $billing) }}" method="POST" id="paymentForm">
                @csrf

                {{-- Payment Entry Section --}}
                <div class="bg-gradient-to-br from-slate-50 to-blue-50/30 border border-slate-100 rounded-xl p-5 space-y-5">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">Payment Entry</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        {{-- Payment Amount --}}
                        <div>
                            <label for="amount" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Payment Amount <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 font-semibold text-sm pointer-events-none">₱</span>
                                <input
                                    type="number"
                                    id="amount"
                                    name="amount"
                                    step="0.01"
                                    min="0.01"
                                    max="{{ number_format($billing->balance, 2, '.', '') }}"
                                    value="{{ old('amount', number_format($billing->balance, 2, '.', '')) }}"
                                    required
                                    class="w-full pl-8 pr-4 py-2.5 border border-slate-200 rounded-lg text-sm text-slate-800 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('amount') border-red-300 @enderror"
                                >
                            </div>
                            <p class="text-xs text-slate-400 mt-1">Max: ₱{{ number_format($billing->balance, 2) }}</p>
                            @error('amount')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Payment Date --}}
                        <div>
                            <label for="payment_date" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Payment Date <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="date"
                                id="payment_date"
                                name="payment_date"
                                value="{{ old('payment_date', now()->format('Y-m-d')) }}"
                                max="{{ now()->format('Y-m-d') }}"
                                required
                                class="w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('payment_date') border-red-300 @enderror"
                            >
                            @error('payment_date')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Payment Method --}}
                        <div>
                            <label for="payment_method" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Payment Method <span class="text-red-500">*</span>
                            </label>
                            <select
                                id="payment_method"
                                name="payment_method"
                                required
                                class="w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('payment_method') border-red-300 @enderror"
                            >
                                <option value="" disabled selected>Select method…</option>
                                <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>💵 Cash</option>
                                <option value="check" {{ old('payment_method') === 'check' ? 'selected' : '' }}>🏦 Check</option>
                                <option value="online_transfer" {{ old('payment_method') === 'online_transfer' ? 'selected' : '' }}>📱 GCash / Online Transfer</option>
                                <option value="other" {{ old('payment_method') === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('payment_method')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Reference Number --}}
                        <div id="reference_number_field">
                            <label for="reference_number" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Reference / OR Number
                                <span class="text-slate-400 font-normal text-xs" id="ref_hint">(optional for cash)</span>
                            </label>
                            <input
                                type="text"
                                id="reference_number"
                                name="reference_number"
                                value="{{ old('reference_number') }}"
                                maxlength="100"
                                placeholder="e.g. OR-2026-00123"
                                class="w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            >
                            @error('reference_number')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label for="notes" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Notes <span class="text-slate-400 font-normal text-xs">(optional)</span>
                        </label>
                        <textarea
                            id="notes"
                            name="notes"
                            rows="2"
                            placeholder="Any additional payment notes…"
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition resize-none"
                        >{{ old('notes') }}</textarea>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex items-center justify-center gap-3 pt-4 border-t border-slate-200">
                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-8 py-2.5 rounded-lg shadow-sm shadow-blue-200 transition-all duration-200 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Process Payment
                        </button>
                        <a href="{{ route('billings.show', $billing) }}"
                            class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold px-6 py-2.5 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-slate-300">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Cancel
                        </a>
                    </div>
                </div>
            </form>

        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- PREVIOUS PAYMENTS FOR THIS BILLING (if any)                  --}}
    {{-- ============================================================ --}}
    @if($billing->payments->count() > 0)
    <div class="bg-white rounded-xl border border-slate-100 shadow-sm">
        <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-700">Previous Payments for this Bill</h3>
            <span class="text-xs text-slate-400">({{ $billing->payments->count() }} record{{ $billing->payments->count() > 1 ? 's' : '' }})</span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-400">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-400">Reference #</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-400">Method</th>
                        <th class="px-6 py-3 text-right text-xs font-bold uppercase tracking-wider text-slate-400">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-400">Received By</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($billing->payments as $payment)
                    <tr class="hover:bg-blue-50/20 transition-colors">
                        <td class="px-6 py-3 text-sm text-slate-600">{{ $payment->payment_date->format('M d, Y') }}</td>
                        <td class="px-6 py-3 text-sm font-semibold text-blue-600">{{ $payment->reference_number ?? '—' }}</td>
                        <td class="px-6 py-3">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700">
                                {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-sm font-bold text-emerald-600 text-right">₱{{ number_format($payment->amount, 2) }}</td>
                        <td class="px-6 py-3 text-sm text-slate-500">{{ $payment->receivedBy->name ?? 'System' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const methodSelect = document.getElementById('payment_method');
        const refInput     = document.getElementById('reference_number');
        const refHint      = document.getElementById('ref_hint');

        function updateRefField() {
            const method = methodSelect.value;
            if (method === 'cash') {
                refInput.removeAttribute('required');
                refHint.textContent = '(optional for cash)';
            } else if (method !== '') {
                refInput.setAttribute('required', 'required');
                refHint.textContent = '(required)';
            } else {
                refInput.removeAttribute('required');
                refHint.textContent = '(optional for cash)';
            }
        }

        methodSelect.addEventListener('change', updateRefField);
        updateRefField();

        // Auto-clamp amount to max balance
        const amountInput = document.getElementById('amount');
        amountInput.addEventListener('blur', function () {
            const max = parseFloat(this.getAttribute('max'));
            let val = parseFloat(this.value);
            if (isNaN(val) || val <= 0) this.value = '0.01';
            else if (val > max) this.value = max.toFixed(2);
            else this.value = val.toFixed(2);
        });
    });
</script>
@endpush

@endsection
