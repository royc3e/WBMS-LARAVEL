@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6" x-data="meterReadingForm()">

        <!-- Page Header -->
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
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Meter Reading Entry</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Record new water meter reading for a consumer
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

        <form action="{{ route('meter-readings.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Search Consumer Section -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-cyan-500 to-blue-600">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Select Consumer
                    </h2>
                </div>
                <div class="p-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Search by Consumer ID or Meter Number
                    </label>
                    <select name="consumer_id" x-model="consumerId" @change="fetchConsumerDetails()" required
                        class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent dark:bg-gray-700 dark:text-white text-base">
                        <option value="">Choose a consumer...</option>
                        @foreach($consumers as $consumer)
                            <option value="{{ $consumer->id }}">
                                {{ $consumer->account_number }} - {{ $consumer->first_name }} {{ $consumer->last_name }} (Meter:
                                {{ $consumer->meter_number }})
                            </option>
                        @endforeach
                    </select>
                    @error('consumer_id')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Consumer Details Section -->
            <div x-show="consumerData" x-transition class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Consumer Information</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Consumer Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                Consumer Name
                            </label>
                            <p class="text-base font-semibold text-gray-900 dark:text-white"
                                x-text="consumerData?.consumer?.first_name + ' ' + consumerData?.consumer?.last_name">-</p>
                        </div>

                        <!-- Account Number -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                Account Number
                            </label>
                            <p class="text-base font-semibold text-gray-900 dark:text-white"
                                x-text="consumerData?.consumer?.account_number">-</p>
                        </div>

                        <!-- Meter Number -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                Meter Number
                            </label>
                            <p class="text-base font-medium text-gray-900 dark:text-white"
                                x-text="consumerData?.meter_number">-</p>
                        </div>

                        <!-- Connection Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                Connection Type
                            </label>
                            <p class="text-base font-medium text-gray-900 dark:text-white capitalize"
                                x-text="consumerData?.consumer?.connection_type">-</p>
                        </div>

                        <!-- Address -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                Address
                            </label>
                            <p class="text-base text-gray-900 dark:text-white"
                                x-text="consumerData?.consumer?.address_line_1 + ', ' + consumerData?.consumer?.city">-</p>
                        </div>

                        <!-- Previous Reading -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                Previous Reading
                            </label>
                            <div class="inline-flex items-center px-4 py-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                                <svg class="h-5 w-5 text-blue-600 dark:text-blue-400 mr-2" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                                <span class="text-lg font-bold text-blue-700 dark:text-blue-300"
                                    x-text="consumerData?.previous_reading?.toFixed(2) + ' m³'">0.00 m³</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reading Entry Section -->
            <div x-show="consumerData" x-transition class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Reading Entry</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Current Reading -->
                        <div>
                            <label for="current_reading"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Current Reading (m³) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="current_reading" id="current_reading" step="0.01" min="0" required
                                x-model="currentReading" @input="calculateConsumption()"
                                value="{{ old('current_reading') }}"
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent dark:bg-gray-700 dark:text-white text-base"
                                placeholder="0.00">
                            @error('current_reading')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Reading Date -->
                        <div>
                            <label for="reading_date"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Reading Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="reading_date" id="reading_date" required
                                value="{{ old('reading_date', date('Y-m-d')) }}"
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent dark:bg-gray-700 dark:text-white text-base">
                            @error('reading_date')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Calculated Consumption -->
                        <div class="md:col-span-2" x-show="consumption > 0">
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">
                                Calculated Consumption
                            </label>
                            <div
                                class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-cyan-500 to-blue-600 rounded-lg">
                                <svg class="h-6 w-6 text-white mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                                <span class="text-xl font-bold text-white" x-text="consumption.toFixed(2) + ' m³'">0.00
                                    m³</span>
                            </div>
                        </div>

                        <!-- Notes/Remarks -->
                        <div class="md:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Notes/Remarks
                            </label>
                            <textarea name="notes" id="notes" rows="3"
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                placeholder="Optional notes or remarks about this reading...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div x-show="consumerData" x-transition class="flex items-center justify-end space-x-4">
                    <button type="button" @click="resetForm()"
                        class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-base font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                        Clear Form
                    </button>
                    <a href="{{ route('meter-readings.index') }}"
                        class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-base font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-3 border border-transparent rounded-lg text-base font-medium text-white bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 transition-colors shadow-sm">
                        <span class="flex items-center">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Submit Reading
                        </span>
                    </button>
                </div>
        </form>
    </div>

    <script>
        function meterReadingForm() {
            return {
                consumerId: '',
                consumerData: null,
                currentReading: '',
                consumption: 0,
                excessConsumption: 0,
                ratePerUnit: 0,
                excessCharge: 0,
                totalAmount: 0,

                async fetchConsumerDetails() {
                    if (!this.consumerId) {
                        this.consumerData = null;
                        return;
                    }

                    try {
                        const response = await fetch(`/meter-readings/consumer/${this.consumerId}`);
                        this.consumerData = await response.json();
                        this.calculateConsumption();
                    } catch (error) {
                        console.error('Error fetching consumer details:', error);
                    }
                },

                calculateConsumption() {
                    const previous = parseFloat(this.consumerData?.previous_reading || 0);
                    const current = parseFloat(this.currentReading || 0);
                    this.consumption = Math.max(current - previous, 0);

                    this.calculateBilling();
                },

                calculateBilling() {
                    const MINIMUM_CHARGE = 200;
                    const MINIMUM_CONSUMPTION = 10;
                    const RESIDENTIAL_RATE = 15;
                    const COMMERCIAL_RATE = 20;

                    // Determine rate based on connection type
                    const connectionType = this.consumerData?.consumer?.connection_type?.toLowerCase();
                    this.ratePerUnit = connectionType === 'commercial' ? COMMERCIAL_RATE : RESIDENTIAL_RATE;

                    // Calculate excess consumption
                    this.excessConsumption = Math.max(this.consumption - MINIMUM_CONSUMPTION, 0);

                    // Calculate excess charge
                    this.excessCharge = this.excessConsumption * this.ratePerUnit;

                    // Calculate total amount
                    if (this.consumption <= MINIMUM_CONSUMPTION) {
                        this.totalAmount = MINIMUM_CHARGE;
                    } else {
                        this.totalAmount = MINIMUM_CHARGE + this.excessCharge;
                    }
                },

                resetForm() {
                    this.currentReading = '';
                    this.consumption = 0;
                    this.excessConsumption = 0;
                    this.excessCharge = 0;
                    this.totalAmount = 0;
                    document.getElementById('notes').value = '';
                }
            }
        }
    </script>
@endsection