<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'TAPTAP Waiter') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="font-sans antialiased bg-[#F8FAFC] text-deep-blue">
    <div class="flex min-h-screen">
        <!-- Professional Waiter Sidebar -->
        <aside class="w-72 bg-white border-r border-gray-100 flex flex-col fixed h-screen z-50">
            <!-- Logo Area -->
            <div class="p-10 mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-red rounded-xl flex items-center justify-center shadow-lg shadow-orange-red/20">
                        <i data-lucide="utensils-crosses" class="w-6 h-6 text-white"></i>
                    </div>
                    <span class="text-2xl font-black text-deep-blue tracking-tighter">TAPTAP</span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-6 space-y-1">
                <p class="px-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Service Menu</p>
                
                <a href="{{ route('waiter.dashboard') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition-all {{ request()->routeIs('waiter.dashboard') ? 'bg-orange-red text-white shadow-xl shadow-orange-red/20' : 'text-gray-400 hover:bg-gray-50 hover:text-deep-blue' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span class="font-bold text-sm">Dashboard</span>
                </a>
                
                <a href="{{ route('waiter.menu') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition-all {{ request()->routeIs('waiter.menu') ? 'bg-orange-red text-white shadow-xl shadow-orange-red/20' : 'text-gray-400 hover:bg-gray-50 hover:text-deep-blue' }}">
                    <i data-lucide="book-open" class="w-5 h-5"></i>
                    <span class="font-bold text-sm">View Menu</span>
                </a>

                <a href="{{ route('waiter.orders') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition-all {{ request()->routeIs('waiter.orders') ? 'bg-orange-red text-white shadow-xl shadow-orange-red/20' : 'text-gray-400 hover:bg-gray-50 hover:text-deep-blue' }} group">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                    <span class="font-bold text-sm">Active Orders</span>
                </a>

                <a href="{{ route('waiter.dashboard') }}#requests" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition-all text-gray-400 hover:bg-gray-50 hover:text-deep-blue group">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    <span class="font-bold text-sm">Customer Calls</span>
                    @php
                        $pendingCount = \App\Models\CustomerRequest::where('status', 'pending')->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="ml-auto bg-orange-red text-[10px] font-black px-2 py-0.5 rounded-full text-white">{{ $pendingCount }}</span>
                    @endif
                </a>

                <div class="pt-8">
                    <p class="px-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Personal Stats</p>
                    
                    <a href="{{ route('waiter.tips') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition-all {{ request()->routeIs('waiter.tips') ? 'bg-orange-red text-white shadow-xl shadow-orange-red/20' : 'text-gray-400 hover:bg-gray-50 hover:text-deep-blue' }}">
                        <i data-lucide="coins" class="w-5 h-5"></i>
                        <span class="font-bold text-sm">My Tips</span>
                    </a>

                    <a href="{{ route('waiter.ratings') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition-all {{ request()->routeIs('waiter.ratings') ? 'bg-orange-red text-white shadow-xl shadow-orange-red/20' : 'text-gray-400 hover:bg-gray-50 hover:text-deep-blue' }}">
                        <i data-lucide="star" class="w-5 h-5"></i>
                        <span class="font-bold text-sm">My Ratings</span>
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 ml-72 p-12">
            <!-- Top Header -->
            <div class="flex justify-between items-center mb-12">
                <div>
                    <h1 class="text-4xl font-black text-deep-blue tracking-tighter">{{ $header ?? 'Dashboard' }}</h1>
                    <p class="text-gray-400 font-bold uppercase tracking-[0.2em] text-[10px] mt-1">TAPTAP Waiter Portal • {{ Auth::user()->restaurant->name }}</p>
                </div>
                
                <div class="flex items-center gap-6">
                    <div class="bg-white px-6 py-3 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-3">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="text-[10px] font-black text-deep-blue uppercase tracking-widest">Shift Active</span>
                    </div>

                    <div class="h-12 w-px bg-gray-100"></div>

                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <p class="text-sm font-black text-deep-blue">{{ Auth::user()->name }}</p>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Waiter Account</p>
                        </div>
                        <div class="relative group">
                            <button class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center font-black text-orange-red shadow-sm border border-orange-100 hover:bg-orange-red hover:text-white transition-all">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </button>
                            
                            <!-- Simple Dropdown for Logout -->
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-2xl border border-gray-50 py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-[60]">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full px-6 py-3 text-left text-sm font-bold text-red-500 hover:bg-red-50 transition-all flex items-center gap-3">
                                        <i data-lucide="log-out" class="w-4 h-4"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{ $slot }}
        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
