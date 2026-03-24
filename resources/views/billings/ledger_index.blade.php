@extends('layouts.app')

@section('title', 'Accounts Ledger')

@section('content')
<div class="space-y-6">

    {{-- ============================================================ --}}
    {{-- BREADCRUMB + PAGE TITLE                                       --}}
    {{-- ============================================================ --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <nav class="flex items-center gap-1.5 text-sm text-slate-400 mb-1">
                <span class="text-slate-600 font-medium">Billing & Payments</span>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-slate-600 font-medium">Accounts Ledger</span>
            </nav>
            <h1 class="text-2xl font-bold text-slate-800">Accounts Ledger</h1>
            <p class="text-sm text-slate-400 mt-0.5">Select a consumer to view their financial history and statements</p>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- 🔍 TOP SECTION (SEARCH + ACTIONS)                            --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5">
        <form action="{{ route('billing.ledger') }}" method="GET">
            <div class="flex flex-col sm:flex-row gap-4 mb-4">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ $search }}"
                        placeholder="Search by Consumer name or account number..."
                        class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm px-5 py-2 rounded-lg transition-colors duration-150 flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Search
                    </button>
                </div>
            </div>

            {{-- 🎛 FILTER SECTION --}}
            <div class="flex flex-wrap gap-3 mt-3 pt-3 border-t border-slate-100">
                <select name="connection_type" class="text-sm rounded-lg border border-slate-200 bg-white px-3 py-2 text-slate-600 focus:ring-2 focus:ring-blue-500">
                    <option value="">All Connection Types</option>
                    <option value="residential" {{ $connectionType === 'residential' ? 'selected' : '' }}>Residential</option>
                    <option value="commercial" {{ $connectionType === 'commercial' ? 'selected' : '' }}>Commercial</option>
                    <option value="industrial" {{ $connectionType === 'industrial' ? 'selected' : '' }}>Industrial</option>
                    <option value="institutional" {{ $connectionType === 'institutional' ? 'selected' : '' }}>Institutional</option>
                </select>
                <select name="status" class="text-sm rounded-lg border border-slate-200 bg-white px-3 py-2 text-slate-600 focus:ring-2 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="disconnected" {{ $status === 'disconnected' ? 'selected' : '' }}>Disconnected</option>
                </select>
                @if($search || $connectionType || $status)
                    <a href="{{ route('billing.ledger') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium px-3 py-2 transition self-center">
                        Clear Filters
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ============================================================ --}}
    {{-- 👥 CONSUMERS TABLE SECTION                                    --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center gap-2">
            <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <h3 class="text-base font-bold text-slate-800">Consumers List</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-6 py-3 text-left font-bold uppercase text-slate-400 tracking-wider text-[11px]">Account No.</th>
                        <th class="px-6 py-3 text-left font-bold uppercase text-slate-400 tracking-wider text-[11px]">Consumer Name</th>
                        <th class="px-6 py-3 text-left font-bold uppercase text-slate-400 tracking-wider text-[11px]">Type</th>
                        <th class="px-6 py-3 text-center font-bold uppercase text-slate-400 tracking-wider text-[11px]">Status</th>
                        <th class="px-6 py-3 text-right font-bold uppercase text-slate-400 tracking-wider text-[11px]">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($consumers as $consumer)
                        <tr class="hover:bg-blue-50/30 transition-colors duration-150 cursor-pointer" onclick="window.location='{{ route('billing.ledger.show', $consumer) }}'">
                            <td class="px-6 py-4 whitespace-nowrap font-semibold text-blue-600">
                                {{ $consumer->account_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-800 flex items-center gap-3">
                                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-xs flex-shrink-0">
                                    {{ strtoupper(substr($consumer->first_name, 0, 1)) }}
                                </div>
                                {{ $consumer->first_name }} {{ $consumer->last_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-600">
                                {{ ucfirst($consumer->connection_type) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @php
                                    $sc = $consumer->connection_status === 'active' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200';
                                @endphp
                                <span class="inline-flex items-center text-[11px] font-bold uppercase px-2 py-0.5 rounded-full border {{ $sc }}">
                                    {{ $consumer->connection_status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <a href="{{ route('billing.ledger.show', $consumer) }}" class="text-blue-600 hover:text-blue-800 font-semibold inline-flex items-center gap-1">
                                    View Ledger
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="h-10 w-10 text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    No consumers found matching your search.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($consumers->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $consumers->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
