import { useForm } from '@inertiajs/react';

export default function ConsumerForm({ consumer = null, connectionTypes = [], statuses = [] }) {
    const { data, setData, post, put, processing, errors } = useForm({
        account_number: consumer?.account_number || '',
        first_name: consumer?.first_name || '',
        middle_name: consumer?.middle_name || '',
        last_name: consumer?.last_name || '',
        email: consumer?.email || '',
        phone: consumer?.phone || '',
        address_line_1: consumer?.address_line_1 || '',
        address_line_2: consumer?.address_line_2 || '',
        city: consumer?.city || '',
        state: consumer?.state || '',
        postal_code: consumer?.postal_code || '',
        connection_type: consumer?.connection_type || 'residential',
        connection_status: consumer?.connection_status || 'active',
        meter_number: consumer?.meter_number || '',
        connection_date: consumer?.connection_date || new Date().toISOString().split('T')[0],
        notes: consumer?.notes || '',
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        
        if (consumer) {
            put(route('consumers.update', consumer.id));
        } else {
            post(route('consumers.store'));
        }
    };

    return (
        <form onSubmit={handleSubmit} className="space-y-6">
            <div className="bg-white dark:bg-slate-800 shadow sm:rounded-lg">
                <div className="px-4 py-5 sm:p-6">
                    <h3 className="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                        {consumer ? 'Edit Consumer' : 'Add New Consumer'}
                    </h3>
                    <p className="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Fill in the details below to {consumer ? 'update' : 'create'} a consumer.
                    </p>

                    <div className="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        {/* Account Information */}
                        <div className="sm:col-span-6 border-b border-gray-200 dark:border-slate-700 pb-6">
                            <h4 className="text-md font-medium text-gray-900 dark:text-white">Account Information</h4>
                        </div>

                        <div className="sm:col-span-2">
                            <label htmlFor="account_number" className="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Account Number
                            </label>
                            <div className="mt-1">
                                <input
                                    type="text"
                                    id="account_number"
                                    value={data.account_number}
                                    onChange={(e) => setData('account_number', e.target.value)}
                                    className="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-slate-700 rounded-md dark:bg-slate-800 dark:text-white"
                                    placeholder="Leave blank to auto-generate"
                                />
                                {errors.account_number && (
                                    <p className="mt-2 text-sm text-red-600">{errors.account_number}</p>
                                )}
                            </div>
                        </div>

                        <div className="sm:col-span-2">
                            <label htmlFor="connection_type" className="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Connection Type
                            </label>
                            <div className="mt-1">
                                <select
                                    id="connection_type"
                                    value={data.connection_type}
                                    onChange={(e) => setData('connection_type', e.target.value)}
                                    className="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-slate-700 rounded-md dark:bg-slate-800 dark:text-white"
                                >
                                    {Object.entries(connectionTypes).map(([value, label]) => (
                                        <option key={value} value={value}>
                                            {label}
                                        </option>
                                    ))}
                                </select>
                                {errors.connection_type && (
                                    <p className="mt-2 text-sm text-red-600">{errors.connection_type}</p>
                                )}
                            </div>
                        </div>

                        <div className="sm:col-span-2">
                            <label htmlFor="connection_status" className="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Status
                            </label>
                            <div className="mt-1">
                                <select
                                    id="connection_status"
                                    value={data.connection_status}
                                    onChange={(e) => setData('connection_status', e.target.value)}
                                    className="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-slate-700 rounded-md dark:bg-slate-800 dark:text-white"
                                >
                                    {Object.entries(statuses).map(([value, label]) => (
                                        <option key={value} value={value}>
                                            {label}
                                        </option>
                                    ))}
                                </select>
                                {errors.connection_status && (
                                    <p className="mt-2 text-sm text-red-600">{errors.connection_status}</p>
                                )}
                            </div>
                        </div>

                        {/* Personal Information */}
                        <div className="sm:col-span-6 border-b border-gray-200 dark:border-slate-700 pb-6 mt-6">
                            <h4 className="text-md font-medium text-gray-900 dark:text-white">Personal Information</h4>
                        </div>

                        <div className="sm:col-span-2">
                            <label htmlFor="first_name" className="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                First Name *
                            </label>
                            <div className="mt-1">
                                <input
                                    type="text"
                                    id="first_name"
                                    value={data.first_name}
                                    onChange={(e) => setData('first_name', e.target.value)}
                                    className="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-slate-700 rounded-md dark:bg-slate-800 dark:text-white"
                                    required
                                />
                                {errors.first_name && (
                                    <p className="mt-2 text-sm text-red-600">{errors.first_name}</p>
                                )}
                            </div>
                        </div>

                        <div className="sm:col-span-2">
                            <label htmlFor="middle_name" className="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Middle Name
                            </label>
                            <div className="mt-1">
                                <input
                                    type="text"
                                    id="middle_name"
                                    value={data.middle_name}
                                    onChange={(e) => setData('middle_name', e.target.value)}
                                    className="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-slate-700 rounded-md dark:bg-slate-800 dark:text-white"
                                />
                                {errors.middle_name && (
                                    <p className="mt-2 text-sm text-red-600">{errors.middle_name}</p>
                                )}
                            </div>
                        </div>

                        <div className="sm:col-span-2">
                            <label htmlFor="last_name" className="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Last Name *
                            </label>
                            <div className="mt-1">
                                <input
                                    type="text"
                                    id="last_name"
                                    value={data.last_name}
                                    onChange={(e) => setData('last_name', e.target.value)}
                                    className="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-slate-700 rounded-md dark:bg-slate-800 dark:text-white"
                                    required
                                />
                                {errors.last_name && (
                                    <p className="mt-2 text-sm text-red-600">{errors.last_name}</p>
                                )}
                            </div>
                        </div>

                        <div className="sm:col-span-3">
                            <label htmlFor="email" className="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Email Address
                            </label>
                            <div className="mt-1">
                                <input
                                    type="email"
                                    id="email"
                                    value={data.email}
                                    onChange={(e) => setData('email', e.target.value)}
                                    className="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-slate-700 rounded-md dark:bg-slate-800 dark:text-white"
                                />
                                {errors.email && (
                                    <p className="mt-2 text-sm text-red-600">{errors.email}</p>
                                )}
                            </div>
                        </div>

                        <div className="sm:col-span-3">
                            <label htmlFor="phone" className="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Phone Number
                            </label>
                            <div className="mt-1">
                                <input
                                    type="tel"
                                    id="phone"
                                    value={data.phone}
                                    onChange={(e) => setData('phone', e.target.value)}
                                    className="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-slate-700 rounded-md dark:bg-slate-800 dark:text-white"
                                />
                                {errors.phone && (
                                    <p className="mt-2 text-sm text-red-600">{errors.phone}</p>
                                )}
                            </div>
                        </div>

                        {/* Address Information */}
                        <div className="sm:col-span-6 border-b border-gray-200 dark:border-slate-700 pb-6 mt-6">
                            <h4 className="text-md font-medium text-gray-900 dark:text-white">Address Information</h4>
                        </div>

                        <div className="sm:col-span-4">
                            <label htmlFor="address_line_1" className="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Street Address *
                            </label>
                            <div className="mt-1">
                                <input
                                    type="text"
                                    id="address_line_1"
                                    value={data.address_line_1}
                                    onChange={(e) => setData('address_line_1', e.target.value)}
                                    className="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-slate-700 rounded-md dark:bg-slate-800 dark:text-white"
                                    required
                                />
                                {errors.address_line_1 && (
                                    <p className="mt-2 text-sm text-red-600">{errors.address_line_1}</p>
                                )}
                            </div>
                        </div>

                        <div className="sm:col-span-2">
                            <label htmlFor="address_line_2" className="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Apt/Suite/Unit
                            </label>
                            <div className="mt-1">
                                <input
                                    type="text"
                                    id="address_line_2"
                                    value={data.address_line_2}
                                    onChange={(e) => setData('address_line_2', e.target.value)}
                                    className="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-slate-700 rounded-md dark:bg-slate-800 dark:text-white"
                                />
                                {errors.address_line_2 && (
                                    <p className="mt-2 text-sm text-red-600">{errors.address_line_2}</p>
                                )}
                            </div>
                        </div>

                        <div className="sm:col-span-2">
                            <label htmlFor="city" className="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                City *
                            </label>
                            <div className="mt-1">
                                <input
                                    type="text"
                                    id="city"
                                    value={data.city}
                                    onChange={(e) => setData('city', e.target.value)}
                                    className="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-slate-700 rounded-md dark:bg-slate-800 dark:text-white"
                                    required
                                />
                                {errors.city && (
                                    <p className="mt-2 text-sm text-red-600">{errors.city}</p>
                                )}
                            </div>
                        </div>

                        <div className="sm:col-span-2">
                            <label htmlFor="state" className="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                State/Province *
                            </label>
                            <div className="mt-1">
                                <input
                                    type="text"
                                    id="state"
                                    value={data.state}
                                    onChange={(e) => setData('state', e.target.value)}
                                    className="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-slate-700 rounded-md dark:bg-slate-800 dark:text-white"
                                    required
                                />
                                {errors.state && (
                                    <p className="mt-2 text-sm text-red-600">{errors.state}</p>
                                )}
                            </div>
                        </div>

                        <div className="sm:col-span-2">
                            <label htmlFor="postal_code" className="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                ZIP/Postal Code *
                            </label>
                            <div className="mt-1">
                                <input
                                    type="text"
                                    id="postal_code"
                                    value={data.postal_code}
                                    onChange={(e) => setData('postal_code', e.target.value)}
                                    className="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-slate-700 rounded-md dark:bg-slate-800 dark:text-white"
                                    required
                                />
                                {errors.postal_code && (
                                    <p className="mt-2 text-sm text-red-600">{errors.postal_code}</p>
                                )}
                            </div>
                        </div>

                        {/* Connection Information */}
                        <div className="sm:col-span-6 border-b border-gray-200 dark:border-slate-700 pb-6 mt-6">
                            <h4 className="text-md font-medium text-gray-900 dark:text-white">Connection Information</h4>
                        </div>

                        <div className="sm:col-span-2">
                            <label htmlFor="meter_number" className="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Meter Number
                            </label>
                            <div className="mt-1">
                                <input
                                    type="text"
                                    id="meter_number"
                                    value={data.meter_number}
                                    onChange={(e) => setData('meter_number', e.target.value)}
                                    className="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-slate-700 rounded-md dark:bg-slate-800 dark:text-white"
                                />
                                {errors.meter_number && (
                                    <p className="mt-2 text-sm text-red-600">{errors.meter_number}</p>
                                )}
                            </div>
                        </div>

                        <div className="sm:col-span-2">
                            <label htmlFor="connection_date" className="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Connection Date *
                            </label>
                            <div className="mt-1">
                                <input
                                    type="date"
                                    id="connection_date"
                                    value={data.connection_date}
                                    onChange={(e) => setData('connection_date', e.target.value)}
                                    className="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-slate-700 rounded-md dark:bg-slate-800 dark:text-white"
                                    required
                                />
                                {errors.connection_date && (
                                    <p className="mt-2 text-sm text-red-600">{errors.connection_date}</p>
                                )}
                            </div>
                        </div>

                        <div className="sm:col-span-6">
                            <label htmlFor="notes" className="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Notes
                            </label>
                            <div className="mt-1">
                                <textarea
                                    id="notes"
                                    rows={3}
                                    value={data.notes}
                                    onChange={(e) => setData('notes', e.target.value)}
                                    className="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-slate-700 rounded-md dark:bg-slate-800 dark:text-white"
                                />
                                {errors.notes && (
                                    <p className="mt-2 text-sm text-red-600">{errors.notes}</p>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
                <div className="px-4 py-3 bg-gray-50 dark:bg-slate-900 text-right sm:px-6 rounded-b-lg">
                    <button
                        type="button"
                        onClick={() => window.history.back()}
                        className="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white dark:hover:bg-slate-600"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        disabled={processing}
                        className="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        {processing ? 'Saving...' : (consumer ? 'Update Consumer' : 'Create Consumer')}
                    </button>
                </div>
            </div>
        </form>
    );
}
