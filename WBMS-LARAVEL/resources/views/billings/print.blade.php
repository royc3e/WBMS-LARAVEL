@extends('layouts.print')

@section('content')
<div class="max-w-4xl mx-auto p-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Billing Statement</h1>
            <p class="text-sm text-gray-500">Generated on {{ now()->format('F d, Y') }}</p>
        </div>
        <div class="text-right">
            <h2 class="text-lg font-semibold text-gray-700">Statement #{{ str_pad($billing->id, 6, '0', STR_PAD_LEFT) }}</h2>
            <p class="text-sm text-gray-500">Billing Month: {{ \Carbon\Carbon::parse($billing->billing_month)->format('F Y') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="border border-gray-200 rounded-lg p-5">
            <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-3">Account Details</h3>
            <dl class="space-y-2 text-sm text-gray-700">
                <div>
                    <dt class="font-medium text-gray-500">Account Number</dt>
                    <dd class="text-gray-900">{{ $billing->consumer->account_number }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Consumer Name</dt>
                    <dd class="text-gray-900">{{ $billing->consumer->first_name }} {{ $billing->consumer->last_name }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Address</dt>
                    <dd class="text-gray-900">
                        {{ $billing->consumer->address_line_1 }}<br>
                        @if($billing->consumer->address_line_2)
                            {{ $billing->consumer->address_line_2 }}<br>
                        @endif
                        {{ $billing->consumer->city }}, {{ $billing->consumer->state }} {{ $billing->consumer->postal_code }}
                    </dd>
                </div>
            </dl>
        </div>
        <div class="border border-gray-200 rounded-lg p-5">
            <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-3">Billing Summary</h3>
            <dl class="space-y-2 text-sm text-gray-700">
                <div class="flex justify-between">
                    <dt class="font-medium text-gray-500">Billing Date</dt>
                    <dd class="text-gray-900">{{ $billing->created_at->format('M d, Y') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="font-medium text-gray-500">Due Date</dt>
                    <dd class="text-gray-900">{{ \Carbon\Carbon::parse($billing->due_date)->format('M d, Y') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="font-medium text-gray-500">Status</dt>
                    <dd class="text-gray-900 capitalize">{{ $billing->status }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="border border-gray-200 rounded-lg overflow-hidden mb-8">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-700">
                <tr>
                    <td class="px-6 py-4">Water consumption ({{ number_format($billing->consumption, 2) }} units)</td>
                    <td class="px-6 py-4 text-right">{{ number_format($billing->amount, 2) }}</td>
                </tr>
                @if($billing->payments->count() > 0)
                    <tr class="bg-green-50">
                        <td class="px-6 py-4 font-medium text-green-800">Payments Received</td>
                        <td class="px-6 py-4 text-right font-medium text-green-800">-{{ number_format($billing->payments->sum('amount'), 2) }}</td>
                    </tr>
                @endif
                <tr class="bg-gray-100 font-semibold">
                    <td class="px-6 py-4">Balance Due</td>
                    <td class="px-6 py-4 text-right {{ $billing->balance > 0 ? 'text-red-600' : 'text-gray-900' }}">{{ number_format($billing->balance, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="border border-gray-200 rounded-lg p-5 mb-8">
        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-3">Meter Readings</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-700">
            <div>
                <p class="font-medium text-gray-500">Previous Reading</p>
                <p class="text-gray-900">{{ number_format($billing->previous_reading, 2) }} units</p>
            </div>
            <div>
                <p class="font-medium text-gray-500">Current Reading</p>
                <p class="text-gray-900">{{ number_format($billing->current_reading, 2) }} units</p>
            </div>
            <div>
                <p class="font-medium text-gray-500">Consumption</p>
                <p class="text-gray-900">{{ number_format($billing->consumption, 2) }} units</p>
            </div>
        </div>
    </div>

    @if($billing->notes)
        <div class="border border-gray-200 rounded-lg p-5 mb-8">
            <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">Notes</h3>
            <p class="text-sm text-gray-700 whitespace-pre-line">{{ $billing->notes }}</p>
        </div>
    @endif

    <div class="text-center text-xs text-gray-500 mt-12">
        <p>{{ config('app.name') }} &middot; Generated {{ now()->format('M d, Y h:i A') }}</p>
        <p>This document is system generated and does not require a signature.</p>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        window.print();
    });
</script>
@endpush
@endsection
