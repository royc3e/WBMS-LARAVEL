@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('meter-readings.index') }}"
                        class="mr-4 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Meter Reading Details</h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            View meter reading information
                        </p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('meter-readings.edit', $meterReading) }}"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-cyan-500 to-blue-600">
                <h2 class="text-lg font-semibold text-white">Reading Information</h2>
            </div>

            <div class="p-6 space-y-6">
                <!-- Consumer Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Consumer Name</label>
                        <p class="text-base font-semibold text-gray-900 dark:text-white">
                            {{ $meterReading->consumer->first_name }} {{ $meterReading->consumer->last_name }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Account
                            Number</label>
                        <p class="text-base font-semibold text-gray-900 dark:text-white">
                            {{ $meterReading->consumer->account_number }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Meter Number</label>
                        <p class="text-base text-gray-900 dark:text-white">{{ $meterReading->meter_number }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Reading Date</label>
                        <p class="text-base text-gray-900 dark:text-white">
                            {{ \Carbon\Carbon::parse($meterReading->reading_date)->format('F d, Y') }}</p>
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-blue-700 dark:text-blue-300 mb-2">Previous
                                Reading</label>
                            <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">
                                {{ number_format($meterReading->previous_reading, 2) }} m³</p>
                        </div>
                        <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-green-700 dark:text-green-300 mb-2">Current
                                Reading</label>
                            <p class="text-2xl font-bold text-green-900 dark:text-green-100">
                                {{ number_format($meterReading->current_reading, 2) }} m³</p>
                        </div>
                        <div class="bg-gradient-to-r from-cyan-500 to-blue-600 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-white mb-2">Consumption</label>
                            <p class="text-2xl font-bold text-white">{{ number_format($meterReading->consumption, 2) }} m³
                            </p>
                        </div>
                    </div>
                </div>

                @if($meterReading->notes)
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Notes/Remarks</label>
                        <p class="text-base text-gray-900 dark:text-white">{{ $meterReading->notes }}</p>
                    </div>
                @endif

                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Recorded By</label>
                    <p class="text-base text-gray-900 dark:text-white">{{ $meterReading->recordedBy->name }} on
                        {{ $meterReading->created_at->format('F d, Y \a\t h:i A') }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection