@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-4">
                <li>
                    <div>
                        <a href="{{ route('billings.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">Billings</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="ml-4 text-sm font-medium text-gray-500 dark:text-gray-400">Generate Billing</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="mt-4">
            <h1 class="text-3xl font-bold leading-tight text-gray-900 dark:text-white">Generate Billing</h1>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                Select how you would like to generate new billing records for consumers.
            </p>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-md bg-green-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (session('info'))
        <div class="mb-6 rounded-md bg-blue-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-4a1 1 0 100 2 1 1 0 000-2zm-1 4a1 1 0 012 0v4a1 1 0 11-2 0v-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-blue-800">{{ session('info') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Generate for All Active Consumers</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Create billing records for every active consumer in the system.
                    </p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-indigo-100 text-indigo-600 dark:bg-indigo-900 dark:text-indigo-300">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.051l-7.5 10a.75.75 0 01-1.116.094l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 6.98-9.307a.75.75 0 011.04-.171z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
            <dl class="mt-6 grid grid-cols-2 gap-4 text-sm">
                <div class="rounded-lg bg-gray-50 dark:bg-gray-900/40 p-4 border border-gray-100 dark:border-gray-700/40">
                    <dt class="text-gray-500 dark:text-gray-400">Active consumers</dt>
                    <dd class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($consumerCounts['active']) }}</dd>
                </div>
                <div class="rounded-lg bg-gray-50 dark:bg-gray-900/40 p-4 border border-gray-100 dark:border-gray-700/40">
                    <dt class="text-gray-500 dark:text-gray-400">Total consumers</dt>
                    <dd class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($consumerCounts['total']) }}</dd>
                </div>
            </dl>
            <div class="mt-6">
                <form action="{{ route('billings.generate.active') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="form_context" value="active">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="billing_month_active" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Billing month</label>
                            <input type="month" id="billing_month_active" name="billing_month" value="{{ old('form_context') === 'active' ? old('billing_month') : '' }}" required class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @error('billing_month')
                                @if(old('form_context') === 'active')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @endif
                            @enderror
                        </div>
                        <div>
                            <label for="due_date_active" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Due date</label>
                            <input type="date" id="due_date_active" name="due_date" value="{{ old('form_context') === 'active' ? old('due_date') : '' }}" required class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark-border-gray-600 dark-text-white">
                            @error('due_date')
                                @if(old('form_context') === 'active')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @endif
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none_focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Generate for active consumers
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Generate for Selected Consumers</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Choose specific consumers or import a list to generate billing records.
                    </p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-indigo-100 text-indigo-600 dark:bg-indigo-900 dark:text-indigo-300">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M3.5 3a.5.5 0 000 1h13a.5.5 0 000-1h-13zM3 6.5A1.5 1.5 0 014.5 5h11A1.5 1.5 0 0117 6.5v7a1.5 1.5 0 01-1.5 1.5H13l1.182 2.364A1 1 0 0113.276 19H6.724a1 1 0 01-.906-1.136L7 15H4.5A1.5 1.5 0 013 13.5v-7z" />
                    </svg>
                </div>
            </div>
            <dl class="mt-6 grid grid-cols-2 gap-4 text-sm">
                <div class="rounded-lg bg-gray-50 dark:bg-gray-900/40 p-4 border border-gray-100 dark:border-gray-700/40">
                    <dt class="text-gray-500 dark:text-gray-400">Inactive consumers</dt>
                    <dd class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($consumerCounts['inactive']) }}</dd>
                </div>
                <div class="rounded-lg bg-gray-50 dark:bg-gray-900/40 p-4 border border-gray-100 dark:border-gray-700/40">
                    <dt class="text-gray-500 dark:text-gray-400">Custom selection</dt>
                    <dd class="mt-1 text-sm text-gray-600 dark:text-gray-400">Upload CSV or pick individually</dd>
                </div>
            </dl>
            <div class="mt-6">
                <button type="button" x-data x-on:click="$dispatch('open-custom-generator')" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600">
                    Choose consumers
                </button>
            </div>
        </div>
    </div>

    <div class="mt-10 bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-100 dark:border-indigo-800 rounded-lg p-6">
        <h3 class="text-sm font-semibold text-indigo-900 dark:text-indigo-200 uppercase tracking-wide">How generation works</h3>
        <div class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-indigo-900/80 dark:text-indigo-100/80">
            <div class="flex items-start gap-3">
                <span class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-white text-indigo-600 font-semibold shadow-sm">1</span>
                <p>Create or confirm the billing period and meter readings that will be used.</p>
            </div>
            <div class="flex items-start gap-3">
                <span class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-white text-indigo-600 font-semibold shadow-sm">2</span>
                <p>Pick whether to generate for everyone or target specific consumer groups.</p>
            </div>
            <div class="flex items-start gap-3">
                <span class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-white text-indigo-600 font-semibold shadow-sm">3</span>
                <p>Review the generated preview and confirm to create the billing records.</p>
            </div>
        </div>
    </div>

    <div x-data="{ open: {{ old('form_context') === 'custom' ? 'true' : 'false' }} }"
         x-on:open-custom-generator.window="open = true"
         x-show="open"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/75 p-4"
         style="display: none;">
        <div x-on:click.away="open = false" class="w-full max-w-3xl bg-white dark:bg-gray-800 rounded-xl shadow-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Generate for selected consumers</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Pick the consumers you need and configure billing details.</p>
                </div>
                <button type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200" x-on:click="open = false">
                    <span class="sr-only">Close</span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('billings.generate.selected') }}" method="POST" class="px-6 py-5 space-y-6">
                @csrf
                <input type="hidden" name="form_context" value="custom">
                @if ($errors->any() && old('form_context') === 'custom')
                    <div class="rounded-md bg-red-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-4a1 1 0 112 0 1 1 0 01-2 0zm.25-8.75a.75.75 0 00-1.5 0v5.5a.75.75 0 001.5 0v-5.5z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Please fix the highlighted issues below.</h3>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="billing_month_custom" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Billing month</label>
                        <input type="month" id="billing_month_custom" name="billing_month" value="{{ old('form_context') === 'custom' ? old('billing_month') : '' }}" required class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @error('billing_month')
                            @if(old('form_context') === 'custom')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @endif
                        @enderror
                    </div>
                    <div>
                        <label for="due_date_custom" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Due date</label>
                        <input type="date" id="due_date_custom" name="due_date" value="{{ old('form_context') === 'custom' ? old('due_date') : '' }}" required class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark-text-white">
                        @error('due_date')
                            @if(old('form_context') === 'custom')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @endif
                        @enderror
                    </div>
                    <div>
                        <label for="rate_per_unit_custom" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rate per unit</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 pl-3 flex items-center">
                                <span class="text-gray-500 sm:text-sm">₱</span>
                            </div>
                            <input type="number" step="0.01" min="0" id="rate_per_unit_custom" name="rate_per_unit" value="{{ old('form_context') === 'custom' ? old('rate_per_unit') : '' }}" required class="block w-full rounded-md border-gray-300 pl-7 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="0.00">
                        </div>
                        @error('rate_per_unit')
                            @if(old('form_context') === 'custom')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @endif
                        @enderror
                    </div>
                    <div>
                        <label for="consumer_ids" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select consumers</label>
                        <select id="consumer_ids" name="consumer_ids[]" multiple required class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white h-40">
                            @foreach(\App\Models\Consumer::orderBy('first_name')->get() as $consumer)
                                <option value="{{ $consumer->id }}" @if(old('form_context') === 'custom' && collect(old('consumer_ids', []))->contains($consumer->id)) selected @endif>{{ $consumer->full_name }} ({{ $consumer->account_number }})</option>
                            @endforeach
                        </select>
                        @error('consumer_ids')
                            @if(old('form_context') === 'custom')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @endif
                        @enderror
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Hold Ctrl (Windows) or Command (Mac) to select multiple consumers.</p>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Selected consumers will receive a new billing record using these settings.</p>
                    <div class="flex gap-3">
                        <button type="button" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600" x-on:click="open = false">
                            Cancel
                        </button>
                        <button type="submit" class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Generate selected billing
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
