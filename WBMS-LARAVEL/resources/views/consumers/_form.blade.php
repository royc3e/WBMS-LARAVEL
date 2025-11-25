@csrf

@if(isset($consumer) && $consumer->exists)
    @method('PUT')
@endif

<div class="bg-white dark:bg-slate-800 shadow sm:rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
            {{ isset($consumer) ? 'Edit Consumer' : 'Add New Consumer' }}
        </h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Fill in the details below to {{ isset($consumer) ? 'update' : 'create' }} a consumer.
        </p>

        <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
            <!-- Account Information -->
            <div class="sm:col-span-6 border-b border-gray-200 dark:border-slate-700 pb-6">
                <h4 class="text-md font-medium text-gray-900 dark:text-white">Account Information</h4>
            </div>

            <div class="sm:col-span-2">
                <label for="account_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Account Number
                </label>
                <div class="mt-1">
                    <input
                        type="text"
                        name="account_number"
                        id="account_number"
                        value="{{ old('account_number', $consumer->account_number ?? '') }}"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-slate-700 rounded-md dark:bg-slate-800 dark:text-white"
                        placeholder="Leave blank to auto-generate"
                    >
                    @error('account_number')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="sm:col-span-2">
                <label for="connection_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Connection Type
                </label>
                <div class="mt-1">
                    <select
                        id="connection_type"
                        name="connection_type"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-slate-700 rounded-md dark:bg-slate-800 dark:text-white"
                    >
                        @foreach([
                            'residential' => 'Residential',
                            'commercial' => 'Commercial',
                            'industrial' => 'Industrial',
                            'government' => 'Government',
                        ] as $value => $label)
                            <option value="{{ $value }}" {{ old('connection_type', $consumer->connection_type ?? '') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('connection_type')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="sm:col-span-2">
                <label for="connection_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Status
                </label>
                <div class="mt-1">
                    <select
                        id="connection_status"
                        name="connection_status"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-slate-700 rounded-md dark:bg-slate-800 dark:text-white"
                    >
                        @foreach([
                            'active' => 'Active',
                            'inactive' => 'Inactive',
                            'disconnected' => 'Disconnected',
                            'pending' => 'Pending',
                        ] as $value => $label)
                            <option value="{{ $value }}" {{ old('connection_status', $consumer->connection_status ?? 'active') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('connection_status')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Personal Information -->
            <div class="sm:col-span-6 border-b border-gray-200 dark:border-slate-700 pb-6 mt-6">
                <h4 class="text-md font-medium text-gray-900 dark:text-white">Personal Information</h4>
            </div>

            <div class="sm:col-span-2">
                <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    First Name *
                </label>
                <div class="mt-1">
                    <input
                        type="text"
                        name="first_name"
                        id="first_name"
                        value="{{ old('first_name', $consumer->first_name ?? '') }}"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-slate-700 rounded-md dark:bg-slate-800 dark:text-white"
                        required
                    >
                    @error('first_name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="sm:col-span-2">
                <label for="middle_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Middle Name
                </label>
                <div class="mt-1">
                    <input
                        type="text"
                        name="middle_name"
                        id="middle_name"
                        value="{{ old('middle_name', $consumer->middle_name ?? '') }}"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-slate-700 rounded-md dark:bg-slate-800 dark:text-white"
                    >
                    @error('middle_name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="sm:col-span-2">
                <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Last Name *
                </label>
                <div class="mt-1">
                    <input
                        type="text"
                        name="last_name"
                        id="last_name"
                        value="{{ old('last_name', $consumer->last_name ?? '') }}"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-slate-700 rounded-md dark:bg-slate-800 dark:text-white"
                        required
                    >
                    @error('last_name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="sm:col-span-3">
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Email Address
                </label>
                <div class="mt-1">
                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="{{ old('email', $consumer->email ?? '') }}"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-slate-700 rounded-md dark:bg-slate-800 dark:text-white"
                    >
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="sm:col-span-3">
                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Phone Number
                </label>
                <div class="mt-1">
                    <input
                        type="tel"
                        name="phone"
                        id="phone"
                        value="{{ old('phone', $consumer->phone ?? '') }}"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-slate-700 rounded-md dark:bg-slate-800 dark:text-white"
                    >
                    @error('phone')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Address Information -->
            <div class="sm:col-span-6 border-b border-gray-200 dark:border-slate-700 pb-6 mt-6">
                <h4 class="text-md font-medium text-gray-900 dark:text-white">Address Information</h4>
            </div>

            <div class="sm:col-span-4">
                <label for="address_line_1" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Street Address *
                </label>
                <div class="mt-1">
                    <input
                        type="text"
                        name="address_line_1"
                        id="address_line_1"
                        value="{{ old('address_line_1', $consumer->address_line_1 ?? '') }}"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-slate-700 rounded-md dark:bg-slate-800 dark:text-white"
                        required
                    >
                    @error('address_line_1')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="sm:col-span-2">
                <label for="address_line_2" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Apt/Suite/Unit
                </label>
                <div class="mt-1">
                    <input
                        type="text"
                        name="address_line_2"
                        id="address_line_2"
                        value="{{ old('address_line_2', $consumer->address_line_2 ?? '') }}"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-slate-700 rounded-md dark:bg-slate-800 dark:text-white"
                    >
                    @error('address_line_2')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="sm:col-span-2">
                <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    City *
                </label>
                <div class="mt-1">
                    <input
                        type="text"
                        name="city"
                        id="city"
                        value="{{ old('city', $consumer->city ?? '') }}"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-slate-700 rounded-md dark:bg-slate-800 dark:text-white"
                        required
                    >
                    @error('city')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="sm:col-span-2">
                <label for="state" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    State/Province *
                </label>
                <div class="mt-1">
                    <input
                        type="text"
                        name="state"
                        id="state"
                        value="{{ old('state', $consumer->state ?? '') }}"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-slate-700 rounded-md dark:bg-slate-800 dark:text-white"
                        required
                    >
                    @error('state')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="sm:col-span-2">
                <label for="postal_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    ZIP/Postal Code *
                </label>
                <div class="mt-1">
                    <input
                        type="text"
                        name="postal_code"
                        id="postal_code"
                        value="{{ old('postal_code', $consumer->postal_code ?? '') }}"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-slate-700 rounded-md dark:bg-slate-800 dark:text-white"
                        required
                    >
                    @error('postal_code')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Connection Information -->
            <div class="sm:col-span-6 border-b border-gray-200 dark:border-slate-700 pb-6 mt-6">
                <h4 class="text-md font-medium text-gray-900 dark:text-white">Connection Information</h4>
            </div>

            <div class="sm:col-span-2">
                <label for="meter_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Meter Number
                </label>
                <div class="mt-1">
                    <input
                        type="text"
                        name="meter_number"
                        id="meter_number"
                        value="{{ old('meter_number', $consumer->meter_number ?? '') }}"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-slate-700 rounded-md dark:bg-slate-800 dark:text-white"
                    >
                    @error('meter_number')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="sm:col-span-2">
                <label for="connection_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Connection Date *
                </label>
                <div class="mt-1">
                    <input
                        type="date"
                        name="connection_date"
                        id="connection_date"
                        value="{{ old('connection_date', isset($consumer->connection_date) ? \Carbon\Carbon::parse($consumer->connection_date)->format('Y-m-d') : now()->format('Y-m-d')) }}"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-slate-700 rounded-md dark:bg-slate-800 dark:text-white"
                        required
                    >
                    @error('connection_date')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="sm:col-span-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Notes
                </label>
                <div class="mt-1">
                    <textarea
                        id="notes"
                        name="notes"
                        rows="3"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-slate-700 rounded-md dark:bg-slate-800 dark:text-white"
                    >{{ old('notes', $consumer->notes ?? '') }}</textarea>
                    @error('notes')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="px-4 py-3 bg-gray-50 dark:bg-slate-900 text-right sm:px-6 rounded-b-lg">
        <a href="{{ route('consumers.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white dark:hover:bg-slate-600">
            Cancel
        </a>
        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            {{ isset($consumer) ? 'Update Consumer' : 'Create Consumer' }}
        </button>
    </div>
</div>
