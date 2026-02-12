<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body x-data="{ sidebarOpen: false }"
    class="font-sans antialiased bg-slate-100 text-slate-900 dark:bg-slate-900 dark:text-slate-100">
    @php
        use Illuminate\Support\Str;
    @endphp
    <div class="min-h-screen lg:flex">
        <!-- Mobile overlay -->
        <div x-cloak x-show="sidebarOpen" @click="sidebarOpen = false"
            class="fixed inset-0 z-30 bg-slate-900/70 backdrop-blur-sm lg:hidden"></div>

        <!-- Sidebar -->
        <aside x-cloak :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed inset-y-0 z-40 flex w-72 flex-col bg-[#0f172a] px-3 py-4 text-white shadow-2xl transition duration-200 ease-in-out lg:fixed lg:inset-y-0 lg:left-0 lg:overflow-y-auto">
            @include('layouts.navigation')
        </aside>


        <!-- Main content -->
        <div class="flex-1 lg:ml-72">
            <header
                class="sticky top-0 z-20 border-b border-slate-200 bg-white/80 px-4 py-4 backdrop-blur dark:border-slate-800 dark:bg-slate-900/80 lg:px-10">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                    </div>
                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center gap-3 rounded-2xl border border-slate-700 bg-slate-800 px-4 py-2 text-sm shadow-sm transition hover:border-slate-600 hover:bg-slate-900">
                        <div class="text-right leading-tight">
                            <p class="text-xs uppercase tracking-wide text-slate-400">Signed in as</p>
                            <p class="text-sm font-semibold text-slate-900 dark:text-white">
                                {{ Auth::user()->name ?? 'User' }}
                            </p>
                        </div>
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-700 text-white">
                            <span class="text-sm font-semibold">
                                {{ Str::upper(Str::substr(Auth::user()->name ?? 'U', 0, 1)) }}
                            </span>
                        </div>
                    </a>
                </div>
                @isset($header)
                    <div
                        class="mt-4 rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        {{ $header }}
                    </div>
                @endisset
            </header>

            <main
                class="min-h-screen bg-slate-100 px-4 py-8 dark:bg-slate-900 sm:px-6 lg:px-10 transition-all duration-300 ease-in-out">
                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>