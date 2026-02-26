<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TIPTAP ORDER @if(isset($restaurant)) · {{ $restaurant->name }} @endif</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        body { background: #0f0a1e; min-height: 100vh; }
        .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.08); }
        .glass-card { background: rgba(28, 22, 51, 0.6); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.05); }
        .gradient-text { background: linear-gradient(135deg, #8b5cf6 0%, #06b6d4 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .card-hover { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5); background: rgba(35, 28, 64, 0.8); }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: rgba(255, 255, 255, 0.02); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: linear-gradient(180deg, rgba(139, 92, 246, 0.5) 0%, rgba(6, 182, 212, 0.5) 100%); border-radius: 10px; }
    </style>
</head>
<body class="font-sans antialiased text-white min-h-screen">
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-violet-600/10 rounded-full blur-[150px] -mr-48 -mt-48"></div>
        <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-cyan-600/10 rounded-full blur-[150px] -ml-48 -mb-48"></div>
    </div>

    <header class="relative z-20 border-b border-white/10 glass">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 py-4 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="text-xl font-black text-white tracking-tight">TIPTAP <span class="gradient-text">ORDER</span></span>
                @if(isset($restaurant))
                    <span class="text-white/50 font-medium hidden sm:inline">· {{ $restaurant->name }}</span>
                @endif
            </div>
            <form action="{{ route('order-portal.logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 rounded-xl text-white/70 hover:text-white hover:bg-white/10 transition-all text-sm font-semibold">Toka</button>
            </form>
        </div>
    </header>

    <main class="relative z-10 max-w-[1600px] mx-auto px-4 sm:px-6 py-8">
        @if (session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm">{{ session('success') }}</div>
        @endif
        @yield('content')
    </main>
</body>
</html>
