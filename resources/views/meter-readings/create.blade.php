@extends('layouts.app')

@section('title', 'Record Meter Reading')

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="meterReadingApp()">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 shadow-sm" x-data="{ show: true }" x-show="show" x-transition>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0 h-8 w-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                        <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    </div>
                    <p class="text-sm font-semibold text-emerald-800">{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
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

    {{-- PAGE HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('meter-readings.index') }}" class="flex-shrink-0 h-10 w-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-all duration-200">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">Meter Reading Entry</h1>
                <p class="text-sm text-slate-500 font-medium">Search the consumer to open the reading entry widget</p>
            </div>
        </div>
        <div class="text-right">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Billing Period</p>
            <p class="text-sm font-bold text-blue-600">{{ now()->format('F Y') }}</p>
        </div>
    </div>

    {{-- MAIN CONTENT: SEARCH --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-visible relative">
        <div style="padding: 24px 32px; background: linear-gradient(to right, #0891b2, #1d4ed8); border-top-left-radius: 1.5rem; border-top-right-radius: 1.5rem;">
            <h2 style="color: white; font-weight: 800; font-size: 20px; display: flex; align-items: center; gap: 12px;">
                <svg style="width:28px;height:28px; color: rgba(255,255,255,0.8);" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                Find Consumer
            </h2>
        </div>
        
        <div class="p-8">
            <label class="block text-sm font-bold text-slate-700 mb-3">
                Select a consumer by Name, Account Number, or Meter Number to begin:
            </label>

            {{-- Search Input Wrapper --}}
            <div style="position: relative;" @click.away="showResults = false" class="max-w-2xl">
                <div style="position: relative;">
                    <div style="position: absolute; left: 20px; top: 50%; transform: translateY(-50%); pointer-events: none; z-index: 1;">
                        <svg style="width: 24px; height: 24px; color: #94a3b8;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    
                    <input type="text"
                           x-model="searchQuery"
                           @input="filterConsumers()"
                           @focus="showResults = true"
                           class="w-full py-4 rounded-xl border border-slate-300 bg-slate-50/50 text-base font-bold text-slate-800 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 hover:border-slate-400 transition-all"
                           style="padding-left: 56px; padding-right: 56px; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);"
                           placeholder="Type to search consumers..."
                           autocomplete="off">
                           
                    <button x-show="searchQuery" @click="clearSelection()" type="button"
                            class="text-slate-400 hover:text-red-500 transition-colors"
                            style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer;">
                        <svg style="width: 24px; height: 24px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                {{-- Results Dropdown --}}
                <div x-show="showResults && filteredConsumers.length > 0" x-cloak
                     style="position: absolute; z-index: 50; width: 100%; margin-top: 12px; background: white; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 20px 40px -10px rgba(0,0,0,0.2); max-height: 480px; overflow-y: auto;">
                    <template x-for="consumer in filteredConsumers" :key="consumer.id">
                        <button type="button" @click="selectConsumer(consumer)"
                                style="width: 100%; text-align: left; padding: 16px 20px; border-bottom: 1px solid #f1f5f9; cursor: pointer; display: block; background: none; border: none; transition: background 0.15s;"
                                onmouseover="this.style.background='#eff6ff'" onmouseout="this.style.background='white'">
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px;">
                                <div style="display: flex; align-items: center; gap: 16px; min-width: 0;">
                                    <div style="flex-shrink: 0; width: 44px; height: 44px; border-radius: 12px; background: #f8fafc; display: flex; align-items: center; justify-content: center; border: 1px solid #e2e8f0;">
                                        <svg style="width: 22px; height: 22px; color: #475569;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div style="min-width: 0;">
                                        <p style="font-size: 16px; font-weight: 800; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" x-text="consumer.full_name"></p>
                                        <p style="font-size: 13px; color: #64748b; margin-top: 2px;">
                                            <strong style="color:#334155;" x-text="'Meter #' + consumer.meter_number"></strong>
                                            <span style="margin: 0 6px;">•</span>
                                            <span x-text="consumer.location"></span>
                                        </p>
                                    </div>
                                </div>
                                <div style="flex-shrink: 0; display: flex; flex-direction: column; align-items: flex-end; gap: 6px;">
                                    <span style="font-size: 12px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em;" x-text="consumer.account_number"></span>
                                    <span x-show="consumer.has_reading_this_month"
                                          style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 999px; font-size: 11px; font-weight: 800; background: #fef3c7; color: #b45309; border: 1px solid #fcd34d;">
                                        ● Has reading
                                    </span>
                                </div>
                            </div>
                        </button>
                    </template>
                </div>

                {{-- No results state --}}
                <div x-show="showResults && filteredConsumers.length === 0 && searchQuery.length >= 1" x-cloak
                     style="position: absolute; z-index: 50; width: 100%; margin-top: 12px; background: white; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 20px 40px -10px rgba(0,0,0,0.2); padding: 40px; text-align: center;">
                    <svg style="width: 56px; height: 56px; margin: 0 auto 16px; color: #cbd5e1;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <p style="font-size: 16px; font-weight: 800; color: #334155;">No consumers found</p>
                    <p style="font-size: 14px; color: #94a3b8; margin-top: 4px;">Try checking the spelling or use the exact account number.</p>
                </div>
            </div>
            
            {{-- Validation Error (if trying to submit without modal somehow) --}}
            @error('consumer_id')
                <p class="mt-3 text-sm text-red-600 font-bold bg-red-50 p-3 rounded-lg max-w-2xl border border-red-100 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $message }}
                </p>
            @enderror
            
            {{-- Info / Instructions --}}
            <div class="mt-10 p-5 rounded-2xl bg-blue-50/50 border border-blue-100/50 flex gap-4 max-w-2xl">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-lg">!</div>
                <div>
                    <h3 class="font-bold text-slate-800 mb-1">Quick Instructions</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">Type the name or account number in the search bar. Click the exact match from the dropdown to open the interactive meter reading form.</p>
                </div>
            </div>
        </div>
    </div>


    {{-- ======================================================== --}}
    {{-- MODAL: METER READING ENTRY                               --}}
    {{-- ======================================================== --}}
    <div x-show="showModal" x-cloak style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        
        <!-- Backdrop -->
        <div x-show="showModal" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" 
             @click="closeModal()"></div>
             
        <!-- Modal Panel -->
        <div x-show="showModal" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative w-full max-w-2xl bg-slate-50 rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] mx-auto border border-white/50">
             
            <!-- Form Wrapper inside modal to capture inputs correctly -->
            <form action="{{ route('meter-readings.store') }}" method="POST" @submit="handleSubmit($event)" class="flex flex-col h-full max-h-[90vh]">
                @csrf
                <input type="hidden" name="consumer_id" x-model="consumerId">

                <!-- MODAL HEADER -->
                <div style="background: linear-gradient(to right, #0891b2, #1d4ed8);" class="px-6 py-4 flex items-center justify-between shadow-sm relative z-10">
                     <h2 class="text-xl font-bold text-white flex items-center gap-3">
                         <svg class="w-6 h-6 text-cyan-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                         Submit Meter Reading
                     </h2>
                     <button type="button" @click="closeModal()" class="text-white/70 hover:text-white transition-colors bg-white/10 hover:bg-white/20 rounded-full p-1.5 focus:outline-none">
                         <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                     </button>
                </div>

                <!-- MODAL BODY -->
                <div class="px-6 py-5 overflow-y-auto flex-1">
                    
                    <!-- Loading State -->
                    <div x-show="!consumerData" class="flex flex-col items-center justify-center py-16">
                        <svg class="animate-spin h-10 w-10 text-blue-600 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="text-slate-500 font-bold">Loading consumer profile...</p>
                    </div>

                    <!-- Content State -->
                    <div x-show="consumerData" class="space-y-6">
                        
                        <!-- Duplicate Warning -->
                        <div x-show="selectedConsumer?.has_reading_this_month" x-cloak
                             class="rounded-xl border border-amber-200 bg-amber-50 p-4 shadow-sm relative overflow-hidden">
                            <div class="absolute right-0 top-0 h-full w-2 bg-amber-400"></div>
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 mt-0.5">
                                    <svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-amber-900 leading-none mb-1.5">Already has a reading this month</p>
                                    <p class="text-xs font-medium text-amber-700">A meter reading for <strong>{{ now()->format('F Y') }}</strong> has already been submitted for this account. You may be creating a duplicate.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Consumer Header Card -->
                        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col md:flex-row divide-y md:divide-y-0 md:divide-x divide-slate-100">
                            <div class="p-4 flex-1">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Consumer Details</p>
                                <p class="text-lg font-extrabold text-slate-800 truncate" x-text="consumerData?.consumer?.first_name + ' ' + consumerData?.consumer?.last_name"></p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200" x-text="'Acc: ' + consumerData?.consumer?.account_number"></span>
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold capitalize bg-blue-50 text-blue-700 border border-blue-100" x-text="consumerData?.consumer?.connection_type"></span>
                                </div>
                            </div>
                            <div class="p-4 bg-slate-50/50 flex flex-col justify-center min-w-[160px]">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Previous Reading</p>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-2xl font-black text-blue-600" x-text="(consumerData?.previous_reading || 0).toFixed(2)">0.00</span>
                                    <span class="text-sm font-bold text-blue-400">m³</span>
                                </div>
                            </div>
                        </div>

                        <!-- Input Section -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="current_reading" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                    New Reading (m³) <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                                    </div>
                                    <input type="number" name="current_reading" id="current_reading" step="0.01" min="0" required
                                           x-model="currentReading" @input="calculateConsumption()" x-ref="readingInput"
                                           value="{{ old('current_reading') }}"
                                           class="form-input w-full pl-11 pr-4 py-3.5 rounded-xl border border-slate-300 bg-white text-lg font-bold text-slate-800 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-sm"
                                           placeholder="0.00">
                                </div>
                            </div>
                            
                            <div>
                                <label for="reading_date" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                    Reading Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="reading_date" id="reading_date" required
                                       value="{{ old('reading_date', date('Y-m-d')) }}"
                                       class="form-input w-full px-4 py-3.5 rounded-xl border border-slate-300 bg-white text-sm font-bold text-slate-800 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-sm">
                            </div>
                        </div>

                        <!-- Real-time Validation & Estimate Box -->
                        <div x-show="currentReading !== ''" x-transition class="mt-4">
                            <!-- Show warning if current reading is lower than previous -->
                            <div x-show="currentReading !== '' && parseFloat(currentReading) < parseFloat(consumerData?.previous_reading || 0)" class="p-4 bg-red-50 border border-red-200 rounded-xl mb-4 flex items-start gap-3">
                                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <div>
                                    <p class="text-sm font-bold text-red-800">Invalid Reading</p>
                                    <p class="text-xs text-red-600 font-medium">The new reading must be strictly greater than or equal to the previous reading.</p>
                                </div>
                            </div>
                            
                            <!-- Successful input estimate -->
                            <div x-show="currentReading !== '' && parseFloat(currentReading) >= parseFloat(consumerData?.previous_reading || 0)" class="bg-blue-50 border border-blue-100 rounded-xl p-5 shadow-inner">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-[10px] font-extrabold text-blue-500 uppercase tracking-widest mb-1">Calculated Consumption</p>
                                        <div class="flex items-baseline gap-1.5">
                                            <span class="text-3xl font-black text-slate-800" x-text="consumption.toFixed(2)">0.00</span>
                                            <span class="text-sm font-bold text-slate-500">m³ totally used</span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[10px] font-extrabold text-blue-500 uppercase tracking-widest mb-1">Estimated Bill Amount</p>
                                        <div class="flex items-baseline gap-1">
                                            <span class="text-lg font-bold text-slate-600">₱</span>
                                            <span class="text-2xl font-black text-emerald-600" x-text="totalAmount.toFixed(2)">0.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes Field -->
                        <div>
                            <label for="notes" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Remarks / Notes (Optional)</label>
                            <textarea name="notes" id="notes" rows="2"
                                      class="form-textarea w-full px-4 py-3 rounded-xl border border-slate-300 bg-white text-sm font-medium text-slate-700 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all resize-none shadow-sm"
                                      placeholder="Any anomalies or notes to record for this read..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- MODAL FOOTER -->
                <div class="px-6 py-4 border-t border-slate-200 bg-white flex flex-col sm:flex-row items-center justify-end gap-3 z-10">
                    <button type="button" @click="closeModal()" class="w-full sm:w-auto px-6 py-3 text-sm font-bold text-slate-600 bg-white hover:bg-slate-50 border border-slate-300 rounded-xl shadow-sm transition-colors focus:ring-4 focus:ring-slate-100">
                        Cancel
                    </button>
                    <!-- Submit button disabled if invalid reading -->
                    <button type="submit" 
                            :disabled="currentReading !== '' && parseFloat(currentReading) < parseFloat(consumerData?.previous_reading || 0)"
                            :class="(currentReading !== '' && parseFloat(currentReading) < parseFloat(consumerData?.previous_reading || 0)) ? 'opacity-50 cursor-not-allowed' : 'hover:shadow-lg hover:opacity-95'"
                            style="background: linear-gradient(to right, #0891b2, #1d4ed8);"
                            class="w-full sm:w-auto px-8 py-3 text-sm font-bold text-white rounded-xl shadow-md transition-all flex items-center justify-center gap-2 focus:ring-4 focus:ring-blue-500/30">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                        Submit Reading Record
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function meterReadingApp() {
    const allConsumers = @json($consumers);

    return {
        searchQuery: '',
        allConsumers: allConsumers,
        filteredConsumers: allConsumers.slice(0, 10),
        showResults: false,
        
        showModal: false,

        consumerId: '',
        selectedConsumer: null,
        consumerData: null,

        currentReading: '',
        consumption: 0,
        excessConsumption: 0,
        ratePerUnit: 0,
        excessCharge: 0,
        totalAmount: 0,

        filterConsumers() {
            const q = this.searchQuery.toLowerCase().trim();

            if (q.length === 0) {
                this.filteredConsumers = this.allConsumers.slice(0, 10);
                this.showResults = true;
                return;
            }

            this.filteredConsumers = this.allConsumers.filter(c => {
                return c.full_name.toLowerCase().includes(q) ||
                       c.account_number.toLowerCase().includes(q) ||
                       c.meter_number.toLowerCase().includes(q) ||
                       c.location.toLowerCase().includes(q);
            }).slice(0, 10);

            this.showResults = true;
        },

        async selectConsumer(consumer) {
            this.selectedConsumer = consumer;
            this.consumerId = consumer.id;
            this.searchQuery = consumer.full_name + ' — ' + consumer.account_number;
            this.showResults = false;
            
            // Open modal and reset fields
            this.currentReading = '';
            this.consumption = 0;
            this.totalAmount = 0;
            this.consumerData = null; // show loading spinner
            this.showModal = true;
            
            await this.fetchConsumerDetails();
            
            // Focus the input specifically after modal opens
            setTimeout(() => {
                const input = this.$refs.readingInput;
                if (input) input.focus();
            }, 300);
        },

        async fetchConsumerDetails() {
            if (!this.consumerId) return;
            try {
                const response = await fetch('/meter-readings/consumer/' + this.consumerId);
                this.consumerData = await response.json();
                this.calculateConsumption(); // Will be 0 initially
            } catch (error) {
                console.error('Error fetching consumer details:', error);
            }
        },

        calculateConsumption() {
            if (!this.consumerData) return;
            const previous = parseFloat(this.consumerData.previous_reading || 0);
            const current = parseFloat(this.currentReading);
            
            // If current reading is blank or less than previous, consumption is 0 visually
            if (isNaN(current) || current < previous) {
                this.consumption = 0;
                this.totalAmount = 0;
                return;
            }
            
            this.consumption = current - previous;
            this.calculateBilling();
        },

        calculateBilling() {
            const MINIMUM_CHARGE = 200;
            const MINIMUM_CONSUMPTION = 10;
            const RESIDENTIAL_RATE = 15;
            const COMMERCIAL_RATE = 20;

            const connectionType = this.consumerData?.consumer?.connection_type?.toLowerCase();
            this.ratePerUnit = connectionType === 'commercial' ? COMMERCIAL_RATE : RESIDENTIAL_RATE;
            this.excessConsumption = Math.max(this.consumption - MINIMUM_CONSUMPTION, 0);
            this.excessCharge = this.excessConsumption * this.ratePerUnit;

            if (this.consumption <= MINIMUM_CONSUMPTION) {
                this.totalAmount = MINIMUM_CHARGE;
            } else {
                this.totalAmount = MINIMUM_CHARGE + this.excessCharge;
            }
        },
        
        closeModal() {
            this.showModal = false;
        },

        clearSelection() {
            this.searchQuery = '';
            this.filteredConsumers = this.allConsumers.slice(0, 10);
            this.showResults = false;
            this.consumerId = '';
            this.selectedConsumer = null;
            this.consumerData = null;
            this.currentReading = '';
            this.consumption = 0;
            this.totalAmount = 0;
            this.showModal = false;
        },

        handleSubmit(event) {
            if (!this.consumerId) {
                event.preventDefault();
                alert('Please select a consumer first.');
                return false;
            }
            
            const current = parseFloat(this.currentReading);
            const prev = parseFloat(this.consumerData?.previous_reading || 0);
            
            if (isNaN(current) || current < prev) {
                event.preventDefault();
                alert('Invalid reading! Reading cannot be lower than the previous reading.');
                return false;
            }
            
            return true;
        }
    }
}
</script>
@endsection