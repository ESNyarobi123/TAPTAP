@props(['title' => 'TIPTAP |  '])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%238b5cf6' stroke-width='2'><path d='M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2'/><path d='M7 2v20'/><path d='M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7'/></svg>">

        <!-- Premium Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            * {
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            }
            
            body { 
                background: #0f0a1e;
                min-height: 100vh;
            }

            /* Gradient Text */
            .gradient-text {
                background: linear-gradient(135deg, #8b5cf6 0%, #06b6d4 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            /* Glassmorphism */
            .glass-card {
                background: rgba(28, 22, 51, 0.6);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.08);
            }

            /* Animations */
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-15px); }
            }
            
            @keyframes pulse-glow {
                0%, 100% { box-shadow: 0 0 30px rgba(139, 92, 246, 0.3); }
                50% { box-shadow: 0 0 60px rgba(139, 92, 246, 0.5); }
            }

            .animate-float { animation: float 6s ease-in-out infinite; }
            .animate-pulse-glow { animation: pulse-glow 3s ease-in-out infinite; }

            /* Mobile Optimizations */
            @media (max-width: 640px) {
                .glass-card {
                    background: rgba(28, 22, 51, 0.8);
                    backdrop-filter: blur(10px);
                    -webkit-backdrop-filter: blur(10px);
                }
                .animate-float {
                    animation: none;
                }
            }

            /* Touch-friendly tap targets */
            @media (hover: none) {
                button, a {
                    min-height: 44px;
                }
            }
            ::-webkit-scrollbar { width: 6px; }
            ::-webkit-scrollbar-track { background: rgba(255, 255, 255, 0.02); }
            ::-webkit-scrollbar-thumb {
                background: linear-gradient(180deg, rgba(139, 92, 246, 0.5) 0%, rgba(6, 182, 212, 0.5) 100%);
                border-radius: 10px;
            }
        </style>
    </head>
    <body class="font-sans antialiased text-white">
        <!-- Background Effects -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden">
            <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-violet-600/10 rounded-full blur-[150px] -mr-48 -mt-48"></div>
            <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-cyan-600/10 rounded-full blur-[150px] -ml-48 -mb-48"></div>
        </div>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-4 sm:pt-0 px-3 sm:px-4 relative z-10">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-2 sm:gap-3 group mb-6 sm:mb-8">
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-violet-600 to-cyan-500 rounded-2xl flex items-center justify-center shadow-xl shadow-violet-500/30 transform group-hover:rotate-12 transition-all duration-500 animate-pulse-glow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="utensils">
                        <path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"></path>
                        <path d="M7 2v20"></path>
                        <path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"></path>
                    </svg>
                </div>
                <div>
                    <span class="text-xl sm:text-2xl font-black text-white tracking-tight block leading-none">TIP<span class="gradient-text">TAP</span></span>
                </div>
            </a>

            <!-- Content Card -->
            <div class="w-full sm:max-w-md glass-card rounded-2xl sm:rounded-3xl p-5 sm:p-8 shadow-2xl shadow-black/50 relative overflow-hidden">
                <!-- Decorative elements inside card -->
                <div class="absolute -top-10 -right-10 w-32 h-32 sm:w-40 sm:h-40 bg-violet-500/10 rounded-full blur-2xl sm:blur-3xl"></div>
                <div class="absolute -bottom-10 -left-10 w-32 h-32 sm:w-40 sm:h-40 bg-cyan-500/10 rounded-full blur-2xl sm:blur-3xl"></div>
                
                <div class="relative z-10">
                    {{ $slot }}
                </div>
            </div>

            <!-- Footer -->
            <p class="mt-6 sm:mt-8 text-white/30 text-xs font-medium text-center">&copy; {{ date('Y') }} TIPTAP. All rights reserved.</p>
        </div>
    </body>
</html>
