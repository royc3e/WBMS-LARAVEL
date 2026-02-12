@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Welcome back, Admin</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Here's what's happening with your water billing system today
                </p>
            </div>
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="rounded-xl border border-slate-700/80 bg-slate-800/80 px-3 pl-10 py-2">
                        <p class="text-xs font-medium text-slate-300">{{ now()->format('l, F j, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <a href="{{ route('consumers.create') }}"
                class="group bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-xl p-4 transition-all duration-200 hover:shadow-lg hover:shadow-indigo-500/20">
                <div class="flex items-center justify-between">
                    <div
                        class="h-10 w-10 rounded-lg bg-white/10 flex items-center justify-center group-hover:bg-white/20 transition-colors">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-white font-bold text-lg mb-1">New Consumer</h3>
                <p class="text-blue-100 text-sm text-center">Add a new water consumer to the system</p>
            </a>

            <a href="{{ route('billings.index') }}"
                class="group bg-gradient-to-br from-green-600 to-green-800 rounded-xl p-4 transition-all duration-200 hover:shadow-lg hover:shadow-green-500/20">
                <div class="flex items-center justify-between">
                    <div
                        class="h-10 w-10 rounded-lg bg-white/10 flex items-center justify-center group-hover:bg-white/20 transition-colors">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-white font-bold text-lg mb-1">Record Payment</h3>
                <p class="text-green-100 text-sm">Process bill payments and receipts</p>
            </a>

            <a href="{{ route('reports.index') }}"
                class="group bg-gradient-to-br from-amber-600 to-amber-800 rounded-xl p-4 transition-all duration-200 hover:shadow-lg hover:shadow-amber-500/20">
                <div class="flex items-center justify-between">
                    <div
                        class="h-10 w-10 rounded-lg bg-white/10 flex items-center justify-center group-hover:bg-white/20 transition-colors">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-white font-bold text-lg mb-1">Generate Reports</h3>
                <p class="text-amber-100 text-sm">Create and export system reports</p>
            </a>

            <a href="{{ route('billings.index') }}"
                class="group bg-gradient-to-br from-purple-600 to-purple-800 rounded-xl p-4 transition-all duration-200 hover:shadow-lg hover:shadow-purple-500/20">
                <div class="flex items-center justify-between">
                    <div
                        class="h-10 w-10 rounded-lg bg-white/10 flex items-center justify-center group-hover:bg-white/20 transition-colors">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-white font-bold text-lg mb-1">Issue Bill</h3>
                <p class="text-purple-100 text-sm">Generate and issue water bills</p>
            </a>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Active Consumers -->
            <div
                class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 transition-all duration-200 hover:shadow-md">
                <div class="flex items-center">
                    <div
                        class="p-3 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Consumers</p>
                        <p class="text-2xl font-semibold text-slate-800 dark:text-white">{{ \App\Models\Consumer::count() }}
                        </p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Active in the system</p>
                    </div>
                </div>
            </div>

            <!-- Total Revenue -->
            <div
                class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 transition-all duration-200 hover:shadow-md">
                <div class="flex items-center">
                    <div
                        class="p-3 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Monthly Revenue</p>
                        <p class="text-2xl font-semibold text-slate-800 dark:text-white">₱248,750</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1"><span
                                class="text-emerald-500 font-medium">+8.2%</span> from last month</p>
                    </div>
                </div>
            </div>

            <!-- Pending Bills -->
            <div
                class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 transition-all duration-200 hover:shadow-md">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Pending Bills</p>
                        <p class="text-2xl font-semibold text-slate-800 dark:text-white">187</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1"><span
                                class="text-rose-500 font-medium">+5.6%</span> from last month</p>
                    </div>
                </div>
            </div>

            <!-- Water Consumption -->
            <div
                class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 transition-all duration-200 hover:shadow-md">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Avg. Consumption</p>
                        <p class="text-2xl font-semibold text-slate-800 dark:text-white">45.2 m³</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1"><span
                                class="text-emerald-500 font-medium">-2.3%</span> more efficient</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Revenue & Consumption Chart -->
            <div
                class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 lg:col-span-2">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-800 dark:text-white">Revenue & Consumption</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Last 6 months overview</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button
                            class="px-3 py-1 text-xs font-medium rounded-lg bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300">Monthly</button>
                        <button
                            class="px-3 py-1 text-xs font-medium rounded-lg text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700">Quarterly</button>
                        <button
                            class="px-3 py-1 text-xs font-medium rounded-lg text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700">Yearly</button>
                    </div>
                </div>

                <!-- Chart Placeholder -->
                <div class="h-64 bg-slate-50 dark:bg-slate-900/50 rounded-lg flex items-center justify-center">
                    <div class="text-center p-6">
                        <div class="mx-auto h-12 w-12 text-slate-300 dark:text-slate-600 mb-2">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                            </svg>
                        </div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Revenue and consumption chart will appear here
                        </p>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="p-4 bg-slate-50 dark:bg-slate-900/30 rounded-lg">
                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Current Month</p>
                        <p class="text-lg font-semibold text-slate-800 dark:text-white">₱248,750</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400"><span
                                class="text-emerald-500 font-medium">+8.2%</span> vs last month</p>
                    </div>
                    <div class="p-4 bg-slate-50 dark:bg-slate-900/30 rounded-lg">
                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Avg. Monthly Growth</p>
                        <p class="text-lg font-semibold text-slate-800 dark:text-white">5.8%</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Consistent growth trend</p>
                    </div>
                    <div class="p-4 bg-slate-50 dark:bg-slate-900/30 rounded-lg">
                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Water Saved</p>
                        <p class="text-lg font-semibold text-slate-800 dark:text-white">12,450 m³</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400"><span
                                class="text-emerald-500 font-medium">+15%</span> efficiency</p>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-800 dark:text-white">Recent Transactions</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Latest payments and bills</p>
                    </div>
                    <a href="#"
                        class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">View
                        All</a>
                </div>

                <div class="space-y-4">
                    <!-- Transaction 1 -->
                    <div class="flex items-start">
                        <div
                            class="p-2 rounded-lg bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-slate-800 dark:text-white">Payment Received</p>
                                <p class="text-sm font-medium text-emerald-600 dark:text-emerald-400">₱2,450.00</p>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Juan Dela Cruz • 2 hours ago</p>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Account #2023-00123</p>
                        </div>
                    </div>

                    <!-- Transaction 2 -->
                    <div class="flex items-start">
                        <div class="p-2 rounded-lg bg-rose-100 text-rose-600 dark:bg-rose-900/30 dark:text-rose-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-slate-800 dark:text-white">Bill Generated</p>
                                <p class="text-sm font-medium text-rose-600 dark:text-rose-400">-₱1,875.00</p>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Maria Santos • 5 hours ago</p>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Account #2023-00456</p>
                        </div>
                    </div>

                    <!-- Transaction 3 -->
                    <div class="flex items-start">
                        <div class="p-2 rounded-lg bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-slate-800 dark:text-white">Payment Pending</p>
                                <p class="text-sm font-medium text-amber-600 dark:text-amber-400">₱3,210.00</p>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Pedro Reyes • 1 day ago</p>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Account #2023-00321</p>
                        </div>
                    </div>

                    <!-- Transaction 4 -->
                    <div class="flex items-start">
                        <div
                            class="p-2 rounded-lg bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-slate-800 dark:text-white">Account Created</p>
                                <p class="text-xs font-medium text-indigo-600 dark:text-indigo-400">New</p>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Ana Torres • 2 days ago</p>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Account #2023-00987</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 text-center">
                    <a href="#"
                        class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                        View all transactions
                        <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Bottom Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Water Consumption by Zone -->
            <div
                class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 lg:col-span-2">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-800 dark:text-white">Consumption by Zone</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Water usage distribution across zones</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <select
                            class="text-sm rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300">
                            <option>This Month</option>
                            <option>Last Month</option>
                            <option>Last 3 Months</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Zone A - Residential</span>
                            <span class="text-sm font-medium text-slate-900 dark:text-white">35%</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2.5 dark:bg-slate-700">
                            <div class="bg-indigo-600 h-2.5 rounded-full" style="width: 35%"></div>
                        </div>
                        <div class="mt-1 text-xs text-slate-500 dark:text-slate-400">1,245 consumers</div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Zone B - Commercial</span>
                            <span class="text-sm font-medium text-slate-900 dark:text-white">25%</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2.5 dark:bg-slate-700">
                            <div class="bg-emerald-500 h-2.5 rounded-full" style="width: 25%"></div>
                        </div>
                        <div class="mt-1 text-xs text-slate-500 dark:text-slate-400">890 consumers</div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Zone C - Industrial</span>
                            <span class="text-sm font-medium text-slate-900 dark:text-white">20%</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2.5 dark:bg-slate-700">
                            <div class="bg-amber-500 h-2.5 rounded-full" style="width: 20%"></div>
                        </div>
                        <div class="mt-1 text-xs text-slate-500 dark:text-slate-400">712 consumers</div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Zone D - Government</span>
                            <span class="text-sm font-medium text-slate-900 dark:text-white">15%</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2.5 dark:bg-slate-700">
                            <div class="bg-rose-500 h-2.5 rounded-full" style="width: 15%"></div>
                        </div>
                        <div class="mt-1 text-xs text-slate-500 dark:text-slate-400">534 consumers</div>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Total Water Consumption</p>
                            <p class="text-2xl font-bold text-slate-900 dark:text-white">1,245,890 m³</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400"><span
                                    class="text-emerald-500 font-medium">+5.2%</span> vs last month</p>
                        </div>
                        <button
                            class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-700 dark:hover:bg-indigo-600">
                            View Detailed Report
                        </button>
                    </div>
                </div>
            </div>

            <!-- System Status -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-800 dark:text-white">System Status</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Current system performance</p>
                    </div>
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                        <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                            <circle cx="4" cy="4" r="3" />
                        </svg>
                        All Systems Operational
                    </span>
                </div>

                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 pt-0.5">
                            <div class="h-2.5 w-2.5 rounded-full bg-green-400"></div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-slate-800 dark:text-white">Billing System</p>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Processing payments and generating bills
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 pt-0.5">
                            <div class="h-2.5 w-2.5 rounded-full bg-green-400"></div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-slate-800 dark:text-white">Database</p>
                            <p class="text-sm text-slate-500 dark:text-slate-400">All consumer records are up to date</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 pt-0.5">
                            <div class="h-2.5 w-2.5 rounded-full bg-green-400"></div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-slate-800 dark:text-white">API Services</p>
                            <p class="text-sm text-slate-500 dark:text-slate-400">All services are running smoothly</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 pt-0.5">
                            <div class="h-2.5 w-2.5 rounded-full bg-green-400"></div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-slate-800 dark:text-white">Backup System</p>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Last backup: Today, 2:00 AM</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="text-sm font-medium text-slate-800 dark:text-white mb-3">Quick Links</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="#"
                            class="p-3 bg-slate-50 dark:bg-slate-800 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                            <div class="flex items-center">
                                <div
                                    class="p-2 rounded-lg bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400 mr-3">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">User Management</span>
                            </div>
                        </a>
                        <a href="#"
                            class="p-3 bg-slate-50 dark:bg-slate-800 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                            <div class="flex items-center">
                                <div
                                    class="p-2 rounded-lg bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 mr-3">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Reports</span>
                            </div>
                        </a>
                        <a href="#"
                            class="p-3 bg-slate-50 dark:bg-slate-800 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                            <div class="flex items-center">
                                <div
                                    class="p-2 rounded-lg bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400 mr-3">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Billing History</span>
                            </div>
                        </a>
                        <a href="#"
                            class="p-3 bg-slate-50 dark:bg-slate-800 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                            <div class="flex items-center">
                                <div
                                    class="p-2 rounded-lg bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 mr-3">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Settings</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Chart.js for future chart implementation -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@endsection