<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TIPTAP Waiter</title>
    
    <!-- Premium Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
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
        .sidebar-link { min-height: 44px; }
        .sidebar-link:focus { outline: none; box-shadow: 0 0 0 2px #0f0a1e, 0 0 0 4px rgba(139, 92, 246, 0.6); }
        #mobile-sidebar { transition: transform 0.3s ease-out, width 0.3s ease-out; }
        #mobile-sidebar.sidebar-closed-mobile { transform: translateX(-100%) !important; }
        #mobile-sidebar.sidebar-open { transform: translateX(0) !important; visibility: visible !important; }
        @media (min-width: 768px) {
            #mobile-sidebar,
            #mobile-sidebar.sidebar-closed-mobile { transform: translateX(0) !important; visibility: visible !important; }
            #mobile-sidebar.sidebar-collapsed { width: 5rem !important; }
            main#main-content { margin-left: 18rem; }
            body.sidebar-collapsed-main main#main-content { margin-left: 5rem; }
        }
        body.sidebar-mobile-open #sidebar-overlay { display: block !important; opacity: 1 !important; pointer-events: auto !important; }
        #mobile-sidebar.sidebar-collapsed { width: 5rem; }
        #mobile-sidebar.sidebar-collapsed .sidebar-link span,
        #mobile-sidebar.sidebar-collapsed .sidebar-label,
        #mobile-sidebar.sidebar-collapsed .sidebar-logo-text,
        #mobile-sidebar.sidebar-collapsed .sidebar-user-text,
        #mobile-sidebar.sidebar-collapsed .sidebar-link .absolute { display: none !important; }
        #mobile-sidebar.sidebar-collapsed .sidebar-link { justify-content: center; padding-left: 0; padding-right: 0; margin-left: 0.5rem; margin-right: 0.5rem; }
        #mobile-sidebar.sidebar-collapsed .sidebar-link > div:first-child { margin: 0; }
        #mobile-sidebar.sidebar-collapsed nav .px-6 { padding-left: 0; padding-right: 0; }
        #mobile-sidebar.sidebar-collapsed .sidebar-user-area .flex-1 { display: none; }
        #mobile-sidebar.sidebar-collapsed .sidebar-user-area { justify-content: center; }
        body.sidebar-collapsed-main main#main-content { margin-left: 5rem; }
    </style>
</head>
<body class="font-sans antialiased text-white min-h-screen pt-[env(safe-area-inset-top)] pl-[env(safe-area-inset-left)] pr-[env(safe-area-inset-right)] pb-[env(safe-area-inset-bottom)]">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-[100] focus:px-4 focus:py-2 focus:bg-violet-600 focus:text-white focus:rounded-xl focus:ring-2 focus:ring-violet-400 focus:ring-offset-2 focus:ring-offset-[#0f0a1e] focus:outline-none">Skip to main content</a>
    
    <!-- Overlay (mobile only) -->
    <div id="sidebar-overlay" onclick="closeSidebar()" class="fixed inset-0 bg-black/60 z-40 backdrop-blur-sm hidden md:hidden transition-opacity duration-300 opacity-0 cursor-pointer" aria-hidden="true"></div>

    <div class="flex min-h-screen">
        <!-- Premium Waiter Sidebar: drawer on mobile, persistent on md+ with toggle -->
        <aside id="mobile-sidebar" class="fixed inset-y-0 left-0 z-[100] w-72 sidebar-gradient flex flex-col h-screen shadow-2xl shadow-black/50 border-r border-white/5 sidebar-closed-mobile" style="width: 18rem;">
            <div class="p-6 pb-4 flex justify-between items-center border-b border-white/5 shrink-0">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-11 h-11 flex shrink-0 items-center justify-center overflow-hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="utensils">
                            <path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"></path>
                            <path d="M7 2v20"></path>
                            <path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"></path>
                        </svg>
                    </div>
                    <div class="sidebar-logo-text min-w-0">
                        <span class="text-xl font-black text-white tracking-tight block leading-none">TIP<span class="gradient-text">TAP</span></span>
                        <span class="text-[10px] font-semibold text-white/40 uppercase tracking-[0.2em]">Waiter Portal</span>
                    </div>
                </div>
                <div class="flex items-center gap-1 shrink-0">
                    <button type="button" id="sidebar-toggle" class="hidden md:flex min-h-[44px] min-w-[44px] items-center justify-center p-2.5 text-white/40 hover:text-white hover:bg-white/10 rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 focus:ring-offset-[#0f0a1e]" aria-label="Collapse sidebar" title="Collapse sidebar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" id="sidebar-toggle-icon-collapse" title="Collapse"><path d="m15 18-6-6 6-6"/></svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" id="sidebar-toggle-icon-expand" class="hidden" title="Expand"><path d="m9 18 6-6-6-6"/></svg>
                    </button>
                    <button type="button" onclick="closeSidebar()" class="md:hidden min-h-[44px] min-w-[44px] inline-flex items-center justify-center p-2.5 text-white/40 hover:text-red-400 hover:bg-red-500/10 rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 focus:ring-offset-[#0f0a1e]" aria-label="Close menu">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <nav class="flex-1 py-6 custom-scrollbar overflow-y-auto overflow-x-hidden">
                <div class="mb-4 px-6 sidebar-label">
                    <p class="text-[10px] font-bold text-white/30 uppercase tracking-[0.2em]">Main Menu</p>
                </div>
                
                <a href="{{ route('waiter.dashboard') }}" onclick="closeSidebar()" class="sidebar-link flex items-center gap-3 px-6 py-3.5 mx-3 rounded-xl {{ request()->routeIs('waiter.dashboard') ? 'sidebar-link-active' : 'text-white/60' }}">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-violet-500/20 to-cyan-500/20 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ request()->routeIs('waiter.dashboard') ? 'text-violet-400' : 'text-white/60' }}">
                            <rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/>
                        </svg>
                    </div>
                    <span class="font-semibold text-sm">Dashboard</span>
                    @if(request()->routeIs('waiter.dashboard'))
                        <div class="absolute right-3 w-1.5 h-8 bg-gradient-to-b from-violet-500 to-cyan-500 rounded-full"></div>
                    @endif
                </a>

                <a href="{{ route('waiter.menu') }}" onclick="closeSidebar()" class="sidebar-link flex items-center gap-3 px-6 py-3.5 mx-3 rounded-xl {{ request()->routeIs('waiter.menu') ? 'sidebar-link-active' : 'text-white/60' }}">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-emerald-500/20 to-teal-500/20 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ request()->routeIs('waiter.menu') ? 'text-emerald-400' : 'text-white/60' }}">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                        </svg>
                    </div>
                    <span class="font-semibold text-sm">Menu Card</span>
                </a>

                <a href="{{ route('waiter.orders') }}" onclick="closeSidebar()" class="sidebar-link flex items-center gap-3 px-6 py-3.5 mx-3 rounded-xl {{ request()->routeIs('waiter.orders') ? 'sidebar-link-active' : 'text-white/60' }}">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-blue-500/20 to-indigo-500/20 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ request()->routeIs('waiter.orders') ? 'text-blue-400' : 'text-white/60' }}">
                            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>
                        </svg>
                    </div>
                    <span class="font-semibold text-sm">Active Orders</span>
                </a>

                <a href="{{ route('waiter.dashboard') }}#requests" onclick="closeSidebar()" class="sidebar-link flex items-center gap-3 px-6 py-3.5 mx-3 rounded-xl text-white/60">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-rose-500/20 to-pink-500/20 flex items-center justify-center relative">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white/60">
                            <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                        </svg>
                        @php
                            $pendingCount = \App\Models\CustomerRequest::where('status', 'pending')->count();
                        @endphp
                        @if($pendingCount > 0)
                            <span class="absolute -top-1 -right-1 w-4 h-4 bg-rose-500 rounded-full flex items-center justify-center text-[9px] font-bold text-white animate-pulse">{{ $pendingCount }}</span>
                        @endif
                    </div>
                    <span class="font-semibold text-sm">Customer Calls</span>
                    @if($pendingCount > 0)
                        <span class="ml-auto bg-rose-500/20 text-rose-400 text-[10px] font-bold px-2.5 py-1 rounded-full">{{ $pendingCount }} New</span>
                    @endif
                </a>

                <a href="{{ route('waiter.handover') }}" onclick="closeSidebar()" class="sidebar-link flex items-center gap-3 px-6 py-3.5 mx-3 rounded-xl {{ request()->routeIs('waiter.handover') ? 'sidebar-link-active' : 'text-white/60' }}">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-teal-500/20 to-cyan-500/20 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ request()->routeIs('waiter.handover') ? 'text-teal-400' : 'text-white/60' }}">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                    <span class="font-semibold text-sm">Hand Over Tables</span>
                    @if(request()->routeIs('waiter.handover'))
                        <div class="absolute right-3 w-1.5 h-8 bg-gradient-to-b from-teal-500 to-cyan-500 rounded-full"></div>
                    @endif
                </a>

                <div class="mt-8 mb-4 px-6 sidebar-label">
                    <p class="text-[10px] font-bold text-white/30 uppercase tracking-[0.2em]">Performance</p>
                </div>

                <a href="{{ route('waiter.tips') }}" onclick="closeSidebar()" class="sidebar-link flex items-center gap-3 px-6 py-3.5 mx-3 rounded-xl {{ request()->routeIs('waiter.tips') ? 'sidebar-link-active' : 'text-white/60' }}">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-amber-500/20 to-yellow-500/20 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ request()->routeIs('waiter.tips') ? 'text-amber-400' : 'text-white/60' }}">
                            <circle cx="8" cy="8" r="6"/><path d="M18.09 10.37A6 6 0 1 1 10.34 18"/><path d="M7 6h1v4"/><path d="m16.71 13.88.7.71-2.82 2.82"/>
                        </svg>
                    </div>
                    <span class="font-semibold text-sm">My Tips</span>
                </a>

                <a href="{{ route('waiter.ratings') }}" onclick="closeSidebar()" class="sidebar-link flex items-center gap-3 px-6 py-3.5 mx-3 rounded-xl {{ request()->routeIs('waiter.ratings') ? 'sidebar-link-active' : 'text-white/60' }}">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-orange-500/20 to-red-500/20 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ request()->routeIs('waiter.ratings') ? 'text-orange-400' : 'text-white/60' }}">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                        </svg>
                    </div>
                    <span class="font-semibold text-sm">My Ratings</span>
                </a>

                <div class="mt-8 mb-4 px-6 sidebar-label">
                    <p class="text-[10px] font-bold text-white/30 uppercase tracking-[0.2em]">Account</p>
                </div>

                <a href="{{ route('waiter.history') }}" onclick="closeSidebar()" class="sidebar-link flex items-center gap-3 px-6 py-3.5 mx-3 rounded-xl {{ request()->routeIs('waiter.history') ? 'sidebar-link-active' : 'text-white/60' }}">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-slate-500/20 to-zinc-500/20 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ request()->routeIs('waiter.history') ? 'text-slate-300' : 'text-white/60' }}">
                            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0 11 18 0z"/>
                        </svg>
                    </div>
                    <span class="font-semibold text-sm">History</span>
                </a>
            </nav>

            <div class="p-4 border-t border-white/5 shrink-0 sidebar-user-area">
                <div class="glass-card rounded-xl p-4 flex items-center gap-3" x-data="{ open: false }" @click="open = !open" @click.outside="open = false">
                    <div class="w-10 h-10 bg-gradient-to-br from-violet-600 to-cyan-500 rounded-lg flex items-center justify-center font-bold text-white shadow-lg shadow-violet-500/20 shrink-0">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0 sidebar-user-text">
                        <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] font-medium text-white/40 truncate">Waiter Account</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white/40 transition-transform cursor-pointer" :class="open ? 'rotate-180' : ''">
                        <path d="m18 15-6-6-6 6"/>
                    </svg>

                    <!-- Dropdown -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95 translate-y-2"
                         x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                         class="absolute bottom-full left-0 w-full mb-2 bg-surface-900 rounded-xl shadow-xl border border-white/10 overflow-hidden z-50">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full px-4 py-3 text-left text-sm font-semibold text-red-400 hover:bg-red-500/10 transition-all flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/>
                                </svg>
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main id="main-content" class="flex-1 min-h-screen flex flex-col w-full relative z-0 transition-[margin] duration-300 md:ml-72" tabindex="-1">
            <!-- Mobile Header -->
            <div class="md:hidden glass sticky top-0 z-30 px-4 py-3 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <button type="button" onclick="openSidebar()" class="min-h-[44px] min-w-[44px] inline-flex items-center justify-center gap-2 px-3 py-2.5 bg-gradient-to-r from-violet-600 to-cyan-600 text-white rounded-xl shadow-lg shadow-violet-500/25 hover:shadow-violet-500/40 transition-all active:scale-95 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 focus:ring-offset-[#0f0a1e]" aria-label="Open menu">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/>
                        </svg>
                    </button>
                    <span class="font-bold text-white/90 text-lg tracking-tight">TIP<span class="gradient-text">TAP</span></span>
                </div>
                    <div class="w-9 h-9 flex items-center justify-center overflow-hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="utensils">
                            <path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"></path>
                            <path d="M7 2v20"></path>
                            <path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"></path>
                        </svg>
                    </div>
            </div>

            <!-- Desktop Header & Content -->
            <div class="p-4 lg:p-8 flex-1">
                <div class="hidden md:flex justify-between items-center mb-8">
                    <div class="flex items-center gap-5">
                        <button type="button" id="sidebar-toggle-top" class="min-h-[44px] min-w-[44px] inline-flex items-center justify-center p-2.5 glass rounded-xl hover:bg-white/10 transition-all focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 focus:ring-offset-[#0f0a1e]" aria-label="Toggle sidebar" title="Toggle sidebar">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16"/><path d="M4 12h16"/><path d="M4 18h16"/></svg>
                        </button>
                        <div>
                            <p class="text-[11px] font-semibold text-violet-400 uppercase tracking-[0.15em] mb-1">Waiter Portal</p>
                            <h1 class="text-3xl font-bold text-white tracking-tight">{{ $header ?? 'Dashboard' }}</h1>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-5">
                        <div class="glass px-4 py-2.5 rounded-xl flex items-center gap-3">
                            <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                            <span class="text-[11px] font-semibold text-white/80 uppercase tracking-wider">Connected</span>
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
                        <button type="button" onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white/20 hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-white/30 rounded" aria-label="Dismiss">×</button>
                    </div>
                </div>
                <script>setTimeout(() => document.getElementById('toast-error')?.remove(), 5000);</script>
            @endif
            @if(session('info'))
                <div id="toast-info" class="fixed bottom-8 right-8 z-[200] animate-float">
                    <div class="glass-card px-6 py-4 rounded-2xl border-cyan-500/20 flex items-center gap-4 shadow-2xl shadow-cyan-500/10">
                        <div class="w-10 h-10 bg-cyan-500/20 rounded-xl flex items-center justify-center text-cyan-400">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-white uppercase tracking-wider">Info</p>
                            <p class="text-sm text-white/60">{{ session('info') }}</p>
                        </div>
                        <button type="button" onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white/20 hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-white/30 rounded" aria-label="Dismiss">×</button>
                    </div>
                </div>
                <script>setTimeout(() => document.getElementById('toast-info')?.remove(), 5000);</script>
            @endif
        </main>
    </div>

    <script>
        function openSidebar() {
            const sidebar = document.getElementById('mobile-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            if (!sidebar || !overlay) return;
            sidebar.classList.remove('sidebar-closed-mobile');
            sidebar.classList.add('sidebar-open');
            document.body.classList.add('sidebar-mobile-open');
            overlay.classList.remove('hidden');
            overlay.classList.remove('opacity-0');
            overlay.classList.add('opacity-100');
            document.body.style.overflow = 'hidden';
        }
        function closeSidebar() {
            const sidebar = document.getElementById('mobile-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            if (!sidebar || !overlay) return;
            sidebar.classList.remove('sidebar-open');
            sidebar.classList.add('sidebar-closed-mobile');
            document.body.classList.remove('sidebar-mobile-open');
            overlay.classList.remove('opacity-100');
            overlay.classList.add('opacity-0');
            setTimeout(function() { overlay.classList.add('hidden'); }, 300);
            document.body.style.overflow = '';
        }
        function toggleSidebar() {
            if (document.getElementById('mobile-sidebar').classList.contains('sidebar-open')) closeSidebar();
            else openSidebar();
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeSidebar();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('#mobile-sidebar nav a');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    setTimeout(closeSidebar, 100);
                });
            });
        });

        var WAITER_SIDEBAR_KEY = 'waiterSidebarCollapsed';
        function isWaiterSidebarCollapsed() { return document.getElementById('mobile-sidebar').classList.contains('sidebar-collapsed'); }
        function setWaiterSidebarCollapsed(collapsed) {
            var s = document.getElementById('mobile-sidebar');
            var ic = document.getElementById('sidebar-toggle-icon-collapse');
            var ie = document.getElementById('sidebar-toggle-icon-expand');
            if (collapsed) {
                s.classList.add('sidebar-collapsed');
                document.body.classList.add('sidebar-collapsed-main');
                if (ic) ic.classList.add('hidden');
                if (ie) ie.classList.remove('hidden');
                try { localStorage.setItem(WAITER_SIDEBAR_KEY, '1'); } catch (e) {}
            } else {
                s.classList.remove('sidebar-collapsed');
                document.body.classList.remove('sidebar-collapsed-main');
                if (ic) ic.classList.remove('hidden');
                if (ie) ie.classList.add('hidden');
                try { localStorage.setItem(WAITER_SIDEBAR_KEY, '0'); } catch (e) {}
            }
        }
        function toggleWaiterSidebar() { setWaiterSidebarCollapsed(!isWaiterSidebarCollapsed()); }
        document.getElementById('sidebar-toggle') && document.getElementById('sidebar-toggle').addEventListener('click', toggleWaiterSidebar);
        document.getElementById('sidebar-toggle-top') && document.getElementById('sidebar-toggle-top').addEventListener('click', toggleWaiterSidebar);
        try { if (localStorage.getItem(WAITER_SIDEBAR_KEY) === '1') setWaiterSidebarCollapsed(true); } catch (e) {}

        window.addEventListener('load', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
