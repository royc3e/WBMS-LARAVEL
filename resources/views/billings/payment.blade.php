@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <div>
                            <a href="{{ route('billings.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">Billings</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <a href="{{ route('billings.show', $billing) }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-gray-100">Billing #{{ $billing->id }}</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="ml-4 text-sm font-medium text-gray-500 dark:text-gray-400">Process Payment</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h2 class="mt-2 text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                Process Payment
            </h2>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                Payment Details
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                Enter payment information for this billing.
            </p>
        </div>
        
        <div class="px-4 py-5 sm:p-6">
            <!-- Billing Summary -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Consumer</h4>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $billing->consumer->first_name }} {{ $billing->consumer->last_name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $billing->consumer->account_number }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Billing Period</h4>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($billing->billing_month)->format('F Y') }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Billing Amount</h4>
                        <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ number_format($billing->amount, 2) }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Balance Due</h4>
                        <p class="mt-1 text-lg font-semibold {{ $billing->balance > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                            {{ number_format($billing->balance, 2) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Payment Form -->
            <form action="{{ route('billings.payment.store', $billing) }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 gap-6">
                    <!-- Payment Amount -->
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Payment Amount <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">₱</span>
                            </div>
                            <input type="number" name="amount" id="amount" 
                                   class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white" 
                                   placeholder="0.00" 
                                   step="0.01" 
                                   min="0.01" 
                                   max="{{ number_format($billing->balance, 2, '.', '') }}" 
                                   value="{{ number_format($billing->balance, 2, '.', '') }}" 
                                   required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm" id="amount-currency">PHP</span>
                            </div>
                        </div>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Maximum amount: {{ number_format($billing->balance, 2) }}
                        </p>
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Payment Date -->
                    <div>
                        <label for="payment_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Payment Date <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <input type="date" name="payment_date" id="payment_date" 
                                   class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white" 
                                   value="{{ old('payment_date', now()->format('Y-m-d')) }}" 
                                   max="{{ now()->format('Y-m-d') }}" 
                                   required>
                        </div>
                        @error('payment_date')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Payment Method <span class="text-red-500">*</span>
                        </label>
                        <select id="payment_method" name="payment_method" 
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white" 
                                required>
                            <option value="" disabled selected>Select payment method</option>
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="check" {{ old('payment_method') == 'check' ? 'selected' : '' }}>Check</option>
                            <option value="online_transfer" {{ old('payment_method') == 'online_transfer' ? 'selected' : '' }}>Online Transfer</option>
                            <option value="other" {{ old('payment_method') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Reference Number (Conditional) -->
                    <div id="reference_number_field" class="hidden">
                        <label for="reference_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Reference Number
                        </label>
                        <div class="mt-1">
                            <input type="text" name="reference_number" id="reference_number" 
                                   class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white" 
                                   value="{{ old('reference_number') }}" 
                                   maxlength="100">
                        </div>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Enter check number, transaction ID, or reference number.
                        </p>
                        @error('reference_number')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Notes
                        </label>
                        <div class="mt-1">
                            <textarea id="notes" name="notes" rows="3" 
                                      class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('notes') }}</textarea>
                        </div>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Any additional notes about this payment.
                        </p>
                    </div>
                </div>

                <div class="pt-5 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('billings.show', $billing) }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-600 dark:border-gray-600 dark:text-white dark:hover:bg-gray-500">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                            Process Payment
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Show/hide reference number field based on payment method
    document.addEventListener('DOMContentLoaded', function() {
        const paymentMethod = document.getElementById('payment_method');
        const referenceField = document.getElementById('reference_number_field');
        const referenceInput = document.getElementById('reference_number');
        
        function toggleReferenceField() {
            if (paymentMethod.value === 'cash') {
                referenceField.classList.add('hidden');
                referenceInput.removeAttribute('required');
            } else {
                referenceField.classList.remove('hidden');
                referenceInput.setAttribute('required', 'required');
            }
        }
        
        // Initial check
        toggleReferenceField();
        
        // Add event listener
        paymentMethod.addEventListener('change', toggleReferenceField);
        
        // Format amount input
        const amountInput = document.getElementById('amount');
        amountInput.addEventListener('change', function() {
            let value = parseFloat(this.value);
            const max = parseFloat(this.getAttribute('max'));
            
            if (isNaN(value) || value <= 0) {
                this.value = '0.01';
            } else if (value > max) {
                this.value = max.toFixed(2);
            } else {
                this.value = value.toFixed(2);
            }
        });
    });
</script>
@endpush
@endsection
