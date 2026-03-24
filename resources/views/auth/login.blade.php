<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - {{ config('app.name', 'WBMS') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Custom animations for premium fluid/water effects */
        .bubble {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 15s infinite ease-in-out;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) scale(1);
                opacity: 0.1;
            }
            50% {
                transform: translateY(-100px) scale(1.1);
                opacity: 0.3;
            }
        }
        
        .bubble:nth-child(1) { width: 120px; height: 120px; left: 10%; top: 20%; animation-duration: 12s; }
        .bubble:nth-child(2) { width: 80px; height: 80px; left: 80%; top: 60%; animation-duration: 8s; animation-delay: 2s; }
        .bubble:nth-child(3) { width: 250px; height: 250px; left: -5%; top: 70%; animation-duration: 20s; animation-delay: 1s; }
        .bubble:nth-child(4) { width: 180px; height: 180px; left: 60%; top: 10%; animation-duration: 15s; animation-delay: 4s; }
    </style>
</head>

<body class="font-sans antialiased text-gray-900 bg-slate-50 min-h-screen selection:bg-blue-300 selection:text-blue-900">

    <div class="min-h-screen flex flex-col lg:flex-row w-full selection:bg-blue-300 selection:text-blue-900">

        <!-- ================= LEFT SIDE: Premium Branding Section ================= -->
        <div class="relative w-full lg:w-5/12 xl:w-1/2 flex flex-col justify-center items-center text-white bg-gradient-to-br from-blue-900 via-blue-700 to-blue-500 overflow-hidden py-16 px-6 sm:px-12">

            <!-- Floating Water Bubbles -->
            <div class="bubble"></div>
            <div class="bubble"></div>
            <div class="bubble"></div>
            <div class="bubble"></div>

            <!-- Subtle Grid Background -->
            <div class="absolute inset-0 opacity-[0.03]"
                style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 40px 40px;">
            </div>

            <!-- Dynamic Bottom Wave -->
            <div class="absolute bottom-0 left-0 right-0 opacity-10 pointer-events-none w-full transform translate-y-1">
                <svg viewBox="0 0 1440 320" class="w-full h-auto" preserveAspectRatio="none">
                    <path fill="#ffffff" fill-opacity="1"
                        d="M0,256L48,240C96,224,192,192,288,181.3C384,171,480,181,576,202.7C672,224,768,256,864,261.3C960,267,1056,245,1152,224C1248,203,1344,181,1392,170.7L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
                    </path>
                </svg>
            </div>

            <!-- Content Area -->
            <div class="relative z-10 text-center max-w-lg mx-auto flex flex-col items-center">
                
                <!-- Majestic Seal Container -->
                <div class="mb-10 inline-flex items-center justify-center rounded-full p-2 bg-white/10 backdrop-blur-xl shadow-[0_0_60px_rgba(255,255,255,0.25)] ring-1 ring-white/30 relative group hover:ring-white/60 hover:bg-white/20 transition-all duration-500 ease-out transform hover:scale-105">
                    <!-- Dynamic Rotating Glow -->
                    <div class="absolute inset-0 rounded-full bg-blue-300 opacity-20 blur-2xl group-hover:opacity-40 transition-opacity duration-500"></div>
                    <img src="{{ asset('images/seal.png') }}" alt="Barangay Maribulan Seal"
                        class="relative z-10 w-28 h-28 sm:w-36 sm:h-36 object-contain rounded-full drop-shadow-[0_10px_15px_rgba(0,0,0,0.3)]">
                </div>

                <div class="space-y-4">
                    <h2 class="text-xs sm:text-sm font-bold tracking-[0.3em] uppercase text-blue-200 opacity-90">
                        Barangay Maribulan
                    </h2>
                    <h1 class="text-4xl sm:text-5xl lg:text-5xl font-extrabold leading-tight text-transparent bg-clip-text bg-gradient-to-br from-white to-blue-100 drop-shadow-sm">
                        Water System<br>Level III
                    </h1>
                </div>

                <!-- Sleek Glass Badge -->
                <div class="mt-10 inline-flex px-6 py-2.5 rounded-full border border-white/20 bg-black/10 backdrop-blur-md shadow-inner text-blue-50 font-medium text-sm tracking-widest uppercase">
                    Efficient • Transparent • Reliable
                </div>
            </div>

        </div>

        <!-- ================= RIGHT SIDE: Immersive Login Form ================= -->
        <div class="w-full lg:w-7/12 xl:w-1/2 flex flex-col justify-center items-center py-12 px-6 sm:px-16 lg:px-24 relative bg-slate-50">
            
            <div class="w-full max-w-[420px] relative z-10">
                
                <!-- Abstract Glow Behind Form -->
                <div class="absolute top-0 right-0 -m-8 w-32 h-32 bg-blue-400 opacity-10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 -m-8 w-40 h-40 bg-indigo-400 opacity-10 rounded-full blur-3xl pointer-events-none"></div>

                <!-- Glassmorphism Form Card -->
                <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] ring-1 ring-gray-900/5 p-8 sm:p-10 transition-all duration-300 hover:shadow-[0_8px_40px_rgb(0,0,0,0.08)]">

                    <!-- Form Header -->
                    <div class="mb-10 text-center sm:text-left">
                        <h2 class="text-2xl sm:text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-600">
                            Welcome Back
                        </h2>
                        <p class="text-sm text-gray-500 mt-2 font-medium">Please enter your credentials to login.</p>
                    </div>

                    <!-- Session Status (if any) -->
                    @if (session('status'))
                        <div class="mb-6 flex items-center gap-3 text-sm font-medium text-green-700 bg-green-50/80 backdrop-blur-sm p-4 rounded-xl border border-green-200">
                            <svg class="h-5 w-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <!-- Email Field -->
                        <div class="group">
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5 transition-colors group-focus-within:text-blue-600">Email Address</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-blue-500 text-gray-400">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                    </svg>
                                </div>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                                    class="block w-full pl-11 pr-4 py-3.5 text-sm bg-gray-50/50 border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white transition-all shadow-sm @error('email') border-red-500 focus:ring-red-500/10 focus:border-red-500 @enderror"
                                    placeholder="admin@maribulan.gov.ph">
                            </div>
                            @error('email')
                                <p class="mt-2 text-xs text-red-600 font-medium flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div class="group">
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="password" class="block text-sm font-semibold text-gray-700 transition-colors group-focus-within:text-blue-600">Password</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition-colors">
                                        Forgot Password?
                                    </a>
                                @endif
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-blue-500 text-gray-400">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="password" name="password" id="password" required autocomplete="current-password"
                                    class="block w-full pl-11 pr-4 py-3.5 text-sm bg-gray-50/50 border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white transition-all shadow-sm @error('password') border-red-500 focus:ring-red-500/10 focus:border-red-500 @enderror"
                                    placeholder="••••••••">
                            </div>
                            @error('password')
                                <p class="mt-2 text-xs text-red-600 font-medium flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center pt-2">
                            <div class="flex items-center h-5">
                                <input id="remember_me" name="remember" type="checkbox"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 cursor-pointer transition-colors">
                            </div>
                            <label for="remember_me" class="ml-3 text-sm font-medium text-gray-600 cursor-pointer select-none">
                                Remember this device
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <button type="submit"
                                class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-[0_10px_20px_rgba(37,99,235,0.2)] text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300 transform hover:-translate-y-1">
                                Access Dashboard
                                <svg class="w-5 h-5 ml-2 -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </div>
                    </form>
                </div>

            </div>

            <!-- Footer Text -->
            <div class="absolute bottom-8 text-center text-xs font-semibold text-gray-400 tracking-wide uppercase">
                &copy; {{ date('Y') == 2026 ? '2026' : '2026' }} Barangay Maribulan. All Rights Reserved.
            </div>

        </div>

    </div>

</body>
</html>