<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'TAPTAP Admin') }}</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <style>
        body { 
            font-family: 'Outfit', sans-serif; 
            background: linear-gradient(135deg, #F0F4F8 0%, #E2E8F0 100%);
            min-height: 100vh;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }
        .sidebar-link-active {
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.2) 0%, rgba(59, 130, 246, 0) 100%);
            color: #60A5FA !important;
            border-left: 4px solid #3B82F6;
            box-shadow: inset 4px 0 15px -5px rgba(59, 130, 246, 0.5);
        }
        .sidebar-link-active i {
            color: #60A5FA !important;
        }
        .card-shadow {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
        }
        .sidebar-nav {
            height: calc(100vh - 200px);
            overflow-y: auto;
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
        .stat-card-blue { border-top: 4px solid #3B82F6; }
        .stat-card-emerald { border-top: 4px solid #10B981; }
        .stat-card-purple { border-top: 4px solid #8B5CF6; }
        .stat-card-orange { border-top: 4px solid #F59E0B; }
    </style>
</head>
<body class="font-sans antialiased text-slate-900">
    <div class="flex min-h-screen">
        <!-- Premium Dark Sidebar -->
        <aside class="w-72 bg-[#0F172A] text-slate-300 flex flex-col fixed h-screen z-50 shadow-2xl">
            <!-- Logo Area -->
            <div class="p-8 mb-2">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-600/20">
                        <i data-lucide="shield-check" class="w-6 h-6 text-white"></i>
                    </div>
                    <div>
                        <span class="text-xl font-black text-white tracking-tighter block leading-none">TAP<span class="text-blue-500">TAP</span></span>
                        <span class="text-[9px] font-bold text-slate-500 uppercase tracking-[0.2em]">Super Admin</span>
                    </div>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 space-y-1 custom-scrollbar pb-10 sidebar-nav">
                <div class="mt-8 mb-4 px-8">
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Main Command</p>
                </div>
                
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-8 py-3 transition-all {{ request()->routeIs('admin.dashboard') ? 'sidebar-link-active' : 'hover:text-white hover:bg-white/5' }}">
                    <i data-lucide="layout-grid" class="w-5 h-5"></i>
                    <span class="font-bold text-sm">Dashboard</span>
                </a>

                <a href="{{ route('admin.restaurants.index') }}" class="flex items-center gap-3 px-8 py-3 transition-all {{ request()->routeIs('admin.restaurants.*') ? 'sidebar-link-active' : 'hover:text-white hover:bg-white/5' }}">
                    <i data-lucide="utensils" class="w-5 h-5"></i>
                    <span class="font-bold text-sm">Restaurants</span>
                </a>

                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-8 py-3 transition-all {{ request()->routeIs('admin.users.*') ? 'sidebar-link-active' : 'hover:text-white hover:bg-white/5' }}">
                    <i data-lucide="users" class="w-5 h-5"></i>
                    <span class="font-bold text-sm">User Management</span>
                </a>

                <div class="mt-8 mb-4 px-8">
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Financials</p>
                </div>

                <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-8 py-3 transition-all {{ request()->routeIs('admin.orders.*') ? 'sidebar-link-active' : 'hover:text-white hover:bg-white/5' }}">
                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                    <span class="font-bold text-sm">All Orders</span>
                </a>

                <a href="{{ route('admin.payments.index') }}" class="flex items-center gap-3 px-8 py-3 transition-all {{ request()->routeIs('admin.payments.*') ? 'sidebar-link-active' : 'hover:text-white hover:bg-white/5' }}">
                    <i data-lucide="credit-card" class="w-5 h-5"></i>
                    <span class="font-bold text-sm">Payments & APIs</span>
                </a>

                <a href="{{ route('admin.withdrawals.index') }}" class="flex items-center gap-3 px-8 py-3 transition-all {{ request()->routeIs('admin.withdrawals.*') ? 'sidebar-link-active' : 'hover:text-white hover:bg-white/5' }}">
                    <i data-lucide="wallet" class="w-5 h-5"></i>
                    <span class="font-bold text-sm">Withdrawals</span>
                </a>

                <div class="mt-8 mb-4 px-8">
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">System Control</p>
                </div>

                <a href="{{ route('admin.bots.index') }}" class="flex items-center gap-3 px-8 py-3 transition-all {{ request()->routeIs('admin.bots.*') ? 'sidebar-link-active' : 'hover:text-white hover:bg-white/5' }}">
                    <i data-lucide="bot" class="w-5 h-5"></i>
                    <span class="font-bold text-sm">Bot Control</span>
                </a>

                <a href="{{ route('admin.notifications.index') }}" class="flex items-center gap-3 px-8 py-3 transition-all {{ request()->routeIs('admin.notifications.*') ? 'sidebar-link-active' : 'hover:text-white hover:bg-white/5' }}">
                    <i data-lucide="send" class="w-5 h-5"></i>
                    <span class="font-bold text-sm">Push Notifications</span>
                </a>

                <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-8 py-3 transition-all {{ request()->routeIs('admin.settings.*') ? 'sidebar-link-active' : 'hover:text-white hover:bg-white/5' }}">
                    <i data-lucide="settings" class="w-5 h-5"></i>
                    <span class="font-bold text-sm">System Settings</span>
                </a>
            </nav>

            <!-- Logout Area -->
            <div class="p-6 border-t border-white/5">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-6 py-4 rounded-2xl text-slate-400 hover:text-red-400 hover:bg-red-400/10 transition-all font-bold text-sm group">
                        <i data-lucide="log-out" class="w-5 h-5 group-hover:-translate-x-1 transition-transform"></i>
                        <span>Sign Out</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 ml-72 p-10">
            <!-- Top Header -->
            <div class="flex justify-between items-center mb-12">
                <div>
                    <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] mb-1">System Overview</p>
                    <h1 class="text-4xl font-black text-slate-900 tracking-tighter">{{ $header ?? 'Dashboard' }}</h1>
                </div>
                
                <div class="flex items-center gap-6">
                    <div class="bg-white/80 backdrop-blur-md px-5 py-2.5 rounded-2xl border border-white flex items-center gap-3 shadow-sm">
                        <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                        <span class="text-[10px] font-black text-slate-900 uppercase tracking-widest">System Online</span>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <p class="text-xs font-black text-slate-900 leading-none mb-1">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest">SUPER ADMIN</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-600/20 text-white font-black">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-8 p-4 bg-emerald-500 text-white rounded-2xl font-bold text-sm flex items-center gap-3 shadow-lg shadow-emerald-500/20 animate-in fade-in slide-in-from-top-4 duration-300">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-8 p-4 bg-red-500 text-white rounded-2xl font-bold text-sm flex items-center gap-3 shadow-lg shadow-red-500/20 animate-in fade-in slide-in-from-top-4 duration-300">
                    <i data-lucide="alert-circle" class="w-5 h-5"></i>
                    {{ session('error') }}
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
