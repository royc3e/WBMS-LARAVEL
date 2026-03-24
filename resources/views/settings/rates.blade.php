@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-3">
                <a href="{{ route('settings.index') }}"
                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Water Rate Settings</h1>
            </div>
            <p class="mt-2 ml-9 text-sm text-gray-600 dark:text-gray-400">
                Configure billing rates and water consumption charges
            </p>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-green-600 dark:text-green-400 mr-3" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm font-medium text-green-800 dark:text-green-300">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-red-600 dark:text-red-400 mr-3" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm font-medium text-red-800 dark:text-red-300">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Billing Rule Explanation -->
        <div class="mb-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300">Billing Rule Logic</h3>
                    <div class="mt-2 text-sm text-blue-700 dark:text-blue-400">
                        <p class="font-semibold mb-1">If consumption ≤ Minimum Consumption:</p>
                        <p class="ml-4 mb-3">→ Charge = Minimum Rate (₱{{ $rates['minimum_rate']->value ?? 200 }})</p>

                        <p class="font-semibold mb-1">If consumption > Minimum Consumption:</p>
                        <p class="ml-4 mb-1">→ Residential: ₱{{ $rates['minimum_rate']->value ?? 200 }} + (Excess ×
                            ₱{{ $rates['residential_excess_rate']->value ?? 15 }})</p>
                        <p class="ml-4 mb-1">→ Commercial: ₱{{ $rates['minimum_rate']->value ?? 200 }} + (Excess ×
                            ₱{{ $rates['commercial_excess_rate']->value ?? 20 }})</p>
                        <p class="ml-4 mb-1">→ Industrial: ₱{{ $rates['minimum_rate']->value ?? 200 }} + (Excess ×
                            ₱{{ $rates['industrial_excess_rate']->value ?? 25 }})</p>
                        <p class="ml-4">→ Government: ₱{{ $rates['minimum_rate']->value ?? 200 }} + (Excess ×
                            ₱{{ $rates['government_excess_rate']->value ?? 12 }})</p>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('settings.rates.update') }}" method="POST">
            @csrf

            <!-- Base Rate Settings -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-cyan-600 dark:text-cyan-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Base Rate Configuration
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="minimum_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Minimum Rate (₱)
                        </label>
                        <div class="relative">
                            <span
                                class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400">₱</span>
                            <input type="number" name="minimum_rate" id="minimum_rate"
                                value="{{ old('minimum_rate', $rates['minimum_rate']->value ?? 200) }}" step="0.01" min="0"
                                required
                                class="block w-full pl-8 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        </div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            {{ $rates['minimum_rate']->description ?? 'Base charge for consumption up to minimum cubic meters' }}
                        </p>
                    </div>
                    <div>
                        <label for="minimum_consumption"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Minimum Consumption (m³)
                        </label>
                        <input type="number" name="minimum_consumption" id="minimum_consumption"
                            value="{{ old('minimum_consumption', $rates['minimum_consumption']->value ?? 10) }}" step="0.01"
                            min="0" required
                            class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            {{ $rates['minimum_consumption']->description ?? 'Cubic meters included in minimum rate' }}</p>
                    </div>
                </div>
            </div>

            <!-- Excess Rate Settings by Connection Type -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    Excess Rate Per Connection Type
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Residential -->
                    <div
                        class="p-4 border-2 border-blue-200 dark:border-blue-800 rounded-lg bg-blue-50 dark:bg-blue-900/20">
                        <label for="residential_excess_rate"
                            class="block text-sm font-medium text-blue-900 dark:text-blue-300 mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Residential Rate (per m³)
                        </label>
                        <div class="relative">
                            <span
                                class="absolute left-4 top-1/2 transform -translate-y-1/2 text-blue-600 dark:text-blue-400">₱</span>
                            <input type="number" name="residential_excess_rate" id="residential_excess_rate"
                                value="{{ old('residential_excess_rate', $rates['residential_excess_rate']->value ?? 15) }}"
                                step="0.01" min="0" required
                                class="block w-full pl-8 pr-4 py-3 border border-blue-300 dark:border-blue-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-blue-900/30 dark:text-white">
                        </div>
                        <p class="mt-1 text-xs text-blue-700 dark:text-blue-400">
                            {{ $rates['residential_excess_rate']->description ?? 'Rate per cubic meter beyond minimum' }}
                        </p>
                    </div>

                    <!-- Commercial -->
                    <div
                        class="p-4 border-2 border-purple-200 dark:border-purple-800 rounded-lg bg-purple-50 dark:bg-purple-900/20">
                        <label for="commercial_excess_rate"
                            class="block text-sm font-medium text-purple-900 dark:text-purple-300 mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Commercial Rate (per m³)
                        </label>
                        <div class="relative">
                            <span
                                class="absolute left-4 top-1/2 transform -translate-y-1/2 text-purple-600 dark:text-purple-400">₱</span>
                            <input type="number" name="commercial_excess_rate" id="commercial_excess_rate"
                                value="{{ old('commercial_excess_rate', $rates['commercial_excess_rate']->value ?? 20) }}"
                                step="0.01" min="0" required
                                class="block w-full pl-8 pr-4 py-3 border border-purple-300 dark:border-purple-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-purple-900/30 dark:text-white">
                        </div>
                        <p class="mt-1 text-xs text-purple-700 dark:text-purple-400">
                            {{ $rates['commercial_excess_rate']->description ?? 'Rate per cubic meter beyond minimum' }}</p>
                    </div>

                    <!-- Industrial -->
                    <div
                        class="p-4 border-2 border-orange-200 dark:border-orange-800 rounded-lg bg-orange-50 dark:bg-orange-900/20">
                        <label for="industrial_excess_rate"
                            class="block text-sm font-medium text-orange-900 dark:text-orange-300 mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                            </svg>
                            Industrial Rate (per m³)
                        </label>
                        <div class="relative">
                            <span
                                class="absolute left-4 top-1/2 transform -translate-y-1/2 text-orange-600 dark:text-orange-400">₱</span>
                            <input type="number" name="industrial_excess_rate" id="industrial_excess_rate"
                                value="{{ old('industrial_excess_rate', $rates['industrial_excess_rate']->value ?? 25) }}"
                                step="0.01" min="0" required
                                class="block w-full pl-8 pr-4 py-3 border border-orange-300 dark:border-orange-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:bg-orange-900/30 dark:text-white">
                        </div>
                        <p class="mt-1 text-xs text-orange-700 dark:text-orange-400">
                            {{ $rates['industrial_excess_rate']->description ?? 'Rate per cubic meter beyond minimum' }}</p>
                    </div>

                    <!-- Government -->
                    <div
                        class="p-4 border-2 border-green-200 dark:border-green-800 rounded-lg bg-green-50 dark:bg-green-900/20">
                        <label for="government_excess_rate"
                            class="block text-sm font-medium text-green-900 dark:text-green-300 mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                            </svg>
                            Government Rate (per m³)
                        </label>
                        <div class="relative">
                            <span
                                class="absolute left-4 top-1/2 transform -translate-y-1/2 text-green-600 dark:text-green-400">₱</span>
                            <input type="number" name="government_excess_rate" id="government_excess_rate"
                                value="{{ old('government_excess_rate', $rates['government_excess_rate']->value ?? 12) }}"
                                step="0.01" min="0" required
                                class="block w-full pl-8 pr-4 py-3 border border-green-300 dark:border-green-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-green-900/30 dark:text-white">
                        </div>
                        <p class="mt-1 text-xs text-green-700 dark:text-green-400">
                            {{ $rates['government_excess_rate']->description ?? 'Rate per cubic meter beyond minimum' }}</p>
                    </div>
                </div>
            </div>

            <!-- Example Calculation -->
            <div
                class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6 mb-6">
                <h3 class="text-md font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    Example Calculation
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-700 dark:text-gray-300 font-medium mb-1">Residential Consumer - 15 m³</p>
                        <p class="text-gray-600 dark:text-gray-400 ml-4">
                            = ₱{{ $rates['minimum_rate']->value ?? 200 }} + ((15 -
                            {{ $rates['minimum_consumption']->value ?? 10 }}) ×
                            ₱{{ $rates['residential_excess_rate']->value ?? 15 }})<br>
                            = ₱{{ $rates['minimum_rate']->value ?? 200 }} + (5 ×
                            ₱{{ $rates['residential_excess_rate']->value ?? 15 }})<br>
                            = <strong
                                class="text-blue-600 dark:text-blue-400">₱{{ ($rates['minimum_rate']->value ?? 200) + (5 * ($rates['residential_excess_rate']->value ?? 15)) }}</strong>
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-700 dark:text-gray-300 font-medium mb-1">Commercial Consumer - 15 m³</p>
                        <p class="text-gray-600 dark:text-gray-400 ml-4">
                            = ₱200 + ((15 - 10) × ₱{{ $rates['commercial_excess_rate']->value ?? 20 }})<br>
                            = ₱200 + (5 × ₱{{ $rates['commercial_excess_rate']->value ?? 20 }})<br>
                            = <strong
                                class="text-purple-600 dark:text-purple-400">₱{{ 200 + (5 * ($rates['commercial_excess_rate']->value ?? 20)) }}</strong>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('settings.index') }}"
                    class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Save Rate Settings
                </button>
            </div>
        </form>

    </div>
@endsection