import { Head, Link } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';
import { PencilIcon, ArrowLeftIcon, EnvelopeIcon, PhoneIcon, MapPinIcon, CalendarIcon, DocumentTextIcon } from '@heroicons/react/24/outline';

export default function Show({ consumer, stats }) {
    const statusStyles = {
        active: 'bg-green-100 text-green-800',
        inactive: 'bg-yellow-100 text-yellow-800',
        disconnected: 'bg-red-100 text-red-800',
        pending: 'bg-blue-100 text-blue-800',
    };

    const connectionTypeColors = {
        residential: 'bg-purple-100 text-purple-800',
        commercial: 'bg-blue-100 text-blue-800',
        industrial: 'bg-yellow-100 text-yellow-800',
        government: 'bg-green-100 text-green-800',
    };

    const formatDate = (dateString) => {
        return new Date(dateString).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    };

    return (
        <AppLayout>
            <Head title={`${consumer.first_name} ${consumer.last_name} - Consumer Details`} />
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div className="mb-6">
                    <Link 
                        href={route('consumers.index')} 
                        className="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                    >
                        <ArrowLeftIcon className="h-4 w-4 mr-1" />
                        Back to Consumers
                    </Link>
                </div>

                <div className="md:flex md:items-center md:justify-between mb-6">
                    <div className="flex-1 min-w-0">
                        <h2 className="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                            {consumer.first_name} {consumer.middle_name} {consumer.last_name}
                        </h2>
                        <div className="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                            <div className="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400">
                                <span className="mr-1.5">Account #:</span>
                                <span className="font-medium text-gray-900 dark:text-white">{consumer.account_number}</span>
                            </div>
                            <div className="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400">
                                <span className="mr-1.5">Status:</span>
                                <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusStyles[consumer.connection_status] || 'bg-gray-100 text-gray-800'}`}>
                                    {consumer.connection_status.charAt(0).toUpperCase() + consumer.connection_status.slice(1)}
                                </span>
                            </div>
                            <div className="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400">
                                <span className="mr-1.5">Connection Type:</span>
                                <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${connectionTypeColors[consumer.connection_type] || 'bg-gray-100 text-gray-800'}`}>
                                    {consumer.connection_type.charAt(0).toUpperCase() + consumer.connection_type.slice(1)}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div className="mt-4 flex md:mt-0 md:ml-4">
                        <Link
                            href={route('consumers.edit', consumer.id)}
                            className="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            <PencilIcon className="h-4 w-4 mr-2" />
                            Edit Consumer
                        </Link>
                    </div>
                </div>

                {/* Stats */}
                {stats && (
                    <div className="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
                        {stats.map((stat) => (
                            <div key={stat.name} className="bg-white dark:bg-slate-800 overflow-hidden shadow rounded-lg">
                                <div className="px-4 py-5 sm:p-6">
                                    <dt className="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                        {stat.name}
                                    </dt>
                                    <dd className="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">
                                        {stat.value}
                                    </dd>
                                </div>
                            </div>
                        ))}
                    </div>
                )}

                <div className="grid grid-cols-1 gap-6 lg:grid-cols-3">
                    {/* Personal Information */}
                    <div className="bg-white dark:bg-slate-800 shadow overflow-hidden sm:rounded-lg">
                        <div className="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-slate-700">
                            <h3 className="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                                Personal Information
                            </h3>
                        </div>
                        <div className="px-4 py-5 sm:p-6">
                            <dl className="space-y-4">
                                <div>
                                    <dt className="text-sm font-medium text-gray-500 dark:text-gray-400">Full Name</dt>
                                    <dd className="mt-1 text-sm text-gray-900 dark:text-white">
                                        {consumer.first_name} {consumer.middle_name} {consumer.last_name}
                                    </dd>
                                </div>
                                <div className="flex items-start">
                                    <EnvelopeIcon className="h-5 w-5 text-gray-400 mr-2 mt-0.5 flex-shrink-0" />
                                    <div>
                                        <dt className="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                        <dd className="mt-1 text-sm text-gray-900 dark:text-white">
                                            {consumer.email || 'N/A'}
                                        </dd>
                                    </div>
                                </div>
                                <div className="flex items-start">
                                    <PhoneIcon className="h-5 w-5 text-gray-400 mr-2 mt-0.5 flex-shrink-0" />
                                    <div>
                                        <dt className="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</dt>
                                        <dd className="mt-1 text-sm text-gray-900 dark:text-white">
                                            {consumer.phone || 'N/A'}
                                        </dd>
                                    </div>
                                </div>
                            </dl>
                        </div>
                    </div>

                    {/* Address Information */}
                    <div className="bg-white dark:bg-slate-800 shadow overflow-hidden sm:rounded-lg">
                        <div className="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-slate-700">
                            <h3 className="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                                Address Information
                            </h3>
                        </div>
                        <div className="px-4 py-5 sm:p-6">
                            <dl className="space-y-4">
                                <div className="flex items-start">
                                    <MapPinIcon className="h-5 w-5 text-gray-400 mr-2 mt-0.5 flex-shrink-0" />
                                    <div>
                                        <dt className="text-sm font-medium text-gray-500 dark:text-gray-400">Address</dt>
                                        <dd className="mt-1 text-sm text-gray-900 dark:text-white">
                                            {consumer.address_line_1}<br />
                                            {consumer.address_line_2 && <>{consumer.address_line_2}<br /></>}
                                            {consumer.city}, {consumer.state} {consumer.postal_code}
                                        </dd>
                                    </div>
                                </div>
                            </dl>
                        </div>
                    </div>

                    {/* Connection Information */}
                    <div className="bg-white dark:bg-slate-800 shadow overflow-hidden sm:rounded-lg">
                        <div className="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-slate-700">
                            <h3 className="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                                Connection Information
                            </h3>
                        </div>
                        <div className="px-4 py-5 sm:p-6">
                            <dl className="space-y-4">
                                <div>
                                    <dt className="text-sm font-medium text-gray-500 dark:text-gray-400">Meter Number</dt>
                                    <dd className="mt-1 text-sm text-gray-900 dark:text-white">
                                        {consumer.meter_number || 'N/A'}
                                    </dd>
                                </div>
                                <div className="flex items-start">
                                    <CalendarIcon className="h-5 w-5 text-gray-400 mr-2 mt-0.5 flex-shrink-0" />
                                    <div>
                                        <dt className="text-sm font-medium text-gray-500 dark:text-gray-400">Connection Date</dt>
                                        <dd className="mt-1 text-sm text-gray-900 dark:text-white">
                                            {formatDate(consumer.connection_date)}
                                        </dd>
                                    </div>
                                </div>
                                <div className="flex items-start">
                                    <DocumentTextIcon className="h-5 w-5 text-gray-400 mr-2 mt-0.5 flex-shrink-0" />
                                    <div>
                                        <dt className="text-sm font-medium text-gray-500 dark:text-gray-400">Notes</dt>
                                        <dd className="mt-1 text-sm text-gray-900 dark:text-white whitespace-pre-line">
                                            {consumer.notes || 'No notes available.'}
                                        </dd>
                                    </div>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
