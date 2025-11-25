@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                Add New Consumer
            </h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Fill in the details below to create a new consumer account.
            </p>
        </div>
    </div>

    <div class="mt-6">
        <form action="{{ route('consumers.store') }}" method="POST">
            @include('consumers._form')
        </form>
    </div>
</div>
@endsection
