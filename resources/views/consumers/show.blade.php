@extends('layouts.app')

@section('title', 'Consumer Details')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 h-8 w-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                    <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                </div>
                <p class="text-sm font-semibold text-emerald-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 h-8 w-8 rounded-lg bg-red-100 flex items-center justify-center">
                    <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </div>
                <p class="text-sm font-semibold text-red-800">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    {{-- ================================================ --}}
    {{-- PAGE HEADER --}}
    {{-- ================================================ --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center shadow-lg shadow-blue-200">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-900">
                        {{ $consumer->first_name }} {{ $consumer->middle_name ?? '' }} {{ $consumer->last_name }}
                    </h1>
                    <p class="text-sm text-slate-500 font-medium">Account #{{ $consumer->account_number }}</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('consumers.edit', $consumer) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-sm transition-all duration-200 hover:-translate-y-0.5">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                Edit
            </a>
            <a href="{{ route('consumers.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 text-sm font-bold rounded-xl border border-slate-200 shadow-sm transition-all duration-200">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Back to List
            </a>
        </div>
    </div>

    {{-- ================================================ --}}
    {{-- MAIN GRID: Details + Service Status --}}
    {{-- ================================================ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT COLUMN: Consumer Information (2 cols) --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Account Information Card --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Account Information</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Account Number</p>
                            <p class="text-sm font-bold text-slate-800">{{ $consumer->account_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Meter Number</p>
                            <p class="text-sm font-bold text-slate-800">{{ $consumer->meter_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Connection Date</p>
                            <p class="text-sm font-bold text-slate-800">{{ $consumer->connection_date ? \Carbon\Carbon::parse($consumer->connection_date)->format('M d, Y') : 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Connection Type</p>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold
                                {{ $consumer->connection_type === 'residential' ? 'bg-blue-50 text-blue-700 border border-blue-100' :
                                   ($consumer->connection_type === 'commercial' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' :
                                   ($consumer->connection_type === 'industrial' ? 'bg-amber-50 text-amber-700 border border-amber-100' :
                                   'bg-purple-50 text-purple-700 border border-purple-100')) }}">
                                {{ $consumer->connection_type ? ucfirst($consumer->connection_type) : 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Contact & Address Card --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Contact & Address</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Email</p>
                            <p class="text-sm font-medium text-slate-800">{{ $consumer->email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Phone</p>
                            <p class="text-sm font-medium text-slate-800">{{ $consumer->phone ?? 'N/A' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Address</p>
                            <p class="text-sm font-medium text-slate-800">
                                {{ $consumer->address_line_1 ?? '' }}{{ $consumer->address_line_2 ? ', ' . $consumer->address_line_2 : '' }},
                                {{ $consumer->city ?? '' }}, {{ $consumer->state ?? '' }} {{ $consumer->postal_code ?? '' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            @if($consumer->notes)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Notes</h2>
                </div>
                <div class="p-6">
                    <p class="text-sm text-slate-700 leading-relaxed">{{ $consumer->notes }}</p>
                </div>
            </div>
            @endif

            {{-- Outstanding Balance Warning --}}
            @if($outstandingBalance > 0)
            <div class="rounded-2xl border border-red-200 bg-gradient-to-r from-red-50 to-rose-50 p-5 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 h-12 w-12 rounded-xl bg-red-100 flex items-center justify-center">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-red-800">Outstanding Balance</p>
                        <p class="text-2xl font-extrabold text-red-600 mt-0.5">₱{{ number_format($outstandingBalance, 2) }}</p>
                        <p class="text-xs text-red-500 mt-0.5">{{ $unpaidBillsCount }} unpaid bill(s) remaining</p>
                    </div>
                </div>
            </div>
            @endif

            {{-- Recent Billings Table --}}
            @if($bills->count() > 0)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Recent Billings</h2>
                    <a href="{{ route('billing.ledger.show', $consumer) }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 transition-colors">View Ledger →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Month</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-50">
                            @foreach($bills->sortByDesc('billing_month')->take(5) as $bill)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-800">
                                    {{ \Carbon\Carbon::parse($bill->billing_month)->format('F Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-slate-800">
                                    ₱{{ number_format($bill->amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-bold rounded-lg
                                        {{ $bill->status === 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' :
                                           ($bill->status === 'overdue' ? 'bg-red-50 text-red-700 border border-red-100' :
                                           ($bill->status === 'cancelled' ? 'bg-slate-50 text-slate-500 border border-slate-100' :
                                           'bg-amber-50 text-amber-700 border border-amber-100')) }}">
                                        {{ ucfirst($bill->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>

        {{-- RIGHT COLUMN: Service Status Panel --}}
        <div class="space-y-6">

            {{-- ================================================ --}}
            {{-- SERVICE STATUS PANEL --}}
            {{-- ================================================ --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden" x-data="{ showDisconnectModal: false, showReconnectModal: false }">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Service Status</h2>
                </div>
                <div class="p-6 space-y-5">

                    {{-- Current Status Badge --}}
                    <div class="text-center">
                        @if($consumer->connection_status === 'active')
                            <div class="inline-flex flex-col items-center gap-2">
                                <div class="h-16 w-16 rounded-2xl bg-emerald-100 flex items-center justify-center">
                                    <svg class="h-8 w-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-sm font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Active
                                </span>
                            </div>
                        @elseif($consumer->connection_status === 'disconnected')
                            <div class="inline-flex flex-col items-center gap-2">
                                <div class="h-16 w-16 rounded-2xl bg-red-100 flex items-center justify-center">
                                    <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                    </svg>
                                </div>
                                <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-sm font-extrabold bg-red-50 text-red-700 border border-red-200">
                                    <span class="h-2 w-2 rounded-full bg-red-500"></span>
                                    Disconnected
                                </span>
                            </div>
                        @else
                            <div class="inline-flex flex-col items-center gap-2">
                                <div class="h-16 w-16 rounded-2xl bg-amber-100 flex items-center justify-center">
                                    <svg class="h-8 w-8 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-sm font-extrabold bg-amber-50 text-amber-700 border border-amber-200">
                                    <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                                    {{ ucfirst($consumer->connection_status ?? 'Unknown') }}
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Latest Status Change Info --}}
                    @if($consumer->latestServiceStatus)
                        <div class="rounded-xl bg-slate-50 border border-slate-100 p-4 space-y-2">
                            <div>
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Last Status Change</p>
                                <p class="text-sm font-bold text-slate-800 mt-0.5">
                                    {{ \Carbon\Carbon::parse($consumer->latestServiceStatus->status_date)->format('M d, Y') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Reason</p>
                                <p class="text-sm text-slate-700 mt-0.5">{{ $consumer->latestServiceStatus->reason }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Processed By</p>
                                <p class="text-sm text-slate-700 mt-0.5">{{ $consumer->latestServiceStatus->processedBy->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    @else
                        <div class="rounded-xl bg-slate-50 border border-slate-100 p-4 text-center">
                            <p class="text-xs text-slate-400">No status change history</p>
                        </div>
                    @endif

                    {{-- Action Buttons --}}
                    @if(in_array(Auth::user()->role, ['admin', 'staff']))
                        <div class="space-y-3 pt-2">
                            @if($consumer->connection_status === 'active')
                                {{-- Disconnect Button --}}
                                <button @click="showDisconnectModal = true"
                                    @if($unpaidBillsCount === 0) disabled @endif
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 text-sm font-bold rounded-xl transition-all duration-200
                                        {{ $unpaidBillsCount > 0
                                            ? 'bg-red-600 hover:bg-red-700 text-white shadow-sm shadow-red-200 hover:-translate-y-0.5 cursor-pointer'
                                            : 'bg-slate-100 text-slate-400 cursor-not-allowed' }}">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                    </svg>
                                    Disconnect Service
                                </button>
                                @if($unpaidBillsCount === 0)
                                    <p class="text-xs text-slate-400 text-center">No unpaid bills — disconnection not applicable</p>
                                @else
                                    <p class="text-xs text-red-500 text-center font-medium">{{ $unpaidBillsCount }} unpaid bill(s) found</p>
                                @endif
                            @else
                                {{-- Reconnect Button --}}
                                <button @click="showReconnectModal = true"
                                    @if($outstandingBalance > 0) disabled @endif
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 text-sm font-bold rounded-xl transition-all duration-200
                                        {{ $outstandingBalance <= 0
                                            ? 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm shadow-emerald-200 hover:-translate-y-0.5 cursor-pointer'
                                            : 'bg-slate-100 text-slate-400 cursor-not-allowed' }}">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    Reconnect Service
                                </button>
                                @if($outstandingBalance > 0)
                                    <p class="text-xs text-red-500 text-center font-medium">Outstanding balance of ₱{{ number_format($outstandingBalance, 2) }} must be settled first</p>
                                @else
                                    <p class="text-xs text-emerald-500 text-center font-medium">All balances settled — eligible for reconnection</p>
                                @endif
                            @endif
                        </div>
                    @endif
                </div>

                {{-- ============================================ --}}
                {{-- DISCONNECT MODAL --}}
                {{-- ============================================ --}}
                <div x-cloak x-show="showDisconnectModal"
                     class="fixed inset-0 z-50 flex items-center justify-center p-4"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0">
                    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showDisconnectModal = false"></div>
                    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md border border-slate-200 overflow-hidden"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                         @click.away="showDisconnectModal = false">

                        {{-- Modal Header --}}
                        <div class="px-6 py-5 border-b border-red-100 bg-gradient-to-r from-red-50 to-rose-50">
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0 h-10 w-10 rounded-xl bg-red-100 flex items-center justify-center">
                                    <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-extrabold text-red-800">Disconnect Service</h3>
                                    <p class="text-xs text-red-600">This will cut off water supply to this consumer</p>
                                </div>
                            </div>
                        </div>

                        {{-- Modal Body --}}
                        <form action="{{ route('consumers.disconnect', $consumer) }}" method="POST">
                            @csrf
                            <div class="px-6 py-5 space-y-4">
                                <div class="rounded-xl bg-red-50 border border-red-100 p-3">
                                    <p class="text-xs text-red-700 font-semibold">
                                        ⚠️ You are about to disconnect the water service for
                                        <strong>{{ $consumer->first_name }} {{ $consumer->last_name }}</strong>
                                        (Account: {{ $consumer->account_number }}).
                                    </p>
                                </div>

                                <div>
                                    <label for="disconnect_reason" class="block text-sm font-bold text-slate-700 mb-1.5">Reason for Disconnection <span class="text-red-500">*</span></label>
                                    <select name="reason" id="disconnect_reason" required
                                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-700 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
                                        <option value="">Select reason...</option>
                                        <option value="Non-payment of bills">Non-payment of bills</option>
                                        <option value="Overdue account - more than 3 months">Overdue account — more than 3 months</option>
                                        <option value="Violation of water usage policy">Violation of water usage policy</option>
                                        <option value="Consumer request">Consumer request</option>
                                        <option value="Illegal connection detected">Illegal connection detected</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="disconnect_notes" class="block text-sm font-bold text-slate-700 mb-1.5">Additional Notes</label>
                                    <textarea name="notes" id="disconnect_notes" rows="3"
                                              class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-700 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors resize-none"
                                              placeholder="Add any additional notes or details..."></textarea>
                                </div>
                            </div>

                            {{-- Modal Footer --}}
                            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-end gap-3">
                                <button type="button" @click="showDisconnectModal = false"
                                        class="px-4 py-2.5 text-sm font-bold text-slate-600 bg-white hover:bg-slate-50 rounded-xl border border-slate-200 transition-colors">
                                    Cancel
                                </button>
                                <button type="submit"
                                        class="px-5 py-2.5 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-sm transition-all duration-200">
                                    Confirm Disconnection
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- RECONNECT MODAL --}}
                {{-- ============================================ --}}
                <div x-cloak x-show="showReconnectModal"
                     class="fixed inset-0 z-50 flex items-center justify-center p-4"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0">
                    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showReconnectModal = false"></div>
                    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md border border-slate-200 overflow-hidden"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                         @click.away="showReconnectModal = false">

                        {{-- Modal Header --}}
                        <div class="px-6 py-5 border-b border-emerald-100 bg-gradient-to-r from-emerald-50 to-green-50">
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0 h-10 w-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                                    <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-extrabold text-emerald-800">Reconnect Service</h3>
                                    <p class="text-xs text-emerald-600">This will restore water supply to this consumer</p>
                                </div>
                            </div>
                        </div>

                        {{-- Modal Body --}}
                        <form action="{{ route('consumers.reconnect', $consumer) }}" method="POST">
                            @csrf
                            <div class="px-6 py-5 space-y-4">
                                <div class="rounded-xl bg-emerald-50 border border-emerald-100 p-3">
                                    <p class="text-xs text-emerald-700 font-semibold">
                                        ✅ You are about to reconnect the water service for
                                        <strong>{{ $consumer->first_name }} {{ $consumer->last_name }}</strong>
                                        (Account: {{ $consumer->account_number }}).
                                    </p>
                                </div>

                                <div>
                                    <label for="reconnect_reason" class="block text-sm font-bold text-slate-700 mb-1.5">Reason for Reconnection <span class="text-red-500">*</span></label>
                                    <select name="reason" id="reconnect_reason" required
                                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-700 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
                                        <option value="">Select reason...</option>
                                        <option value="Full payment of outstanding balance">Full payment of outstanding balance</option>
                                        <option value="Payment arrangement settled">Payment arrangement settled</option>
                                        <option value="Administrative correction">Administrative correction</option>
                                        <option value="Consumer request approved">Consumer request approved</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="reconnect_notes" class="block text-sm font-bold text-slate-700 mb-1.5">Additional Notes</label>
                                    <textarea name="notes" id="reconnect_notes" rows="3"
                                              class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-700 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors resize-none"
                                              placeholder="Add any additional notes or details..."></textarea>
                                </div>
                            </div>

                            {{-- Modal Footer --}}
                            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-end gap-3">
                                <button type="button" @click="showReconnectModal = false"
                                        class="px-4 py-2.5 text-sm font-bold text-slate-600 bg-white hover:bg-slate-50 rounded-xl border border-slate-200 transition-colors">
                                    Cancel
                                </button>
                                <button type="submit"
                                        class="px-5 py-2.5 text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-sm transition-all duration-200">
                                    Confirm Reconnection
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ================================================ --}}
            {{-- SERVICE STATUS HISTORY --}}
            {{-- ================================================ --}}
            @if($serviceStatusHistory->count() > 0)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Status History</h2>
                </div>
                <div class="p-4 space-y-3">
                    @foreach($serviceStatusHistory as $statusRecord)
                        <div class="flex items-start gap-3 p-3 rounded-xl {{ $statusRecord->action_type === 'disconnection' ? 'bg-red-50/50 border border-red-100' : 'bg-emerald-50/50 border border-emerald-100' }}">
                            <div class="flex-shrink-0 mt-0.5">
                                @if($statusRecord->action_type === 'disconnection')
                                    <div class="h-7 w-7 rounded-lg bg-red-100 flex items-center justify-center">
                                        <svg class="h-3.5 w-3.5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                        </svg>
                                    </div>
                                @else
                                    <div class="h-7 w-7 rounded-lg bg-emerald-100 flex items-center justify-center">
                                        <svg class="h-3.5 w-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2">
                                    <p class="text-xs font-bold {{ $statusRecord->action_type === 'disconnection' ? 'text-red-700' : 'text-emerald-700' }}">
                                        {{ $statusRecord->action_type === 'disconnection' ? 'Disconnected' : 'Reconnected' }}
                                    </p>
                                    <p class="text-[10px] text-slate-400 font-semibold flex-shrink-0">
                                        {{ \Carbon\Carbon::parse($statusRecord->status_date)->format('M d, Y') }}
                                    </p>
                                </div>
                                <p class="text-xs text-slate-600 mt-0.5 truncate">{{ $statusRecord->reason }}</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">By: {{ $statusRecord->processedBy->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
