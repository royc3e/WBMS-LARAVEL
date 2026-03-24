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
    class="font-sans antialiased bg-slate-50 text-slate-900 cursor-default">
    @php
        use Illuminate\Support\Str;
    @endphp
    <div class="min-h-screen">
        <!-- Mobile overlay -->
        <div x-cloak x-show="sidebarOpen" @click="sidebarOpen = false"
            class="fixed inset-0 z-30 bg-slate-900/70 backdrop-blur-sm lg:hidden"></div>

        <!-- Sidebar: always visible on lg+, slide-in on mobile -->
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed inset-y-0 left-0 z-40 flex w-64 flex-col bg-white border-r border-slate-100 text-slate-700 shadow-sm transition-transform duration-200 ease-in-out overflow-y-auto">
            @include('layouts.navigation')
        </aside>

        <!-- Main content wrapper — offset by sidebar width on lg -->
        <div class="lg:pl-64 min-h-screen flex flex-col">
            <header
                class="sticky top-0 z-20 border-b border-slate-200 bg-white/80 px-4 py-4 backdrop-blur lg:px-10">
                <div class="flex items-center justify-end gap-4 w-full">
                    <a href="{{ route('settings.profile') }}"
                        class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm shadow-sm transition hover:border-blue-200 hover:bg-blue-50/50 hover:shadow-md group">
                        <div class="text-right leading-tight">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Signed in as</p>
                            <p class="text-sm font-bold text-slate-900">
                                {{ Auth::user()->name ?? 'User' }}
                            </p>
                            <p class="text-[10px] text-slate-400 capitalize">{{ Auth::user()->role ?? 'staff' }}</p>
                        </div>
                        {{-- Profile photo or fallback initial avatar --}}
                        @if(Auth::user()->profile_photo && file_exists(public_path('storage/' . Auth::user()->profile_photo)))
                            <img
                                src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
                                alt="{{ Auth::user()->name }}"
                                class="h-10 w-10 rounded-full object-cover ring-2 ring-blue-100 group-hover:ring-blue-300 transition-all duration-200 shadow-sm"
                            >
                        @else
                            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-blue-700 text-white ring-2 ring-blue-100 group-hover:ring-blue-300 transition-all duration-200 shadow-sm">
                                <span class="text-sm font-extrabold leading-none">
                                    {{ Str::upper(Str::substr(Auth::user()->name ?? 'U', 0, 1)) }}
                                </span>
                            </div>
                        @endif
                    </a>
                </div>
                @isset($header)
                    <div
                        class="mt-4 rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
                        {{ $header }}
                    </div>
                @endisset
            </header>

            <main class="flex-1 bg-slate-50 px-4 py-8 sm:px-6 lg:px-8 overflow-x-hidden">
                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>