@extends('layouts.app')

@section('title', 'Consumer Details')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Consumer Details</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Account #{{ $consumer->account_number }}
            </p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('consumers.edit', $consumer) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <x-icon name="pencil" class="h-4 w-4 mr-2" />
                Edit
            </a>
            <a href="{{ route('consumers.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600">
                <x-icon name="arrow-left" class="h-4 w-4 mr-2" />
                Back to List
            </a>
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg dark:bg-gray-800 dark:border-gray-700">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                {{ $consumer->first_name }} {{ $consumer->middle_name ?? '' }} {{ $consumer->last_name }}
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                {{ $consumer->connection_status ? ucfirst($consumer->connection_status) : 'N/A' }} • 
                {{ $consumer->connection_type ? ucfirst($consumer->connection_type) : 'N/A' }} Connection
            </p>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700">
            <dl>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-gray-800">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Account Information</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 dark:text-gray-200">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Account Number</p>
                                <p class="font-medium">{{ $consumer->account_number ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Meter Number</p>
                                <p class="font-medium">{{ $consumer->meter_number ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Connection Date</p>
                                <p class="font-medium">{{ $consumer->connection_date ? \Carbon\Carbon::parse($consumer->connection_date)->format('M d, Y') : 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Status</p>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $consumer->connection_status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                       ($consumer->connection_status === 'inactive' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                                       'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200') }}">
                                    {{ $consumer->connection_status ? ucfirst($consumer->connection_status) : 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-gray-700">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Contact Information</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 dark:text-gray-200">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Email</p>
                                <p class="font-medium">{{ $consumer->email ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Phone</p>
                                <p class="font-medium">{{ $consumer->phone ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-gray-800">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Address</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 dark:text-gray-200">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Address Line 1</p>
                                <p class="font-medium">{{ $consumer->address_line_1 ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Address Line 2</p>
                                <p class="font-medium">{{ $consumer->address_line_2 ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">City</p>
                                <p class="font-medium">{{ $consumer->city ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">State/Province</p>
                                <p class="font-medium">{{ $consumer->state ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Postal Code</p>
                                <p class="font-medium">{{ $consumer->postal_code ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </dd>
                </div>
                @if($consumer->notes)
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-gray-700">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Notes</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 dark:text-gray-200">
                        {{ $consumer->notes }}
                    </dd>
                </div>
                @endif
            </dl>
        </div>
    </div>

    @if(isset($bills) || isset($payments))
    <div class="mt-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @if(isset($bills) && $bills->count() > 0)
            <div class="bg-white shadow overflow-hidden sm:rounded-lg dark:bg-gray-800">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Recent Bills</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Bill #</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                            @foreach($bills->take(5) as $bill)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $bill->bill_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ \Carbon\Carbon::parse($bill->billing_date)->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                                    {{ number_format($bill->amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $bill->status === 'paid' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                           ($bill->status === 'overdue' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 
                                           'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200') }}">
                                        {{ ucfirst($bill->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($bills->count() > 5)
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 dark:bg-gray-800">
                    <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                        View all bills
                    </a>
                </div>
                @endif
            </div>
            @endif

            @if(isset($payments) && $payments->count() > 0)
            <div class="bg-white shadow overflow-hidden sm:rounded-lg dark:bg-gray-800">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Recent Payments</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Payment #</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Method</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                            @foreach($payments->take(5) as $payment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $payment->payment_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                                    {{ number_format($payment->amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ ucfirst($payment->payment_method) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($payments->count() > 5)
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 dark:bg-gray-800">
                    <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                        View all payments
                    </a>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
    @endif

    @if(isset($outstandingBalance) && $outstandingBalance > 0)
    <div class="mt-8 bg-red-50 border-l-4 border-red-400 p-4 dark:bg-red-900/20 dark:border-red-600">
        <div class="flex">
            <div class="flex-shrink-0">
                <x-icon name="exclamation-circle" class="h-5 w-5 text-red-400" />
            </div>
            <div class="ml-3">
                <p class="text-sm text-red-700 dark:text-red-300">
                    <span class="font-medium">Outstanding Balance:</span>
                    <span class="font-bold">₱{{ number_format($outstandingBalance, 2) }}</span>
                    @if(isset($lastBill))
                    <br>
                    <span class="text-xs">Last bill: {{ \Carbon\Carbon::parse($lastBill->billing_date)->format('M d, Y') }} ({{ $lastBill->bill_number }})</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
