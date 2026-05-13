@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Audit Logs</h1>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Track system activities and consumer billing history
                    </p>
                </div>
            </div>
        </div>

        @include('audit-logs.partials.system-logs')

    </div>
@endsection