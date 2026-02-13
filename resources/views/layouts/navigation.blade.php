@php
    $navLinks = [
        ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'dashboard'],
        ['label' => 'Meter Reading', 'route' => 'meter-readings.index', 'icon' => 'document-chart-bar'],
        ['label' => 'Consumers', 'route' => 'consumers.index', 'icon' => 'user-group'],
        [
            'label' => 'Billing & Payments', 
            'icon' => 'currency-dollar',
            'dropdown' => true,
            'submenu' => [
                ['label' => 'Billing Page', 'route' => 'billings.index', 'icon' => 'document-text'],
                ['label' => 'Payment Page', 'route' => 'payments.index', 'icon' => 'credit-card'],
                ['label' => 'Audit Logs', 'route' => 'audit-logs.index', 'icon' => 'clipboard-document-list'],
            ]
        ],
        ['label' => 'Settings', 'route' => 'users.index', 'icon' => 'cog-6-tooth'],
        ['label' => 'Reports', 'route' => 'reports.index', 'icon' => 'chart-bar'],
    ];
@endphp

{{-- SIDEBAR WRAPPER --}}
<div class="flex h-full w-full flex-col gap-4 overflow-y-auto px-2 py-3" x-data="{ openDropdowns: {} }">
    {{-- BRANDING --}}
    <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
        <!-- Logo image -->
        <img 
            src="{{ asset('images/maribulan-seal.png') }}" 
            alt="Barangay Maribulan Logo"
            class="h-10 w-10 object-contain"
        >
        <div class="leading-tight">
            <p class="text-xs font-bold uppercase tracking-[0.15em] text-white/60">Barangay Maribulan</p>
            <p class="text-base font-bold tracking-tight text-white">Water System Level III</p>
        </div>
    </a>


    {{-- NAVIGATION --}}
    <nav class="flex-1">
        <p class="px-3 text-xs font-medium uppercase tracking-[0.15em] text-white/50 mb-3">Navigation</p>

        <ul class="space-y-1.5">
            @foreach ($navLinks as $index => $link)
                @if(isset($link['dropdown']) && $link['dropdown'])
                    {{-- Dropdown Menu Item --}}
                    @php
                        $hasActiveSubmenu = false;
                        foreach ($link['submenu'] as $sublink) {
                            if (Route::has($sublink['route']) && request()->routeIs($sublink['route'])) {
                                $hasActiveSubmenu = true;
                                break;
                            }
                        }
                    @endphp
                    
                    <li x-data="{ open: {{ $hasActiveSubmenu ? 'true' : 'false' }} }">
                        <button
                            @click="open = !open"
                            @class([
                                'group flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-200 cursor-pointer',
                                'bg-gradient-to-r from-indigo-500/20 via-indigo-500/10 to-transparent text-white shadow-md shadow-indigo-500/5 ring-1 ring-inset ring-indigo-400/30' => $hasActiveSubmenu,
                                'text-white/80 hover:text-white hover:bg-white/5 hover:ring-1 hover:ring-white/10' => !$hasActiveSubmenu,
                            ])
                        >
                            <span class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-md bg-white/10 text-white/80 ring-1 ring-inset ring-white/10 transition duration-200 group-hover:bg-white/15 group-hover:text-white">
                                <x-icon :name="$link['icon']" class="h-4 w-4" />
                            </span>

                            <span class="flex-1 truncate text-left">{{ $link['label'] }}</span>

                            <svg 
                                class="h-4 w-4 transition-transform duration-200"
                                :class="{ 'rotate-180': open }"
                                fill="none" 
                                stroke="currentColor" 
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        {{-- Submenu --}}
                        <ul 
                            x-show="open"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-2"
                            class="mt-1.5 space-y-1 pl-3 overflow-hidden"
                        >
                            @foreach($link['submenu'] as $sublink)
                                @php
                                    $hasRoute = Route::has($sublink['route']);
                                    $isActive = $hasRoute && request()->routeIs($sublink['route']);
                                @endphp
                                
                                <li>
                                    <a
                                        href="{{ $hasRoute ? route($sublink['route']) : '#' }}"
                                        @class([
                                            'group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200',
                                            'bg-indigo-500/20 text-white ring-1 ring-inset ring-indigo-400/30' => $isActive,
                                            'text-white/70 hover:text-white hover:bg-white/5' => !$isActive && $hasRoute,
                                            'pointer-events-none text-white/40 opacity-50' => !$hasRoute,
                                        ])
                                        @if($isActive) aria-current="page" @endif
                                        @if(!$hasRoute) aria-disabled="true" @endif
                                    >
                                        <span class="flex h-6 w-6 flex-shrink-0 items-center justify-center">
                                            <x-icon :name="$sublink['icon']" class="h-3.5 w-3.5" />
                                        </span>

                                        <span class="flex-1 truncate">{{ $sublink['label'] }}</span>

                                        @if($hasRoute && $isActive)
                                            <span class="h-2 w-2 rounded-full bg-indigo-400 shadow-[0_0_8px_rgba(99,102,241,0.6)]"></span>
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @else
                    {{-- Regular Menu Item --}}
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
                @endif
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
