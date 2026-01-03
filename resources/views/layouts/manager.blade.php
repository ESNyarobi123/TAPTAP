<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'TAPTAP Manager') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="font-sans antialiased bg-[#F8FAFC] text-deep-blue">
    <div class="flex min-h-screen">
        <!-- Professional Manager Sidebar -->
        <aside class="w-72 bg-white border-r border-gray-100 flex flex-col fixed h-screen z-50">
            <!-- Logo Area -->
            <div class="p-10 mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-deep-blue rounded-xl flex items-center justify-center shadow-lg shadow-deep-blue/20">
                        <i data-lucide="layout-grid" class="w-6 h-6 text-white"></i>
                    </div>
                    <span class="text-2xl font-black text-deep-blue tracking-tighter">TAPTAP</span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-6 space-y-1">
                <p class="px-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Main Menu</p>
                
                <a href="{{ route('manager.dashboard') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition-all {{ request()->routeIs('manager.dashboard') ? 'bg-deep-blue text-white shadow-xl shadow-deep-blue/20' : 'text-gray-400 hover:bg-gray-50 hover:text-deep-blue' }}">
                    <i data-lucide="home" class="w-5 h-5"></i>
                    <span class="font-bold text-sm">Dashboard</span>
                </a>
                
                <a href="{{ route('manager.orders.live') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition-all {{ request()->routeIs('manager.orders.live') ? 'bg-deep-blue text-white shadow-xl shadow-deep-blue/20' : 'text-gray-400 hover:bg-gray-50 hover:text-deep-blue' }}">
                    <i data-lucide="zap" class="w-5 h-5"></i>
                    <span class="font-bold text-sm">Live Orders</span>
                </a>

                <a href="{{ route('manager.menu.index') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition-all {{ request()->routeIs('manager.menu.index') ? 'bg-deep-blue text-white shadow-xl shadow-deep-blue/20' : 'text-gray-400 hover:bg-gray-50 hover:text-deep-blue' }}">
                    <i data-lucide="book-open" class="w-5 h-5"></i>
                    <span class="font-bold text-sm">Menu Management</span>
                </a>

                <a href="{{ route('manager.waiters.index') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition-all {{ request()->routeIs('manager.waiters.index') ? 'bg-deep-blue text-white shadow-xl shadow-deep-blue/20' : 'text-gray-400 hover:bg-gray-50 hover:text-deep-blue' }}">
                    <i data-lucide="users" class="w-5 h-5"></i>
                    <span class="font-bold text-sm">Waiters & Staff</span>
                </a>

                <a href="{{ route('manager.tables.index') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition-all {{ request()->routeIs('manager.tables.index') ? 'bg-deep-blue text-white shadow-xl shadow-deep-blue/20' : 'text-gray-400 hover:bg-gray-50 hover:text-deep-blue' }}">
                    <i data-lucide="layout-grid" class="w-5 h-5"></i>
                    <span class="font-bold text-sm">Tables & QR Codes</span>
                </a>

                <div class="pt-8">
                    <p class="px-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Finance & Feedback</p>
                    
                    <a href="{{ route('manager.payments.index') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition-all {{ request()->routeIs('manager.payments.index') ? 'bg-deep-blue text-white shadow-xl shadow-deep-blue/20' : 'text-gray-400 hover:bg-gray-50 hover:text-deep-blue' }}">
                        <i data-lucide="credit-card" class="w-5 h-5"></i>
                        <span class="font-bold text-sm">Payments</span>
                    </a>

                    <a href="{{ route('manager.feedback.index') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition-all {{ request()->routeIs('manager.feedback.index') ? 'bg-deep-blue text-white shadow-xl shadow-deep-blue/20' : 'text-gray-400 hover:bg-gray-50 hover:text-deep-blue' }}">
                        <i data-lucide="message-square" class="w-5 h-5"></i>
                        <span class="font-bold text-sm">Feedback</span>
                    </a>

                    <a href="{{ route('manager.api.index') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition-all {{ request()->routeIs('manager.api.index') ? 'bg-deep-blue text-white shadow-xl shadow-deep-blue/20' : 'text-gray-400 hover:bg-gray-50 hover:text-deep-blue' }}">
                        <i data-lucide="qr-code" class="w-5 h-5"></i>
                        <span class="font-bold text-sm">QR & Mobile API</span>
                    </a>
                </div>
            </nav>

            <!-- Bottom Profile Area -->
            <div class="p-6 border-t border-gray-50">
                <div class="flex items-center gap-4 mb-6 px-4">
                    <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center font-black text-deep-blue">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-deep-blue font-black text-xs truncate">{{ Auth::user()->name }}</p>
                        <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">Manager</p>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full bg-red-50 hover:bg-red-500 text-red-500 hover:text-white py-3 rounded-xl font-bold text-xs transition-all flex items-center justify-center gap-2">
                        <i data-lucide="log-out" class="w-4 h-4"></i> LOGOUT
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 ml-72 p-12">
            <!-- Top Header -->
            <div class="flex justify-between items-center mb-12">
                <div>
                    <h1 class="text-4xl font-black text-deep-blue tracking-tighter">{{ $header ?? 'Dashboard' }}</h1>
                    <p class="text-gray-400 font-bold uppercase tracking-[0.2em] text-[10px] mt-1">Restaurant Management Portal</p>
                </div>
                
                <div class="flex items-center gap-4">
                    <div class="bg-white px-6 py-3 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-3">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="text-xs font-black text-deep-blue uppercase tracking-widest">System Live</span>
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
