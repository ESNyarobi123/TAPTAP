<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TIPTAP Manager</title>
    
    <!-- Premium Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        
        body { 
            background: #0f0a1e;
            min-height: 100vh;
        }

        /* Premium Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.02);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, rgba(139, 92, 246, 0.5) 0%, rgba(6, 182, 212, 0.5) 100%);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, rgba(139, 92, 246, 0.8) 0%, rgba(6, 182, 212, 0.8) 100%);
        }

        /* Glassmorphism Effects */
        .glass {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        
        .glass-card {
            background: rgba(28, 22, 51, 0.6);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .bg-surface-900 {
            background: #0f0a1e;
        }

        /* Sidebar Styling */
        .sidebar-gradient {
            background: #0f0a1e;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        .sidebar-link {
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .sidebar-link:hover {
            background: linear-gradient(90deg, rgba(139, 92, 246, 0.1) 0%, transparent 100%);
            color: #fff;
        }
        
        .sidebar-link-active {
            background: linear-gradient(90deg, rgba(139, 92, 246, 0.2) 0%, transparent 100%);
            color: #fff !important;
        }
        
        .sidebar-link-active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 60%;
            background: linear-gradient(180deg, #8b5cf6 0%, #06b6d4 100%);
            border-radius: 0 4px 4px 0;
        }

        /* Gradient Text */
        .gradient-text {
            background: linear-gradient(135deg, #8b5cf6 0%, #06b6d4 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(139, 92, 246, 0.3); }
            50% { box-shadow: 0 0 40px rgba(139, 92, 246, 0.5); }
        }

        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-pulse-glow { animation: pulse-glow 2s ease-in-out infinite; }

        /* Card Hover Effects */
        .card-hover {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5);
            background: rgba(35, 28, 64, 0.8);
        }

        /* Hide scrollbar utility */
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="font-sans antialiased text-white">
    
    <!-- Overlay -->
    <div id="sidebar-overlay" onclick="closeSidebar()" class="fixed inset-0 bg-black/60 z-40 backdrop-blur-sm hidden transition-opacity duration-300 opacity-0 cursor-pointer"></div>

    <div class="flex min-h-screen">
        <!-- Premium Manager Sidebar -->
        <aside id="mobile-sidebar" style="transform: translateX(-100%);" class="fixed inset-y-0 left-0 z-50 w-72 sidebar-gradient flex flex-col h-screen shadow-2xl shadow-black/50 transition-transform duration-300 ease-out border-r border-white/5">
            <!-- Logo Area -->
            <div class="p-6 pb-4 flex justify-between items-center border-b border-white/5">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 bg-white/10 rounded-xl flex items-center justify-center shadow-lg overflow-hidden">
                        <img src="{{ asset('logo.png') }}" alt="TIPTAP Logo" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <span class="text-xl font-black text-white tracking-tight block leading-none">TIP<span class="gradient-text">TAP</span></span>
                        <span class="text-[10px] font-semibold text-white/40 uppercase tracking-[0.2em]">Manager Portal</span>
                    </div>
                </div>
                <button onclick="closeSidebar()" class="p-2.5 text-white/40 hover:text-white hover:bg-white/10 rounded-xl transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 py-6 custom-scrollbar overflow-y-auto">
                <div class="mb-4 px-6">
                    <p class="text-[10px] font-bold text-white/30 uppercase tracking-[0.2em]">Main Menu</p>
                </div>
                
                <a href="{{ route('manager.dashboard') }}" onclick="closeSidebar()" class="sidebar-link flex items-center gap-3 px-6 py-3.5 mx-3 rounded-xl {{ request()->routeIs('manager.dashboard') ? 'sidebar-link-active' : 'text-white/60' }}">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-violet-500/20 to-cyan-500/20 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ request()->routeIs('manager.dashboard') ? 'text-violet-400' : 'text-white/60' }}">
                            <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                        </svg>
                    </div>
                    <span class="font-semibold text-sm">Dashboard</span>
                </a>

                <a href="{{ route('manager.orders.live') }}" onclick="closeSidebar()" class="sidebar-link flex items-center gap-3 px-6 py-3.5 mx-3 rounded-xl {{ request()->routeIs('manager.orders.live') ? 'sidebar-link-active' : 'text-white/60' }}">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-amber-500/20 to-orange-500/20 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ request()->routeIs('manager.orders.live') ? 'text-amber-400' : 'text-white/60' }}">
                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                        </svg>
                    </div>
                    <span class="font-semibold text-sm">Live Orders</span>
                </a>

                <a href="{{ route('manager.menu.index') }}" onclick="closeSidebar()" class="sidebar-link flex items-center gap-3 px-6 py-3.5 mx-3 rounded-xl {{ request()->routeIs('manager.menu.index') ? 'sidebar-link-active' : 'text-white/60' }}">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-emerald-500/20 to-teal-500/20 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ request()->routeIs('manager.menu.index') ? 'text-emerald-400' : 'text-white/60' }}">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                        </svg>
                    </div>
                    <span class="font-semibold text-sm">Menu Management</span>
                </a>

                <a href="{{ route('manager.menu-image.index') }}" onclick="closeSidebar()" class="sidebar-link flex items-center gap-3 px-6 py-3.5 mx-3 rounded-xl {{ request()->routeIs('manager.menu-image.index') ? 'sidebar-link-active' : 'text-white/60' }}">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-rose-500/20 to-orange-500/20 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ request()->routeIs('manager.menu-image.index') ? 'text-rose-400' : 'text-white/60' }}">
                            <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                            <circle cx="9" cy="9" r="2"/>
                            <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                        </svg>
                    </div>
                    <span class="font-semibold text-sm">Menu Image</span>
                </a>

                <a href="{{ route('manager.waiters.index') }}" onclick="closeSidebar()" class="sidebar-link flex items-center gap-3 px-6 py-3.5 mx-3 rounded-xl {{ request()->routeIs('manager.waiters.index') ? 'sidebar-link-active' : 'text-white/60' }}">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-blue-500/20 to-indigo-500/20 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ request()->routeIs('manager.waiters.index') ? 'text-blue-400' : 'text-white/60' }}">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                    <span class="font-semibold text-sm">Waiters & Staff</span>
                </a>

                <a href="{{ route('manager.tables.index') }}" onclick="closeSidebar()" class="sidebar-link flex items-center gap-3 px-6 py-3.5 mx-3 rounded-xl {{ request()->routeIs('manager.tables.index') ? 'sidebar-link-active' : 'text-white/60' }}">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-purple-500/20 to-pink-500/20 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ request()->routeIs('manager.tables.index') ? 'text-purple-400' : 'text-white/60' }}">
                            <rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/>
                        </svg>
                    </div>
                    <span class="font-semibold text-sm">Tables & QR Codes</span>
                </a>

                <div class="mt-8 mb-4 px-6">
                    <p class="text-[10px] font-bold text-white/30 uppercase tracking-[0.2em]">Finance & Feedback</p>
                </div>

                <a href="{{ route('manager.payments.index') }}" onclick="closeSidebar()" class="sidebar-link flex items-center gap-3 px-6 py-3.5 mx-3 rounded-xl {{ request()->routeIs('manager.payments.index') ? 'sidebar-link-active' : 'text-white/60' }}">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-pink-500/20 to-rose-500/20 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ request()->routeIs('manager.payments.index') ? 'text-pink-400' : 'text-white/60' }}">
                            <rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>
                        </svg>
                    </div>
                    <span class="font-semibold text-sm">Payments</span>
                </a>

                <a href="{{ route('manager.feedback.index') }}" onclick="closeSidebar()" class="sidebar-link flex items-center gap-3 px-6 py-3.5 mx-3 rounded-xl {{ request()->routeIs('manager.feedback.index') ? 'sidebar-link-active' : 'text-white/60' }}">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-cyan-500/20 to-teal-500/20 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ request()->routeIs('manager.feedback.index') ? 'text-cyan-400' : 'text-white/60' }}">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                        </svg>
                    </div>
                    <span class="font-semibold text-sm">Feedback</span>
                </a>

                <a href="{{ route('manager.tips.index') }}" onclick="closeSidebar()" class="sidebar-link flex items-center gap-3 px-6 py-3.5 mx-3 rounded-xl {{ request()->routeIs('manager.tips.index') ? 'sidebar-link-active' : 'text-white/60' }}">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-amber-500/20 to-yellow-500/20 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ request()->routeIs('manager.tips.index') ? 'text-amber-400' : 'text-white/60' }}">
                            <circle cx="8" cy="8" r="6"/><path d="M18.09 10.37A6 6 0 1 1 10.34 18"/><path d="M7 6h1v4"/><path d="m16.71 13.88.7.71-2.82 2.82"/>
                        </svg>
                    </div>
                    <span class="font-semibold text-sm">Tips Management</span>
                </a>

                <a href="{{ route('manager.api.index') }}" onclick="closeSidebar()" class="sidebar-link flex items-center gap-3 px-6 py-3.5 mx-3 rounded-xl {{ request()->routeIs('manager.api.index') ? 'sidebar-link-active' : 'text-white/60' }}">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-violet-500/20 to-purple-500/20 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ request()->routeIs('manager.api.index') ? 'text-violet-400' : 'text-white/60' }}">
                            <rect width="5" height="5" x="3" y="3" rx="1"/><rect width="5" height="5" x="16" y="3" rx="1"/><rect width="5" height="5" x="3" y="16" rx="1"/><path d="M21 16h-3a2 2 0 0 0-2 2v3"/><path d="M21 21v.01"/><path d="M12 7v3a2 2 0 0 1-2 2H7"/><path d="M3 12h.01"/><path d="M12 3h.01"/><path d="M12 16v.01"/><path d="M16 12h1"/><path d="M21 12v.01"/><path d="M12 21v-1"/>
                        </svg>
                    </div>
                    <span class="font-semibold text-sm">QR & Mobile API</span>
                </a>
            </nav>

            <!-- User Profile Area -->
            <div class="p-4 border-t border-white/5">
                <div class="glass-card rounded-xl p-4 flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-violet-600 to-cyan-500 rounded-lg flex items-center justify-center font-bold text-white shadow-lg shadow-violet-500/20">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] font-medium text-white/40 truncate">Manager Account</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-2 text-white/40 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 min-h-screen flex flex-col w-full relative z-0 transition-all duration-300">
            <!-- Mobile Header -->
            <div class="md:hidden glass sticky top-0 z-30 px-4 py-3 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <button onclick="openSidebar()" class="flex items-center gap-2 px-3 py-2.5 bg-gradient-to-r from-violet-600 to-cyan-600 text-white rounded-xl shadow-lg shadow-violet-500/25 hover:shadow-violet-500/40 transition-all active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/>
                        </svg>
                    </button>
                    <span class="font-bold text-white/90 text-lg tracking-tight">TIP<span class="gradient-text">TAP</span></span>
                </div>
                    <div class="w-9 h-9 bg-white/10 rounded-xl flex items-center justify-center shadow-lg overflow-hidden">
                        <img src="{{ asset('logo.png') }}" alt="TIPTAP Logo" class="w-full h-full object-cover">
                    </div>
            </div>

            <!-- Desktop Header & Content -->
            <div class="p-4 lg:p-8 flex-1">
                <!-- Desktop Top Bar -->
                <div class="hidden md:flex justify-between items-center mb-8">
                    <div class="flex items-center gap-5">
                        <button onclick="openSidebar()" class="flex items-center gap-3 px-4 py-3 glass rounded-xl hover:bg-white/10 transition-all active:scale-95 group">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white/60 group-hover:text-violet-400 transition-colors">
                                <line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/>
                            </svg>
                            <span class="font-semibold text-sm text-white/80">Menu</span>
                        </button>
                        <div>
                            <p class="text-[11px] font-semibold text-violet-400 uppercase tracking-[0.15em] mb-1">Manager Portal</p>
                            <h1 class="text-3xl font-bold text-white tracking-tight">{{ $header ?? 'Dashboard' }}</h1>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-5">
                        <div class="glass px-4 py-2.5 rounded-xl flex items-center gap-3">
                            <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                            <span class="text-[11px] font-semibold text-white/80 uppercase tracking-wider">System Live</span>
                        </div>
                    </div>
                </div>

                {{ $slot }}
            </div>

            <!-- Toast Notifications -->
            @if(session('success'))
                <div id="toast-success" class="fixed bottom-8 right-8 z-[200] animate-float">
                    <div class="glass-card px-6 py-4 rounded-2xl border-emerald-500/20 flex items-center gap-4 shadow-2xl shadow-emerald-500/10">
                        <div class="w-10 h-10 bg-emerald-500/20 rounded-xl flex items-center justify-center text-emerald-400">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-white uppercase tracking-wider">Success</p>
                            <p class="text-sm text-white/60">{{ session('success') }}</p>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white/20 hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                        </button>
                    </div>
                </div>
                <script>setTimeout(() => document.getElementById('toast-success')?.remove(), 5000);</script>
            @endif

            @if(session('error'))
                <div id="toast-error" class="fixed bottom-8 right-8 z-[200] animate-float">
                    <div class="glass-card px-6 py-4 rounded-2xl border-rose-500/20 flex items-center gap-4 shadow-2xl shadow-rose-500/10">
                        <div class="w-10 h-10 bg-rose-500/20 rounded-xl flex items-center justify-center text-rose-400">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-white uppercase tracking-wider">Error</p>
                            <p class="text-sm text-white/60">{{ session('error') }}</p>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white/20 hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                        </button>
                    </div>
                </div>
                <script>setTimeout(() => document.getElementById('toast-error')?.remove(), 5000);</script>
            @endif
        </main>
    </div>

    <script>
        function openSidebar() {
            const sidebar = document.getElementById('mobile-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            sidebar.style.transform = 'translateX(0)';
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0');
            
            overlay.classList.remove('hidden');
            setTimeout(() => {
                overlay.classList.remove('opacity-0');
                overlay.classList.add('opacity-100');
            }, 10);
            
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            const sidebar = document.getElementById('mobile-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            sidebar.style.transform = 'translateX(-100%)';
            sidebar.classList.remove('translate-x-0');
            sidebar.classList.add('-translate-x-full');
            
            overlay.classList.remove('opacity-100');
            overlay.classList.add('opacity-0');
            setTimeout(() => {
                overlay.classList.add('hidden');
            }, 300);
            
            document.body.style.overflow = '';
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('mobile-sidebar');
            if (sidebar.style.transform === 'translateX(-100%)' || sidebar.classList.contains('-translate-x-full')) {
                openSidebar();
            } else {
                closeSidebar();
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeSidebar();
            }
        });

        window.addEventListener('load', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
