@php
    $navLinks = [
        ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'dashboard'],
        ['label' => 'Meter Reading', 'route' => 'meter-readings.index', 'icon' => 'document-chart-bar'],
        ['label' => 'Consumers', 'route' => 'consumers.index', 'icon' => 'user-group'],
        ['label' => 'Billing', 'route' => 'billings.index', 'icon' => 'currency-dollar'],
        ['label' => 'User Management', 'route' => 'users.index', 'icon' => 'cog-6-tooth'],
        ['label' => 'Reports', 'route' => 'reports.index', 'icon' => 'chart-bar'],
    ];
@endphp

{{-- SIDEBAR WRAPPER --}}
<div class="flex h-full w-full flex-col gap-6 overflow-y-auto px-4 py-6">
    {{-- BRANDING --}}
    <div class="group rounded-2xl border border-white/10 bg-gradient-to-br from-blue-600/20 to-indigo-600/20 px-4 py-3 shadow-lg shadow-indigo-500/10 backdrop-blur transition-all duration-300 hover:shadow-indigo-500/20">
        <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-lg shadow-blue-500/30 ring-1 ring-white/20 ring-offset-1 ring-offset-blue-500/20 transition-all duration-300 group-hover:shadow-blue-500/40">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 21h16.5M4.5 3h15m0 0v16.5m0-16.5V9h-15M7.5 3v6m0 12v-6m0 0h6m-6 0H4.5m9 0h6m-6 0h-6m6 0v6m0-6v6m0 0h6m-6 0h-6"/>
                </svg>
            </div>
            <div class="leading-tight">
                <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-white/60">WATER BILLING</p>
                <p class="text-lg font-bold tracking-tight text-white">Management System</p>
            </div>
        </div>
    </div>

    {{-- NAVIGATION --}}
    <nav class="flex-1">
        <p class="px-2 text-xs font-semibold uppercase tracking-[0.2em] text-white/40">Navigation</p>

        <ul class="mt-3 space-y-1.5">
            @foreach ($navLinks as $link)
                @php
                    $hasRoute = Route::has($link['route']);
                    $isActive = $hasRoute && request()->routeIs($link['route']);
                @endphp

                <li>
                    <a
                        href="{{ $hasRoute ? route($link['route']) : '#' }}"
                        @class([
                            'group flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium transition-all duration-200',
                            'bg-gradient-to-r from-indigo-500/25 via-indigo-500/15 to-transparent text-white shadow-lg shadow-indigo-500/10 ring-1 ring-inset ring-indigo-400/40 backdrop-blur' => $isActive,
                            'text-white/70 hover:text-white hover:bg-white/5 hover:ring-1 hover:ring-white/10' => !$isActive && $hasRoute,
                            'pointer-events-none text-white/40 opacity-50' => !$hasRoute,
                        ])
                        @if($isActive) aria-current="page" @endif
                        @if(!$hasRoute) aria-disabled="true" @endif
                    >
                        <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-white/10 text-white/80 ring-1 ring-inset ring-white/10 transition duration-200 group-hover:bg-white/15 group-hover:text-white">
                            <x-icon :name="$link['icon']" class="h-4.5 w-4.5" />
                        </span>

                        <span class="flex-1 truncate">{{ $link['label'] }}</span>

                        @if($hasRoute)
                            @if($isActive)
                                <span class="h-2.5 w-2.5 rounded-full bg-indigo-400 shadow-[0_0_12px_rgba(99,102,241,0.6)]"></span>
                            @else
                                <x-icon name="chevron-right" class="h-4 w-4 text-white/35 transition group-hover:text-white/60" />
                            @endif
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>

    {{-- ACCOUNT ACTIONS --}}
    <form method="POST" action="{{ route('logout') }}" class="mt-auto">
        @csrf
        <button
            type="submit"
            class="group flex w-full items-center gap-3 rounded-xl border border-white/10 bg-red-500/10 px-4 py-3 text-sm font-medium text-red-200 transition hover:border-red-400/20 hover:bg-red-500/20 hover:text-white"
        >
            <span class="flex items-center gap-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-red-500/20 text-red-200 transition group-hover:bg-red-500/30 group-hover:text-white">
                    <x-icon name="power" class="h-4 w-4" />
                </span>
                <span class="flex-1 text-left">Log Out</span>
                <x-icon name="arrow-right" class="h-4 w-4 text-red-200 transition group-hover:text-white" />
        </button>
    </form>
</div>
