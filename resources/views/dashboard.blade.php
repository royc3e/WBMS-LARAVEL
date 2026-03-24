@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-8">

        {{-- ================================================ --}}
        {{-- TOP HEADER: Welcome Banner --}}
        {{-- ================================================ --}}
        <div
            class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-700 via-blue-600 to-blue-500 px-6 py-7 shadow-lg shadow-blue-200">
            {{-- Background pattern --}}
            <div class="absolute inset-0 opacity-[0.06]"
                style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 28px 28px;">
            </div>
            {{-- Bottom wave --}}
            <svg class="absolute bottom-0 left-0 right-0 w-full opacity-10 text-white" viewBox="0 0 1440 80"
                preserveAspectRatio="none" fill="currentColor">
                <path
                    d="M0,64L80,58.7C160,53,320,43,480,42.7C640,43,800,53,960,56C1120,59,1280,53,1360,50.7L1440,48L1440,80L1360,80C1280,80,1120,80,960,80C800,80,640,80,480,80C320,80,160,80,80,80L0,80Z" />
            </svg>

            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <p class="text-blue-100 text-xs font-bold uppercase tracking-widest mb-1">Dashboard Overview</p>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-white">
                        Welcome back, {{ Auth::user()->name ?? 'User' }}!
                    </h1>
                    <p class="mt-1 text-blue-100 text-sm">
                        Here's what's happening with Barangay Maribulan Water System today.
                    </p>
                </div>
                <div class="flex-shrink-0">
                    <div
                        class="inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/10 backdrop-blur-sm px-4 py-2.5 shadow-inner">
                        <svg class="h-4 w-4 text-blue-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-white text-sm font-semibold">{{ now()->format('l, F j, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================================================ --}}
        {{-- STAT CARDS ROW --}}
        {{-- ================================================ --}}
        @php
            $totalConsumers = \App\Models\Consumer::count();
            $activeConsumers = \App\Models\Consumer::where('connection_status', 'active')->count();
            $unpaidAccounts = \App\Models\Billing::where('status', 'pending')->count();
            $monthlyRevenue = \App\Models\Payment::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount');
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

            {{-- Card 1: Total Consumers --}}
            <div
                class="group bg-white rounded-xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Total Consumers</p>
                        <p class="mt-2 text-2xl font-bold text-slate-800">{{ number_format($totalConsumers) }}</p>
                        <p class="mt-1 text-xs text-slate-400">Registered in the system</p>
                    </div>
                    <div
                        class="flex-shrink-0 h-10 w-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600 group-hover:bg-slate-200 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-50 flex items-center gap-1.5">
                    <span class="text-slate-400 text-xs">👥 All registered households</span>
                </div>
            </div>

            {{-- Card 2: Active Consumers --}}
            <div
                class="group bg-white rounded-xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Active Consumers</p>
                        <p class="mt-2 text-2xl font-bold text-green-600">{{ number_format($activeConsumers) }}</p>
                        <p class="mt-1 text-xs text-green-500 font-medium">Currently connected accounts</p>
                    </div>
                    <div
                        class="flex-shrink-0 h-10 w-10 rounded-lg bg-green-100 flex items-center justify-center text-green-600 group-hover:bg-green-200 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-50">
                    <div class="w-full bg-slate-100 rounded-full h-1.5">
                        <div class="bg-green-500 h-1.5 rounded-full transition-all duration-700"
                            style="width: {{ $totalConsumers > 0 ? round(($activeConsumers / $totalConsumers) * 100) : 0 }}%">
                        </div>
                    </div>
                    <p class="text-xs text-slate-400 mt-1.5">
                        {{ $totalConsumers > 0 ? round(($activeConsumers / $totalConsumers) * 100) : 0 }}% of total
                        consumers
                    </p>
                </div>
            </div>

            {{-- Card 3: Unpaid Accounts --}}
            <div
                class="group bg-white rounded-xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Unpaid Accounts</p>
                        <p class="mt-2 text-2xl font-bold text-red-500">{{ number_format($unpaidAccounts) }}</p>
                        <p class="mt-1 text-xs text-red-400 font-medium">Consumers with pending payments</p>
                    </div>
                    <div
                        class="flex-shrink-0 h-10 w-10 rounded-lg bg-red-100 flex items-center justify-center text-red-500 group-hover:bg-red-200 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-50 flex items-center gap-1.5">
                    @if($unpaidAccounts > 0)
                        <span
                            class="inline-flex items-center gap-1 text-xs font-semibold text-red-500 bg-red-50 px-2 py-0.5 rounded-full">
                            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3" />
                            </svg>
                            Needs immediate action
                        </span>
                    @else
                        <span
                            class="inline-flex items-center gap-1 text-xs font-semibold text-green-500 bg-green-50 px-2 py-0.5 rounded-full">
                            ✓ All accounts settled
                        </span>
                    @endif
                </div>
            </div>

            {{-- Card 4: Monthly Revenue --}}
            <div
                class="group bg-white rounded-xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Monthly Revenue</p>
                        <p class="mt-2 text-2xl font-bold text-blue-600">₱{{ number_format($monthlyRevenue, 2) }}</p>
                        <p class="mt-1 text-xs text-slate-400">Total collected — {{ now()->format('F Y') }}</p>
                    </div>
                    <div
                        class="flex-shrink-0 h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600 group-hover:bg-blue-200 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-50 flex items-center gap-1.5">
                    <span class="text-blue-500 text-xs font-semibold">💰</span>
                    <span class="text-slate-400 text-xs">Payments collected this month</span>
                </div>
            </div>

        </div>

        {{-- ================================================ --}}
        {{-- QUICK ACTION CARDS --}}
        {{-- ================================================ --}}
        <div>
            <h2 class="text-sm font-bold uppercase tracking-widest text-slate-400 mb-4">Quick Actions</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                <a href="{{ route('consumers.create') }}"
                    class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-600 to-blue-800 p-5 shadow-md shadow-blue-200 transition-all duration-300 hover:shadow-xl hover:shadow-blue-300 hover:-translate-y-1">
                    <div
                        class="absolute -right-4 -top-4 h-20 w-20 rounded-full bg-white/10 group-hover:bg-white/15 transition-colors">
                    </div>
                    <div
                        class="h-10 w-10 rounded-xl bg-white/15 flex items-center justify-center mb-4 group-hover:bg-white/25 transition-colors">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                    <h3 class="text-white font-bold text-base leading-tight">New Consumer</h3>
                    <p class="text-blue-100 text-xs mt-1 leading-snug">Add a new water consumer</p>
                </a>

                <a href="{{ route('billings.index') }}"
                    class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-700 p-5 shadow-md shadow-emerald-200 transition-all duration-300 hover:shadow-xl hover:shadow-emerald-300 hover:-translate-y-1">
                    <div
                        class="absolute -right-4 -top-4 h-20 w-20 rounded-full bg-white/10 group-hover:bg-white/15 transition-colors">
                    </div>
                    <div
                        class="h-10 w-10 rounded-xl bg-white/15 flex items-center justify-center mb-4 group-hover:bg-white/25 transition-colors">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-white font-bold text-base leading-tight">Record Payment</h3>
                    <p class="text-emerald-100 text-xs mt-1 leading-snug">Process bill payments</p>
                </a>

                <a href="{{ route('reports.index') }}"
                    class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 p-5 shadow-md shadow-amber-200 transition-all duration-300 hover:shadow-xl hover:shadow-amber-300 hover:-translate-y-1">
                    <div
                        class="absolute -right-4 -top-4 h-20 w-20 rounded-full bg-white/10 group-hover:bg-white/15 transition-colors">
                    </div>
                    <div
                        class="h-10 w-10 rounded-xl bg-white/15 flex items-center justify-center mb-4 group-hover:bg-white/25 transition-colors">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-white font-bold text-base leading-tight">Generate Reports</h3>
                    <p class="text-amber-100 text-xs mt-1 leading-snug">Create and export reports</p>
                </a>

                <a href="{{ route('billings.generate') }}"
                    class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-600 to-purple-800 p-5 shadow-md shadow-purple-200 transition-all duration-300 hover:shadow-xl hover:shadow-purple-300 hover:-translate-y-1">
                    <div
                        class="absolute -right-4 -top-4 h-20 w-20 rounded-full bg-white/10 group-hover:bg-white/15 transition-colors">
                    </div>
                    <div
                        class="h-10 w-10 rounded-xl bg-white/15 flex items-center justify-center mb-4 group-hover:bg-white/25 transition-colors">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="text-white font-bold text-base leading-tight">Issue Bill</h3>
                    <p class="text-purple-100 text-xs mt-1 leading-snug">Generate water bills</p>
                </a>
            </div>
        </div>

        {{-- ================================================ --}}
        {{-- MAIN CONTENT: Chart + Recent Transactions --}}
        {{-- ================================================ --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Revenue & Consumption Chart --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:col-span-2">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Revenue & Consumption</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Last 6 months overview</p>
                    </div>
                    <div class="flex items-center gap-1 bg-slate-50 rounded-lg p-1 border border-slate-100">
                        <button
                            class="px-3 py-1.5 text-xs font-semibold rounded-md bg-blue-600 text-white shadow-sm">Monthly</button>
                        <button
                            class="px-3 py-1.5 text-xs font-medium rounded-md text-slate-500 hover:text-slate-700 hover:bg-white transition-colors">Quarterly</button>
                        <button
                            class="px-3 py-1.5 text-xs font-medium rounded-md text-slate-500 hover:text-slate-700 hover:bg-white transition-colors">Yearly</button>
                    </div>
                </div>

                {{-- Visual Bar Chart (CSS-only) --}}
                <div class="h-52 flex items-end justify-between gap-2 px-2 mb-3">
                    @foreach([['Oct', '65', 'bg-blue-200'], ['Nov', '80', 'bg-blue-300'], ['Dec', '72', 'bg-blue-400'], ['Jan', '88', 'bg-blue-500'], ['Feb', '75', 'bg-blue-400'], ['Mar', '95', 'bg-blue-600']] as [$month, $height, $color])
                        <div class="flex-1 flex flex-col items-center gap-1">
                            <span class="text-[10px] font-bold text-slate-400">{{ $month }}</span>
                            <div class="w-full rounded-t-lg {{ $color }} transition-all duration-500 hover:opacity-80"
                                style="height: {{ $height }}%;"></div>
                        </div>
                    @endforeach
                </div>

                <div class="grid grid-cols-3 gap-3 mt-4">
                    <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">This Month</p>
                        <p class="text-lg font-extrabold text-slate-800 mt-0.5">₱248,750</p>
                        <p class="text-xs text-emerald-500 font-semibold mt-0.5">+8.2% vs last</p>
                    </div>
                    <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Avg. Growth</p>
                        <p class="text-lg font-extrabold text-slate-800 mt-0.5">5.8%</p>
                        <p class="text-xs text-slate-400 mt-0.5">Consistent trend</p>
                    </div>
                    <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Water Saved</p>
                        <p class="text-lg font-extrabold text-slate-800 mt-0.5">12,450 m³</p>
                        <p class="text-xs text-emerald-500 font-semibold mt-0.5">+15% efficiency</p>
                    </div>
                </div>
            </div>

            {{-- Recent Transactions --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Recent Transactions</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Latest payments & bills</p>
                    </div>
                    <a href="{{ route('payments.index') }}"
                        class="text-xs font-bold text-blue-600 hover:text-blue-700 transition-colors">View All →</a>
                </div>

                <div class="space-y-4">
                    @foreach([
                                ['icon_bg' => 'bg-emerald-100', 'icon_color' => 'text-emerald-600', 'type' => 'Payment Received', 'amount' => '₱2,450.00', 'amount_color' => 'text-emerald-600', 'name' => 'Juan Dela Cruz', 'time' => '2 hours ago', 'acc' => '#2026-00123', 'icon' => 'check'],
                                ['icon_bg' => 'bg-rose-100', 'icon_color' => 'text-rose-600', 'type' => 'Bill Generated', 'amount' => '-₱1,875.00', 'amount_color' => 'text-rose-600', 'name' => 'Maria Santos', 'time' => '5 hours ago', 'acc' => '#2026-00456', 'icon' => 'document'],
                                ['icon_bg' => 'bg-amber-100', 'icon_color' => 'text-amber-600', 'type' => 'Payment Pending', 'amount' => '₱3,210.00', 'amount_color' => 'text-amber-600', 'name' => 'Pedro Reyes', 'time' => '1 day ago', 'acc' => '#2026-00321', 'icon' => 'clock'],
                                ['icon_bg' => 'bg-blue-100', 'icon_color' => 'text-blue-600', 'type' => 'Account Created', 'amount' => 'New', 'amount_color' => 'text-blue-600', 'name' => 'Ana Torres', 'time' => '2 days ago', 'acc' => '#2026-00987', 'icon' => 'user'],
                            ] as $tx)
                        <div class="flex items-start gap-3">



                                                               <div class="flex-shrink-0 h-9 w-9 rounded-xl {{ $tx['icon_bg'] }} {{ $tx['icon_color'] }} flex items-center justify-center">
                                @if($tx['icon'] === 'check')



                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                                @elseif($tx['icon'] === 'document')



                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                @elseif($tx['icon'] === 'clock')
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    @else
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2">
                                        <p class="text-sm font-semibold text-slate-800 truncate">{{ $tx['type'] }}</p>
                                        <p class="text-sm font-bold {{ $tx['amount_color'] }} flex-shrink-0">{{ $tx['amount'] }}</p>
                                    </div>
                                    <p class="text-xs text-slate-500">{{ $tx['name'] }} &bull; {{ $tx['time'] }}</p>
                                    <p class="text-xs text-slate-400">Acc {{ $tx['acc'] }}</p>
                                </div>
                            </div>

                       @endforeach
                </div>



                <div class="mt-5 pt-4 border-t border-slate-100 text-center">
                    <a href="{{ route('payments.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                        View all transactions
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>
            </div>

        </div>

        {{-- ================================================ --}}
        {{-- BOTTOM SECTION: Consumption Zones + System Status --}}
        {{-- ================================================ --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Consumption by Zone --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:col-span-2">
                <div class=
                       "flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Consumption by Connection Type</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Water usage distribution across consumer types</p>
                    </div>
                    <select class="text-xs rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-slate-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option>This Month</option>
                        <option>Last Month</option>
                        <option>Last 3 Months</option>
                    </select>
                </div>
                <div class="space-y-5">
                    @foreach([
                            ['label' => 'Residential', 'pct' => 45, 'consumers' => '1,245', 'color' => 'bg-blue-500'],
                            ['label' => 'Commercial', 'pct' => 30, 'consumers' => '890', 'color' => 'bg-emerald-500'],
                            ['label' => 'Industrial', 'pct' => 15, 'consumers' => '320', 'color' => 'bg-amber-500'],
                            ['label' => 'Government', 'pct' => 10, 'consumers' => '145', 'color' => 'bg-rose-500'],
                        ] as $zone)
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">

                                                               <span class="h-2.5 w-2.5 rounded-full {{ $zone['color'] }}"></span>
                                    <span class="text-sm font-semibold text-slate-700">{{ $zone['label'] }}</span>
                                </div>
                                <div class="flex items-center gap-3">

                                   <span class="text-xs text-slate-400">{{ $zone['consumers'] }} consumers</span>
                                    <span class="text-sm font-bold text-slate-800">{{ $zone['pct'] }}%</span>
                                </div>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2.5">
                                <div class="{{ $zone['color'] }} h-2.5 rounded-full transition-all duration-700" style="width: {{ $zone['pct'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>


                                    <div class="mt-6 pt-5 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Total Water Consumption</p>
                        <p class="text-2xl font-extrabold text-slate-900 mt-0.5">1,245,890 m³</p>
                        <p class="text-xs text-emerald-500 font-semibold mt-0.5">+5.2% vs last month</p>
                    </div>
                    <a href="{{ route('reports.index') }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-sm shadow-blue-200 transition-all duration-200 hover:-translate-y-0.5">
                        View Detailed Report
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>
            </div>


                                   {{-- System Status + Quick Links --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex flex-col gap-6">
                {{-- System Status --}}
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="text-base font-bold text-slate-800">System Status</h2>
                            <p c
                               lass="text-xs text-slate-400 mt-0.5">Current performance</p>

                                                </div>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Operational
                        </span>
                    </div>
                    <div class="space-y-3">
                        @foreach([
                                ['Billing System', 'Processing payments and bills'],
                                ['Database', 'All consumer records up to date'],
                                ['API Services', 'All services running smoothly'],
                                ['Backup', 'Last backup: Today, 2:00 AM'],
                            ] as [$name, $desc])
                            <div class="flex items-start gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                                <div class="flex-
                               shrink-0 mt-0.5 h-2 w-2 rounded-full bg-emerald-500 shadow-[0_0_6px_rgba(16,185,129,0.5)]"></div>
                                <div
                                   >
                                    <p class="text-sm font-semibold text-slate-800">{{ $name }}</p>
                                    <p class="text-xs text-slate-400 leading-relaxed">{{ $desc }}</p>

                                                                   </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Quick Links</h3>
                    <div class="grid grid-cols-2 gap-2.5">
                        @foreach([
                                ['User Mgmt', 'bg-blue-50 text-blue-600', route('settings.users')],
                                ['Reports', 'bg-emerald-50 text-emerald-600', route('reports.index')],
                                ['Billing', 'bg-amber-50 text-amber-600', route('billings.index')],
                                ['Settings', 'bg-purple-50 text-purple-600', route('settings.index')],
                            ] as [$label, $style, $href])
                            <a href="{{ $href }}" class="p-3 rounded-xl border border-slate-100 hover:border-slate-200 hover:shadow-sm transition-all duration-200 group">
                                <div class="h-8 w-8 rounded-lg {{ $style }} flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold text-slate-700">{{ $label }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection