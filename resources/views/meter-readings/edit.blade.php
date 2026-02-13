@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center">
                <a href="{{ route('meter-readings.index') }}"
                    class="mr-4 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Meter Reading</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Update meter reading information
                    </p>
                </div>
            </div>
        </div>

        @if(session('error'))
            <div class="mb-6 rounded-lg bg-red-50 dark:bg-red-900/20 p-4 border-l-4 border-red-500">
                <div class="flex">
                    <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z"
                            clip-rule="evenodd" />
                    </svg>
                    <p class="ml-3 text-sm font-medium text-red-800 dark:text-red-200">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <form action="{{ route('meter-readings.update', $meterReading) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Consumer Info (Read-only) -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Consumer Information</h2>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
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
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Previous
                            Reading</label>
                        <p class="text-base font-bold text-blue-600 dark:text-blue-400">
                            {{ number_format($meterReading->previous_reading, 2) }} m³</p>
                    </div>
                </div>
            </div>

            <!-- Editable Fields -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Update Reading</h2>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="current_reading"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Current Reading (m³) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="current_reading" id="current_reading" step="0.01"
                                min="{{ $meterReading->previous_reading }}" required
                                value="{{ old('current_reading', $meterReading->current_reading) }}"
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                            @error('current_reading')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="reading_date"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Reading Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="reading_date" id="reading_date" required
                                value="{{ old('reading_date', $meterReading->reading_date->format('Y-m-d')) }}"
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                            @error('reading_date')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Notes/Remarks
                            </label>
                            <textarea name="notes" id="notes" rows="3"
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                placeholder="Optional notes or remarks...">{{ old('notes', $meterReading->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between">
                <form action="{{ route('meter-readings.destroy', $meterReading) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this meter reading?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                        Delete Reading
                    </button>
                </form>

                <div class="flex space-x-4">
                    <a href="{{ route('meter-readings.show', $meterReading) }}"
                        class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-cyan-600 hover:bg-cyan-700 text-white font-medium rounded-lg transition-colors">
                        Update Reading
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection