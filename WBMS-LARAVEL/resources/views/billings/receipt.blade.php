@extends('layouts.print')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <!-- Receipt Header -->
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-gray-800">PAYMENT RECEIPT</h1>
        <p class="text-gray-600">Water Billing Management System</p>
        <p class="text-sm text-gray-500 mt-1">{{ config('app.name') }}</p>
    </div>

    <!-- Receipt Info -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div>
            <h3 class="text-sm font-medium text-gray-500">Receipt #</h3>
            <p class="text-gray-900">{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</p>
        </div>
        <div>
            <h3 class="text-sm font-medium text-gray-500">Date</h3>
            <p class="text-gray-900">{{ $payment->payment_date->format('F d, Y') }}</p>
        </div>
        <div>
            <h3 class="text-sm font-medium text-gray-500">Payment Method</h3>
            <p class="text-gray-900">{{ $payment->payment_method_name }}</p>
            @if($payment->reference_number)
                <p class="text-sm text-gray-600">Ref: {{ $payment->reference_number }}</p>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        <!-- Billed To -->
        <div class="border border-gray-200 rounded-lg p-4">
            <h3 class="text-sm font-medium text-gray-500 mb-2">BILLED TO</h3>
            <p class="font-medium text-gray-900">{{ $payment->billing->consumer->first_name }} {{ $payment->billing->consumer->last_name }}</p>
            <p class="text-sm text-gray-600">Account #: {{ $payment->billing->consumer->account_number }}</p>
            <p class="text-sm text-gray-600">{{ $payment->billing->consumer->address_line_1 }}</p>
            @if($payment->billing->consumer->address_line_2)
                <p class="text-sm text-gray-600">{{ $payment->billing->consumer->address_line_2 }}</p>
            @endif
            <p class="text-sm text-gray-600">
                {{ $payment->billing->consumer->city }}, {{ $payment->billing->consumer->state }} {{ $payment->billing->consumer->postal_code }}
            </p>
        </div>

        <!-- Billing Period -->
        <div class="border border-gray-200 rounded-lg p-4">
            <h3 class="text-sm font-medium text-gray-500 mb-2">BILLING PERIOD</h3>
            <p class="text-gray-900">{{ \Carbon\Carbon::parse($payment->billing->billing_month)->format('F Y') }}</p>
            
            <h3 class="text-sm font-medium text-gray-500 mt-4 mb-2">BILLING #</h3>
            <p class="text-gray-900">{{ str_pad($payment->billing->id, 6, '0', STR_PAD_LEFT) }}</p>
        </div>
    </div>

    <!-- Payment Details -->
    <div class="border border-gray-200 rounded-lg overflow-hidden mb-8">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        Payment for {{ \Carbon\Carbon::parse($payment->billing->billing_month)->format('F Y') }} Billing
                        @if($payment->notes)
                            <p class="text-xs text-gray-500 mt-1">{{ $payment->notes }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                        {{ number_format($payment->amount, 2) }}
                    </td>
                </tr>
                <tr class="bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        Total Paid
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">
                        {{ number_format($payment->amount, 2) }}
                    </td>
                </tr>
                <tr class="bg-green-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-800">
                        Payment Status
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-800 text-right">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Completed
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Billing Summary -->
    <div class="border border-gray-200 rounded-lg overflow-hidden mb-8">
        <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
            <h3 class="text-sm font-medium text-gray-900">Billing Summary</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Previous Balance</p>
                    <p class="font-medium">{{ number_format($payment->billing->amount, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Payments</p>
                    <p class="font-medium text-green-600">-{{ number_format($payment->billing->payments->sum('amount'), 2) }}</p>
                </div>
                <div class="md:col-span-2 pt-4 mt-4 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <p class="text-lg font-medium">Balance Due</p>
                        <p class="text-lg font-bold {{ $payment->billing->balance > 0 ? 'text-red-600' : 'text-green-600' }}">
                            {{ number_format($payment->billing->balance, 2) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Notes -->
    @if($payment->notes)
        <div class="border border-gray-200 rounded-lg p-4 mb-8">
            <h3 class="text-sm font-medium text-gray-500 mb-2">NOTES</h3>
            <p class="text-sm text-gray-700">{{ $payment->notes }}</p>
        </div>
    @endif

    <!-- Footer -->
    <div class="mt-12 pt-8 border-t border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <h4 class="text-sm font-medium text-gray-500">Received By</h4>
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <p class="text-sm">{{ $payment->receivedBy->name ?? 'System' }}</p>
                    <p class="text-xs text-gray-500">{{ $payment->created_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>
            <div class="md:col-span-2">
                <div class="text-center">
                    <p class="text-sm text-gray-500">Thank you for your payment!</p>
                    <p class="text-xs text-gray-400 mt-2">This is a computer-generated receipt. No signature required.</p>
                    <p class="text-xs text-gray-400 mt-1">Receipt #{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</p>
                    <p class="text-xs text-gray-400">Printed on {{ now()->format('M d, Y h:i A') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-print the receipt when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        window.print();
        
        // Close the print dialog after printing (for some browsers)
        window.onafterprint = function() {
            // Optional: Close the window after printing
            // window.close();
        };
    });
</script>
@endpush

<style>
    @media print {
        @page {
            size: auto;
            margin: 10mm;
        }
        body {
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .no-print {
            display: none !important;
        }
    }
</style>
@endsection
