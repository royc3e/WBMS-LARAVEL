@extends('layouts.app')

@section('title', 'Edit Billing #' . str_pad($billing->id, 6, '0', STR_PAD_LEFT))

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- ============================================================ --}}
    {{-- BREADCRUMB + PAGE HEADER                                      --}}
    {{-- ============================================================ --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <nav class="flex items-center gap-1.5 text-sm text-slate-400 mb-1">
                <a href="{{ route('billings.index') }}" class="text-slate-600 font-medium hover:text-blue-600 transition">Billing</a>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <a href="{{ route('billings.show', $billing) }}" class="text-slate-600 font-medium hover:text-blue-600 transition">Bill #{{ str_pad($billing->id, 6, '0', STR_PAD_LEFT) }}</a>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-slate-500">Edit</span>
            </nav>
            <h1 class="text-2xl font-bold text-slate-800">Edit Billing Record</h1>
            <p class="text-sm text-slate-400 mt-0.5">Modify the billing details below. Changes will be saved immediately.</p>
        </div>
        <a href="{{ route('billings.show', $billing) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 text-sm font-semibold rounded-xl border border-slate-200 shadow-sm transition-all duration-200">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Billing
        </a>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 shadow-sm">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 h-8 w-8 rounded-lg bg-red-100 flex items-center justify-center mt-0.5">
                    <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-red-800 mb-1">Please fix the following errors:</p>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li class="text-sm text-red-700">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- ============================================================ --}}
    {{-- EDIT FORM                                                      --}}
    {{-- ============================================================ --}}
    <form action="{{ route('billings.update', $billing) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Consumer + Period --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Consumer & Billing Period</h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Consumer --}}
                <div class="md:col-span-2">
                    <label for="consumer_id" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Consumer <span class="text-red-500">*</span>
                    </label>
                    <select name="consumer_id" id="consumer_id" required
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-700
                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors
                                   @error('consumer_id') border-red-400 bg-red-50 @enderror">
                        <option value="">Select consumer...</option>
                        @foreach($consumers as $consumer)
                            <option value="{{ $consumer->id }}"
                                {{ old('consumer_id', $billing->consumer_id) == $consumer->id ? 'selected' : '' }}>
                                {{ $consumer->account_number }} — {{ $consumer->first_name }} {{ $consumer->last_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('consumer_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Billing Month --}}
                <div>
                    <label for="billing_month" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Billing Month <span class="text-red-500">*</span>
                    </label>
                    <input type="month" name="billing_month" id="billing_month" required
                           value="{{ old('billing_month', \Carbon\Carbon::parse($billing->billing_month)->format('Y-m')) }}"
                           class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-700
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors
                                  @error('billing_month') border-red-400 bg-red-50 @enderror">
                    @error('billing_month')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Due Date --}}
                <div>
                    <label for="due_date" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Due Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="due_date" id="due_date" required
                           value="{{ old('due_date', $billing->due_date) }}"
                           class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-700
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors
                                  @error('due_date') border-red-400 bg-red-50 @enderror">
                    @error('due_date')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>

        {{-- Meter Readings --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Meter Readings</h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Previous Reading --}}
                <div>
                    <label for="previous_reading" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Previous Reading (m³) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="previous_reading" id="previous_reading" required min="0" step="0.01"
                           value="{{ old('previous_reading', $billing->previous_reading) }}"
                           oninput="calcConsumption()"
                           class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-700
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors
                                  @error('previous_reading') border-red-400 bg-red-50 @enderror">
                    @error('previous_reading')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Current Reading --}}
                <div>
                    <label for="current_reading" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Current Reading (m³) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="current_reading" id="current_reading" required min="0" step="0.01"
                           value="{{ old('current_reading', $billing->current_reading) }}"
                           oninput="calcConsumption()"
                           class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-700
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors
                                  @error('current_reading') border-red-400 bg-red-50 @enderror">
                    @error('current_reading')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Consumption (auto-calculated, read-only) --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Consumption (m³)</label>
                    <div id="consumption_display"
                         class="w-full rounded-xl border border-slate-100 bg-slate-100 px-4 py-2.5 text-sm font-bold text-slate-700">
                        {{ number_format($billing->consumption, 2) }}
                    </div>
                    <p class="mt-1 text-xs text-slate-400">Auto-calculated from readings</p>
                </div>

            </div>
        </div>

        {{-- Financial Details --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Financial Details</h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Amount --}}
                <div>
                    <label for="amount" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Total Amount (₱) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 font-semibold text-sm">₱</span>
                        <input type="number" name="amount" id="amount" required min="0" step="0.01"
                               value="{{ old('amount', $billing->amount) }}"
                               class="w-full rounded-xl border border-slate-200 bg-slate-50 pl-8 pr-4 py-2.5 text-sm text-slate-700
                                      focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors
                                      @error('amount') border-red-400 bg-red-50 @enderror">
                    </div>
                    @error('amount')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label for="status" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" id="status" required
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-700
                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors
                                   @error('status') border-red-400 bg-red-50 @enderror">
                        @foreach(['pending' => 'Pending', 'paid' => 'Paid', 'overdue' => 'Overdue', 'cancelled' => 'Cancelled'] as $value => $label)
                            <option value="{{ $value }}" {{ old('status', $billing->status) === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Previous Balance (read-only info) --}}
                @if($billing->previous_balance > 0)
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Previous Balance</label>
                    <div class="w-full rounded-xl border border-amber-100 bg-amber-50 px-4 py-2.5 text-sm font-semibold text-amber-700">
                        ₱{{ number_format($billing->previous_balance, 2) }}
                    </div>
                    <p class="mt-1 text-xs text-slate-400">Carried over from prior unpaid bills</p>
                </div>
                @endif

                {{-- Penalty (read-only info) --}}
                @if($billing->penalty > 0)
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Penalty</label>
                    <div class="w-full rounded-xl border border-red-100 bg-red-50 px-4 py-2.5 text-sm font-semibold text-red-700">
                        ₱{{ number_format($billing->penalty, 2) }}
                    </div>
                    <p class="mt-1 text-xs text-slate-400">Late payment penalty applied</p>
                </div>
                @endif

                {{-- Arrears --}}
                <div>
                    <label for="arrears" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Arrears (₱)
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 font-semibold text-sm">₱</span>
                        <input type="number" name="arrears" id="arrears" min="0" step="0.01"
                               value="{{ old('arrears', $billing->arrears ?? 0) }}"
                               class="w-full rounded-xl border border-slate-200 bg-slate-50 pl-8 pr-4 py-2.5 text-sm text-slate-700
                                      focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors
                                      @error('arrears') border-red-400 bg-red-50 @enderror">
                    </div>
                    <p class="mt-1 text-xs text-slate-400">Past due amounts not included in previous balance</p>
                    @error('arrears')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>

        {{-- Notes --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Notes</h2>
            </div>
            <div class="p-6">
                <textarea name="notes" id="notes" rows="3"
                          placeholder="Optional notes about this billing record..."
                          class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-700
                                 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('notes', $billing->notes) }}</textarea>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center justify-end gap-3 pb-4">
            <a href="{{ route('billings.show', $billing) }}"
               class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-white hover:bg-slate-50 rounded-xl border border-slate-200 transition-colors">
                Cancel
            </a>
            <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-sm transition-all duration-200 hover:-translate-y-0.5">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Save Changes
            </button>
        </div>

    </form>
</div>

@push('scripts')
<script>
    function calcConsumption() {
        const prev = parseFloat(document.getElementById('previous_reading').value) || 0;
        const curr = parseFloat(document.getElementById('current_reading').value) || 0;
        const consumption = Math.max(curr - prev, 0);
        document.getElementById('consumption_display').textContent = consumption.toFixed(2);
    }
</script>
@endpush
@endsection
