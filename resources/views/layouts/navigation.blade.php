@php
    $navLinks = [
        ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'dashboard'],
        ['label' => 'Meter Reading', 'route' => 'meter-readings.index', 'icon' => 'document-chart-bar'],
        ['label' => 'Consumers', 'route' => 'consumers.index', 'icon' => 'user-group'],
        [
            'label' => 'Billing & Payments',
            'icon'  => 'currency-dollar',
            'dropdown' => true,
            'submenu' => [
                ['label' => 'Billing Page',  'route' => 'billings.index',    'icon' => 'document-text'],
                ['label' => 'Payment Page',  'route' => 'payments.index',    'icon' => 'credit-card'],
                ['label' => 'Audit Logs',    'route' => 'audit-logs.index',  'icon' => 'clipboard-document-list'],
            ]
        ],
        ['label' => 'Settings', 'route' => 'settings.index', 'icon' => 'cog-6-tooth'],
        ['label' => 'Reports',  'route' => 'reports.index',  'icon' => 'chart-bar'],
    ];
@endphp

{{-- ============================================================
     SIDEBAR WRAPPER
     ============================================================ --}}
<div class="flex h-full w-full flex-col overflow-y-auto px-3 py-4" x-data="{}">

    {{-- ----- BRANDING ----- --}}
    <a href="{{ route('dashboard') }}"
       class="flex items-center gap-3 px-2 mb-6 group">
        <img
            src="{{ asset('images/maribulan-seal.png') }}"
            alt="Barangay Maribulan Logo"
            class="h-12 w-12 flex-shrink-0 object-contain transition-transform duration-300 group-hover:scale-105"
        >
        <div class="leading-snug min-w-0">
            <p class="text-xs font-extrabold uppercase tracking-[0.12em] text-blue-600">Barangay Maribulan</p>
            <p class="text-base font-extrabold tracking-tight text-slate-900 leading-tight">Water System<br>Level III</p>
        </div>
    </a>

    {{-- Divider --}}
    <div class="mb-4 h-px bg-slate-100"></div>

    {{-- ----- NAV LABEL ----- --}}
    <p class="mb-2 px-2 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">Menu</p>

    {{-- ----- NAVIGATION ----- --}}
    <nav class="flex-1">
        <ul class="space-y-0.5">
            @foreach ($navLinks as $link)

                {{-- ── DROPDOWN ITEM ── --}}
                @if(isset($link['dropdown']) && $link['dropdown'])
                    @php
                        $hasActiveSubmenu = false;
                        foreach ($link['submenu'] as $sub) {
                            if (Route::has($sub['route']) && request()->routeIs($sub['route'])) {
                                $hasActiveSubmenu = true;
                                break;
                            }
                        }
                    @endphp

                    <li x-data="{ open: {{ $hasActiveSubmenu ? 'true' : 'false' }} }">

                        {{-- Dropdown trigger --}}
                        <button
                            @click="open = !open"
                            @class([
                                'group flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition-all duration-200 cursor-pointer select-none',
                                'bg-blue-50 text-blue-700 font-semibold border-l-4 border-blue-600 pl-2.5' => $hasActiveSubmenu,
                                'font-medium text-slate-600 hover:text-blue-600 hover:bg-blue-50 border-l-4 border-transparent' => !$hasActiveSubmenu,
                            ])
                        >
                            {{-- Icon (bare, no container) --}}
                            <x-icon :name="$link['icon']" @class([
                                'h-6 w-6 flex-shrink-0 transition-colors duration-200',
                                'text-blue-600' => $hasActiveSubmenu,
                                'text-slate-400 group-hover:text-blue-500' => !$hasActiveSubmenu,
                            ]) />

                            <span class="flex-1 truncate text-left">{{ $link['label'] }}</span>

                            {{-- Chevron --}}
                            <svg
                                class="h-4 w-4 flex-shrink-0 transition-transform duration-300 @if($hasActiveSubmenu) text-blue-500 @else text-slate-400 group-hover:text-blue-400 @endif"
                                :class="{ 'rotate-180': open }"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        {{-- Submenu --}}
                        <ul
                            x-show="open"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-1"
                            class="mt-0.5 ml-9 space-y-0.5 border-l-2 border-slate-100 pl-3"
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
                                            'group flex items-center gap-2.5 rounded-lg px-2.5 py-2 text-sm transition-all duration-200',
                                            'text-blue-700 font-semibold bg-blue-50' => $isActive,
                                            'text-slate-500 font-medium hover:text-blue-600 hover:bg-blue-50' => !$isActive && $hasRoute,
                                            'pointer-events-none text-slate-300 opacity-40' => !$hasRoute,
                                        ])
                                        @if($isActive) aria-current="page" @endif
                                    >
                                        <x-icon :name="$sublink['icon']" @class([
                                            'h-4 w-4 flex-shrink-0',
                                            'text-blue-600' => $isActive,
                                            'text-slate-400 group-hover:text-blue-500' => !$isActive,
                                        ]) />
                                        <span class="truncate">{{ $sublink['label'] }}</span>
                                        @if($isActive)
                                            <span class="ml-auto h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                    </li>

                {{-- ── REGULAR ITEM ── --}}
                @else
                    @php
                        $hasRoute = Route::has($link['route']);
                        $isActive = $hasRoute && request()->routeIs($link['route']);
                    @endphp

                    <li>
                        <a
                            href="{{ $hasRoute ? route($link['route']) : '#' }}"
                            @class([
                                'group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition-all duration-200 border-l-4',
                                'bg-blue-50 text-blue-700 font-semibold border-blue-600 pl-2.5' => $isActive,
                                'font-medium text-slate-600 hover:text-blue-600 hover:bg-blue-50 border-transparent' => !$isActive && $hasRoute,
                                'pointer-events-none text-slate-300 opacity-40 border-transparent' => !$hasRoute,
                            ])
                            @if($isActive) aria-current="page" @endif
                            @if(!$hasRoute) aria-disabled="true" @endif
                        >
                            {{-- Bare icon, no container --}}
                            <x-icon :name="$link['icon']" @class([
                                'h-6 w-6 flex-shrink-0 transition-colors duration-200',
                                'text-blue-600' => $isActive,
                                'text-slate-400 group-hover:text-blue-500' => !$isActive && $hasRoute,
                                'text-slate-300' => !$hasRoute,
                            ]) />

                            <span class="flex-1 truncate">{{ $link['label'] }}</span>

                            @if($isActive)
                                <span class="ml-auto h-2 w-2 rounded-full bg-blue-500 shadow-[0_0_6px_rgba(59,130,246,0.5)]"></span>
                            @endif
                        </a>
                    </li>
                @endif

            @endforeach
        </ul>
    </nav>

    {{-- ----- DIVIDER BEFORE LOGOUT ----- --}}
    <div class="mt-4 h-px bg-slate-100"></div>

    {{-- ----- LOGOUT BUTTON ----- --}}
    <form method="POST" action="{{ route('logout') }}" class="mt-3">
        @csrf
        <button
            type="submit"
            class="group flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-rose-500 transition-all duration-200 hover:bg-rose-50 hover:text-rose-700 border-l-4 border-transparent hover:border-rose-400"
        >
            {{-- Bare icon --}}
            <x-icon name="power" class="h-6 w-6 flex-shrink-0 text-rose-400 group-hover:text-rose-600 transition-colors duration-200" />
            <span>Log Out</span>
        </button>
    </form>

</div>
