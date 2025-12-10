@php
    $navLinks = [
        ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'dashboard'],
        ['label' => 'Meter Reading', 'route' => 'meter-readings.index', 'icon' => 'document-chart-bar'],
        ['label' => 'Consumers', 'route' => 'consumers.index', 'icon' => 'user-group'],
        ['label' => 'Billing & Payments', 'route' => 'billings.index', 'icon' => 'currency-dollar'],
        ['label' => 'User Management', 'route' => 'users.index', 'icon' => 'cog-6-tooth'],
        ['label' => 'Reports', 'route' => 'reports.index', 'icon' => 'chart-bar'],
    ];
@endphp

{{-- SIDEBAR WRAPPER --}}
<div class="flex h-full w-full flex-col gap-4 overflow-y-auto px-2 py-3">
    {{-- BRANDING --}}
    <a href="{{ route('dashboard') }}" class="block group rounded-lg border border-white/10 bg-gradient-to-br from-blue-600/20 to-indigo-600/20 px-4 py-2.5 shadow-md shadow-indigo-500/10 backdrop-blur transition-all duration-200 hover:shadow-indigo-500/20 hover:border-white/20">
        <div class="flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-md shadow-blue-500/20 ring-1 ring-white/20 transition-all duration-200 group-hover:shadow-blue-500/30 group-hover:scale-105">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 21h16.5M4.5 3h15m0 0v16.5m0-16.5V9h-15M7.5 3v6m0 12v-6m0 0h6m-6 0H4.5m9 0h6m-6 0h-6m6 0v6m0-6v6m0 0h6m-6 0h-6"/>
                </svg>
            </div>
            <div class="leading-tight">
                <p class="text-xs font-bold uppercase tracking-[0.15em] text-white/60">WATER BILLING</p>
                <p class="text-base font-bold tracking-tight text-white">Management</p>
            </div>
        </div>
    </a>

    {{-- NAVIGATION --}}
    <nav class="flex-1">
        <p class="px-3 text-xs font-medium uppercase tracking-[0.15em] text-white/50 mb-3">Navigation</p>

        <ul class="space-y-1.5">
            @foreach ($navLinks as $link)
                @php
                    $hasRoute = Route::has($link['route']);
                    $isActive = $hasRoute && request()->routeIs($link['route']);
                @endphp

                <li>
                    <a
                        href="{{ $hasRoute ? route($link['route']) : '#' }}"
                        @class([
                            'group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-200',
                            'bg-gradient-to-r from-indigo-500/20 via-indigo-500/10 to-transparent text-white shadow-md shadow-indigo-500/5 ring-1 ring-inset ring-indigo-400/30' => $isActive,
                            'text-white/80 hover:text-white hover:bg-white/5 hover:ring-1 hover:ring-white/10' => !$isActive && $hasRoute,
                            'pointer-events-none text-white/40 opacity-50' => !$hasRoute,
                        ])
                        @if($isActive) aria-current="page" @endif
                        @if(!$hasRoute) aria-disabled="true" @endif
                    >
                        <span class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-md bg-white/10 text-white/80 ring-1 ring-inset ring-white/10 transition duration-200 group-hover:bg-white/15 group-hover:text-white">
                            <x-icon :name="$link['icon']" class="h-4 w-4" />
                        </span>

                        <span class="flex-1 truncate">{{ $link['label'] }}</span>

                        @if($hasRoute && $isActive)
                            <span class="h-2.5 w-2.5 rounded-full bg-indigo-400 shadow-[0_0_10px_rgba(99,102,241,0.6)]"></span>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>

    {{-- ACCOUNT ACTIONS --}}
    <form method="POST" action="{{ route('logout') }}" class="mt-4">
        @csrf
        <button
            type="submit"
            class="group flex w-full items-center gap-3 rounded-lg border border-white/10 bg-red-500/10 px-4 py-2.5 text-sm font-medium text-red-200 transition hover:border-red-400/20 hover:bg-red-500/20 hover:text-white"
        >
            <span class="flex items-center gap-3">
                <span class="flex h-8 w-8 items-center justify-center rounded-md bg-red-500/20 text-red-200 transition group-hover:bg-red-500/30 group-hover:text-white">
                    <x-icon name="power" class="h-4 w-4" />
                </span>
                <span class="text-sm">Log Out</span>
            </span>
        </button>
    </form>
</div>
