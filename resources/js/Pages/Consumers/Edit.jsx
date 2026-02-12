import { Head } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';
import ConsumerForm from '@/Components/Form/ConsumerForm';

export default function Edit({ consumer, connectionTypes, statuses }) {
    return (
        <AppLayout>
            <Head title={`Edit Consumer - ${consumer.first_name} ${consumer.last_name}`} />
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div className="md:flex md:items-center md:justify-between">
                    <div className="flex-1 min-w-0">
                        <h2 className="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                            Edit Consumer
                        </h2>
                        <p className="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Update the details for {consumer.first_name} {consumer.last_name}.
                        </p>
                    </div>
                </div>

                <div className="mt-6">
                    <div className="bg-white dark:bg-slate-800 shadow overflow-hidden sm:rounded-lg">
                        <div className="px-4 py-5 sm:p-6">
                            <ConsumerForm 
                                consumer={consumer}
                                connectionTypes={connectionTypes}
                                statuses={statuses}
                            />
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
