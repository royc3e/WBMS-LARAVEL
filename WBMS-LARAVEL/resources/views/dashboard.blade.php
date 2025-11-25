@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Dashboard</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Overview of your water billing management system
            </p>
        </div>
        <div class="flex items-center gap-3 text-sm text-gray-500 dark:text-gray-400">
            <div class="rounded-xl border border-slate-700/80 bg-slate-800/80 px-3 py-2">
                <p class="font-medium text-slate-300">Today</p>
                <p class="mt-0.5 text-xs uppercase tracking-wide">{{ now()->format('M d, Y') }}</p>
            </div>
        </div>
    </div>
    </div>

    {{-- Dashboard Cards --}}
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">

        {{-- Total Consumers --}}
        <div class="rounded-2xl border border-slate-800/80 bg-slate-900/60 p-6 text-center shadow-lg shadow-slate-950/40">
            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Total Consumers</p>
            <p class="mt-3 text-3xl font-semibold text-indigo-400">0</p>
            <p class="mt-2 text-xs text-slate-500">All registered consumer accounts.</p>
        </div>

        {{-- Pending Bills --}}
        <div class="rounded-2xl border border-slate-800/80 bg-slate-900/60 p-6 text-center shadow-lg shadow-slate-950/40">
            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Pending Bills</p>
            <p class="mt-3 text-3xl font-semibold text-amber-400">0</p>
            <p class="mt-2 text-xs text-slate-500">Bills that are generated but not yet paid.</p>
        </div>

        {{-- Total Payments --}}
        <div class="rounded-2xl border border-slate-800/80 bg-slate-900/60 p-6 text-center shadow-lg shadow-slate-950/40">
            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Total Payments (This Month)</p>
            <p class="mt-3 text-3xl font-semibold text-emerald-400">₱0.00</p>
            <p class="mt-2 text-xs text-slate-500">Total amount collected for the current billing period.</p>
        </div>

    </div>

    {{-- Secondary content --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- Billing overview --}}
        <div class="rounded-2xl border border-slate-800/80 bg-slate-900/60 p-6 shadow-lg shadow-slate-950/40 lg:col-span-2">
            <div class="flex items-center justify-between gap-2">
                <div>
                    <h2 class="text-sm font-semibold text-white">Billing Overview</h2>
                    <p class="mt-1 text-xs text-slate-400">Snapshot of the current billing cycle.</p>
                </div>
                <span class="rounded-full bg-emerald-500/10 px-2 py-1 text-[10px] font-medium uppercase tracking-wide text-emerald-400">On Track</span>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <p class="text-[11px] uppercase tracking-wide text-slate-500">Billed This Month</p>
                    <p class="mt-2 text-lg font-semibold text-slate-100">₱0.00</p>
                </div>
                <div>
                    <p class="text-[11px] uppercase tracking-wide text-slate-500">Collected</p>
                    <p class="mt-2 text-lg font-semibold text-emerald-400">₱0.00</p>
                </div>
                <div>
                    <p class="text-[11px] uppercase tracking-wide text-slate-500">Outstanding</p>
                    <p class="mt-2 text-lg font-semibold text-amber-400">₱0.00</p>
                </div>
            </div>
        </div>

        {{-- Recent activity --}}
        <div class="rounded-2xl border border-slate-800/80 bg-slate-900/60 p-6 shadow-lg shadow-slate-950/40">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-semibold text-white">Recent Activity</h2>
                <span class="text-[11px] uppercase tracking-wide text-slate-500">Today</span>
            </div>
            <p class="mt-2 text-xs text-slate-500">Recent bills and payments will appear here as data is recorded.</p>

            <ul class="mt-4 space-y-3 text-xs text-slate-300">
                <li class="flex items-center justify-between">
                    <span class="text-slate-400">No recent activity</span>
                </li>
            </ul>
        </div>

    </div>
</div>
@endsection

</div>
</body>
</html>
