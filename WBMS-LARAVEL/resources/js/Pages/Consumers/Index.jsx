import { Head, Link, router, usePage } from '@inertiajs/react';
import { useState } from 'react';
import AppLayout from '@/Layouts/AppLayout';
import { PencilIcon, TrashIcon, EyeIcon, PlusIcon } from '@heroicons/react/24/outline';
import { debounce } from 'lodash';

export default function Index({ consumers, filters }) {
    const [search, setSearch] = useState(filters.search || '');
    const [status, setStatus] = useState(filters.status || '');
    const [perPage, setPerPage] = useState(filters.per_page || 10);

    const debouncedSearch = debounce((value) => {
        router.get(
            route('consumers.index'),
            { search: value, status, per_page: perPage },
            { preserveState: true, replace: true }
        );
    }, 300);

    const handleSearch = (e) => {
        setSearch(e.target.value);
        debouncedSearch(e.target.value);
    };

    const handleStatusChange = (e) => {
        const newStatus = e.target.value;
        setStatus(newStatus);
        router.get(
            route('consumers.index'),
            { search, status: newStatus, per_page: perPage },
            { preserveState: true, replace: true }
        );
    };

    const handlePerPageChange = (e) => {
        const newPerPage = e.target.value;
        setPerPage(newPerPage);
        router.get(
            route('consumers.index'),
            { search, status, per_page: newPerPage },
            { preserveState: true, replace: true }
        );
    };

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

    return (
        <AppLayout>
            <Head title="Consumers" />
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                    <div className="mb-4 sm:mb-0">
                        <h1 className="text-2xl font-semibold text-gray-900 dark:text-white">Consumers</h1>
                        <p className="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Manage your water consumers and their accounts
                        </p>
                    </div>
                    <Link
                        href={route('consumers.create')}
                        className="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                        <PlusIcon className="h-4 w-4 mr-2" />
                        Add New Consumer
                    </Link>
                </div>

                <div className="bg-white dark:bg-slate-800 shadow rounded-lg overflow-hidden">
                    <div className="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-slate-700">
                        <div className="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                            <div className="w-full md:w-1/3">
                                <label htmlFor="search" className="sr-only">Search</label>
                                <div className="relative">
                                    <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg className="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fillRule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clipRule="evenodd" />
                                        </svg>
                                    </div>
                                    <input
                                        type="text"
                                        name="search"
                                        id="search"
                                        className="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-slate-700 rounded-md leading-5 bg-white dark:bg-slate-800 placeholder-gray-500 dark:placeholder-slate-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        placeholder="Search consumers..."
                                        value={search}
                                        onChange={handleSearch}
                                    />
                                </div>
                            </div>
                            <div className="flex items-center space-x-4">
                                <div className="w-40">
                                    <select
                                        id="status"
                                        name="status"
                                        className="block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-slate-700 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-slate-800 dark:text-white"
                                        value={status}
                                        onChange={handleStatusChange}
                                    >
                                        <option value="">All Status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="disconnected">Disconnected</option>
                                        <option value="pending">Pending</option>
                                    </select>
                                </div>
                                <div className="w-24">
                                    <select
                                        id="per_page"
                                        name="per_page"
                                        className="block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-slate-700 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-slate-800 dark:text-white"
                                        value={perPage}
                                        onChange={handlePerPageChange}
                                    >
                                        <option value="5">5</option>
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="overflow-x-auto">
                        <table className="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                            <thead className="bg-gray-50 dark:bg-slate-800">
                                <tr>
                                    <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                                        Account #
                                    </th>
                                    <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                                        Contact
                                    </th>
                                    <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                                        Connection
                                    </th>
                                    <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" className="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody className="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-slate-700">
                                {consumers.data.length > 0 ? (
                                    consumers.data.map((consumer) => (
                                        <tr key={consumer.id} className="hover:bg-gray-50 dark:hover:bg-slate-700">
                                            <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                {consumer.account_number}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <div className="text-sm font-medium text-gray-900 dark:text-white">
                                                    {consumer.first_name} {consumer.last_name}
                                                </div>
                                                <div className="text-sm text-gray-500 dark:text-slate-400">
                                                    {consumer.email}
                                                </div>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-slate-400">
                                                <div>{consumer.phone || 'N/A'}</div>
                                                <div className="text-xs text-gray-400 dark:text-slate-500 truncate max-w-xs">
                                                    {consumer.address_line_1}, {consumer.city}
                                                </div>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${connectionTypeColors[consumer.connection_type] || 'bg-gray-100 text-gray-800'}`}>
                                                    {consumer.connection_type.charAt(0).toUpperCase() + consumer.connection_type.slice(1)}
                                                </span>
                                                <div className="text-xs text-gray-500 dark:text-slate-400 mt-1">
                                                    {new Date(consumer.connection_date).toLocaleDateString()}
                                                </div>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusStyles[consumer.connection_status] || 'bg-gray-100 text-gray-800'}`}>
                                                    {consumer.connection_status.charAt(0).toUpperCase() + consumer.connection_status.slice(1)}
                                                </span>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div className="flex justify-end space-x-2">
                                                    <Link
                                                        href={route('consumers.show', consumer.id)}
                                                        className="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                                                    >
                                                        <EyeIcon className="h-5 w-5" />
                                                    </Link>
                                                    <Link
                                                        href={route('consumers.edit', consumer.id)}
                                                        className="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                                    >
                                                        <PencilIcon className="h-5 w-5" />
                                                    </Link>
                                                    <button
                                                        onClick={() => {
                                                            if (confirm('Are you sure you want to delete this consumer?')) {
                                                                router.delete(route('consumers.destroy', consumer.id));
                                                            }
                                                        }}
                                                        className="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                    >
                                                        <TrashIcon className="h-5 w-5" />
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan="6" className="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500 dark:text-slate-400">
                                            No consumers found.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>

                    {consumers.data.length > 0 && (
                        <div className="bg-white dark:bg-slate-800 px-4 py-3 flex items-center justify-between border-t border-gray-200 dark:border-slate-700 sm:px-6">
                            <div className="flex-1 flex justify-between sm:hidden">
                                <Link
                                    href={consumers.prev_page_url}
                                    className={`relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md ${
                                        !consumers.prev_page_url ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-50'
                                    }`}
                                    disabled={!consumers.prev_page_url}
                                >
                                    Previous
                                </Link>
                                <Link
                                    href={consumers.next_page_url}
                                    className={`ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md ${
                                        !consumers.next_page_url ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-50'
                                    }`}
                                    disabled={!consumers.next_page_url}
                                >
                                    Next
                                </Link>
                            </div>
                            <div className="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <p className="text-sm text-gray-700 dark:text-slate-400">
                                        Showing <span className="font-medium">{consumers.from}</span> to{' '}
                                        <span className="font-medium">{consumers.to}</span> of{' '}
                                        <span className="font-medium">{consumers.total}</span> results
                                    </p>
                                </div>
                                <div>
                                    <nav className="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                        {consumers.links.map((link, index) => (
                                            <Link
                                                key={index}
                                                href={link.url || '#'}
                                                className={`relative inline-flex items-center px-4 py-2 border text-sm font-medium ${
                                                    link.active
                                                        ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600 dark:bg-slate-700 dark:border-slate-600 dark:text-white'
                                                        : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-700'
                                                } ${!link.url ? 'opacity-50 cursor-not-allowed' : ''}`}
                                                dangerouslySetInnerHTML={{ __html: link.label }}
                                            />
                                        ))}
                                    </nav>
                                </div>
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </AppLayout>
    );
}
