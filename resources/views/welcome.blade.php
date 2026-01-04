<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TAPTAP - The Future of Smart Dining & WhatsApp Ordering</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Outfit', 'sans-serif'],
                    },
                    colors: {
                        'primary': '#6366F1',
                        'secondary': '#0F172A',
                        'accent': '#10B981',
                        'whatsapp': '#25D366',
                        'premium-gold': '#D4AF37',
                    },
                    animation: {
                        'gradient': 'gradient 8s linear infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        gradient: {
                            '0%, 100%': { 'background-size': '200% 200%', 'background-position': 'left center' },
                            '50%': { 'background-size': '200% 200%', 'background-position': 'right center' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        .glass-nav {
            background: rgba(11, 15, 26, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .text-gradient {
            background: linear-gradient(to right, #818CF8, #34D399, #60A5FA);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-size: 200% auto;
            animation: gradient 5s linear infinite;
        }

        .hero-gradient {
            background: radial-gradient(circle at top right, rgba(99, 102, 241, 0.15), transparent),
                        radial-gradient(circle at bottom left, rgba(16, 185, 129, 0.1), transparent),
                        #0B0F1A;
        }

        .bento-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .bento-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.5);
            border-color: rgba(99, 102, 241, 0.3);
            background: rgba(255, 255, 255, 0.05);
        }

        .whatsapp-bubble {
            position: relative;
            background: #075E54;
            border-radius: 18px;
            padding: 10px 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            color: white;
        }

        .whatsapp-bubble::after {
            content: '';
            position: absolute;
            left: -10px;
            top: 10px;
            border-width: 10px 10px 10px 0;
            border-style: solid;
            border-color: transparent #075E54 transparent transparent;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #0B0F1A;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #6366F1;
            border-radius: 10px;
        }

        @media (max-width: 768px) {
            .hero-title { font-size: 3.5rem; line-height: 1; }
        }
    </style>
</head>
<body class="bg-[#0B0F1A] text-white overflow-x-hidden custom-scrollbar">
    
    <!-- Navigation -->
    <nav class="fixed w-full z-[100] glass-nav transition-all duration-500 py-4" id="navbar">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-3 group">
                    <div class="w-12 h-12 bg-primary rounded-2xl flex items-center justify-center shadow-xl shadow-primary/20 transform group-hover:rotate-12 transition-all duration-500">
                        <i data-lucide="zap" class="w-7 h-7 text-white fill-white/20"></i>
                    </div>
                    <div>
                        <span class="text-2xl font-black text-white tracking-tighter block leading-none">TAP<span class="text-primary">TAP</span></span>
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.3em]">Smart Dining</span>
                    </div>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden lg:flex items-center space-x-8">
                    <a href="#features" class="text-sm font-semibold text-slate-400 hover:text-white transition-colors">Features</a>
                    <a href="#how-it-works" class="text-sm font-semibold text-slate-400 hover:text-white transition-colors">How it Works</a>
                    <a href="#whatsapp-bot" class="text-sm font-semibold text-slate-400 hover:text-white transition-colors">WhatsApp Bot</a>
                    <a href="#pricing" class="text-sm font-semibold text-slate-400 hover:text-white transition-colors">Pricing</a>
                    
                    <div class="h-6 w-px bg-white/10 mx-2"></div>

                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="bg-white text-secondary px-6 py-3 rounded-xl font-bold hover:shadow-2xl hover:-translate-y-1 transition-all text-sm">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-bold text-white hover:text-primary transition-colors">Log in</a>
                            <a href="{{ route('restaurant.register') }}" class="bg-primary text-white px-8 py-4 rounded-2xl font-black shadow-lg shadow-primary/30 hover:bg-white hover:text-primary hover:shadow-white/20 hover:-translate-y-1 transition-all text-sm">
                                Join Now
                            </a>
                        @endauth
                    @endif
                </div>

                <!-- Mobile Menu Button -->
                <button class="lg:hidden p-2 text-white" id="mobile-menu-btn">
                    <i data-lucide="menu" class="w-8 h-8"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu Overlay -->
    <div class="fixed inset-0 z-[110] bg-[#0B0F1A] hidden flex-col p-8 lg:hidden" id="mobile-menu">
        <div class="flex justify-between items-center mb-12">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center">
                    <i data-lucide="zap" class="w-6 h-6 text-white"></i>
                </div>
                <span class="text-xl font-black text-white">TAPTAP</span>
            </div>
            <button id="close-menu-btn" class="text-white"><i data-lucide="x" class="w-8 h-8"></i></button>
        </div>
        <div class="flex flex-col gap-8 text-2xl font-bold text-white">
            <a href="#features" class="hover:text-primary">Features</a>
            <a href="#how-it-works" class="hover:text-primary">How it Works</a>
            <a href="#whatsapp-bot" class="hover:text-primary">WhatsApp Bot</a>
            <a href="#pricing" class="hover:text-primary">Pricing</a>
            <hr class="border-white/10">
            <a href="{{ route('login') }}">Log in</a>
            <a href="{{ route('restaurant.register') }}" class="bg-primary text-white text-center py-5 rounded-2xl">Get Started</a>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="relative pt-40 pb-20 lg:pt-56 lg:pb-40 overflow-hidden hero-gradient">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div data-aos="fade-right" data-aos-duration="1000">
                    <div class="inline-flex items-center px-4 py-2 rounded-full bg-primary/20 text-primary text-xs font-bold uppercase tracking-widest mb-8 border border-primary/20">
                        <span class="w-2 h-2 bg-primary rounded-full mr-3 animate-pulse"></span>
                        Reinventing Restaurant Experience
                    </div>
                    
                    <h1 class="text-6xl lg:text-8xl font-black text-white tracking-tighter mb-8 leading-[0.9] hero-title">
                        Dining <br/>
                        <span class="text-gradient">Redefined.</span>
                    </h1>
                    
                    <p class="text-xl text-slate-400 mb-12 max-w-xl leading-relaxed font-medium">
                        Empower your restaurant with a smart WhatsApp ordering system. No apps, no friction—just seamless dining from scan to pay.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row items-center gap-6">
                        <a href="{{ route('restaurant.register') }}" class="w-full sm:w-auto px-10 py-6 bg-primary text-white rounded-3xl font-black text-xl shadow-2xl shadow-primary/20 hover:bg-white hover:text-primary hover:scale-105 transition-all text-center group">
                            Start Free Trial
                            <i data-lucide="arrow-right" class="inline-block ml-2 w-6 h-6 group-hover:translate-x-1 transition-transform"></i>
                        </a>
                        <a href="#whatsapp-bot" class="w-full sm:w-auto px-10 py-6 bg-white/5 text-white border border-white/10 rounded-3xl font-black text-lg hover:border-primary hover:bg-white/10 transition-all flex items-center justify-center gap-3">
                            <i data-lucide="play-circle" class="w-6 h-6 text-primary"></i>
                            Live Demo
                        </a>
                    </div>

                    <!-- Logo Cloud -->
                    <div class="mt-20" data-aos="fade-up" data-aos-delay="400">
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] mb-8">Powering the best in Tanzania</p>
                        <div class="flex flex-wrap items-center gap-10 opacity-30 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-700">
                            <span class="text-xl font-black text-white tracking-tighter">SAMAKI<span class="text-primary">SAMAKI</span></span>
                            <span class="text-xl font-black text-white tracking-tighter">AKEMI</span>
                            <span class="text-xl font-black text-white tracking-tighter">CTFM</span>
                            <span class="text-xl font-black text-white tracking-tighter">ELEMENTS</span>
                        </div>
                    </div>

                    <div class="mt-16 flex items-center gap-12 border-t border-white/10 pt-12">
                        <div class="flex -space-x-3">
                            <img src="https://i.pravatar.cc/100?u=1" class="w-12 h-12 rounded-full border-4 border-[#0B0F1A]" alt="User">
                            <img src="https://i.pravatar.cc/100?u=2" class="w-12 h-12 rounded-full border-4 border-[#0B0F1A]" alt="User">
                            <img src="https://i.pravatar.cc/100?u=3" class="w-12 h-12 rounded-full border-4 border-[#0B0F1A]" alt="User">
                            <div class="w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center text-xs font-bold border-4 border-[#0B0F1A]">50+</div>
                        </div>
                        <p class="text-sm text-slate-400 font-semibold">Trusted by <span class="text-white">50+ Restaurants</span> in Tanzania</p>
                    </div>
                </div>

                <div class="relative" data-aos="zoom-in" data-aos-duration="1200">
                    <div class="relative z-20 animate-float">
                        <div class="bg-white/5 rounded-[3rem] p-4 shadow-[0_50px_100px_-20px_rgba(0,0,0,0.5)] border-8 border-white/5">
                            <img src="https://gemini-user-artifacts.s3.amazonaws.com/artifacts/a5dcb96f-18df-46ab-b822-2fcd7a7946c6/restaurant_ordering_hero_1767515979820.png" alt="TAPTAP Hero" class="rounded-[2.2rem] w-full object-cover aspect-[4/5]">
                        </div>
                    </div>
                    
                    <!-- Floating Elements -->
                    <div class="absolute -top-10 -right-10 bg-[#1E293B] p-6 rounded-3xl shadow-2xl z-30 border border-white/10 animate-pulse-slow">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-whatsapp/20 rounded-2xl flex items-center justify-center">
                                <i data-lucide="message-square" class="w-6 h-6 text-whatsapp"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-500 uppercase">New Order</p>
                                <p class="text-sm font-black text-white">Table #12 - Samaki</p>
                            </div>
                        </div>
                    </div>

                    <div class="absolute -bottom-10 -left-10 bg-[#1E293B] p-6 rounded-3xl shadow-2xl z-30 border border-white/10 animate-bounce-slow" style="animation: float 5s ease-in-out infinite reverse;">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-primary/20 rounded-2xl flex items-center justify-center">
                                <i data-lucide="credit-card" class="w-6 h-6 text-primary"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-500 uppercase">Payment Received</p>
                                <p class="text-sm font-black text-white">TZS 45,000 via TigoPesa</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 hidden lg:block">
            <div class="w-6 h-10 border-2 border-white/10 rounded-full flex justify-center p-1">
                <div class="w-1 h-2 bg-primary rounded-full animate-bounce"></div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 bg-[#0B0F1A] border-y border-white/5">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-12">
                <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="text-5xl font-black text-white mb-2">30%</div>
                    <p class="text-sm font-bold text-slate-500 uppercase tracking-widest">Faster Turnover</p>
                </div>
                <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="text-5xl font-black text-white mb-2">0</div>
                    <p class="text-sm font-bold text-slate-500 uppercase tracking-widest">App Downloads</p>
                </div>
                <div class="text-center" data-aos="fade-up" data-aos-delay="300">
                    <div class="text-5xl font-black text-white mb-2">100%</div>
                    <p class="text-sm font-bold text-slate-500 uppercase tracking-widest">Digital Accuracy</p>
                </div>
                <div class="text-center" data-aos="fade-up" data-aos-delay="400">
                    <div class="text-5xl font-black text-white mb-2">24/7</div>
                    <p class="text-sm font-bold text-slate-500 uppercase tracking-widest">Smart Support</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How it Works -->
    <section id="how-it-works" class="py-32 bg-[#0B0F1A]">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-24">
                <h2 class="text-primary font-black tracking-[0.3em] uppercase text-xs mb-4">The Process</h2>
                <h3 class="text-4xl md:text-5xl font-black text-white tracking-tighter mb-6">Simple for You, <br/>Magic for Customers.</h3>
            </div>

            <div class="grid md:grid-cols-3 gap-12">
                <div class="relative p-12 bento-card rounded-[3rem] group" data-aos="fade-up" data-aos-delay="100">
                    <div class="absolute -top-6 left-12 w-12 h-12 bg-primary text-white rounded-2xl flex items-center justify-center font-black text-xl shadow-lg shadow-primary/30">1</div>
                    <div class="w-16 h-16 bg-white/5 rounded-2xl flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
                        <i data-lucide="qr-code" class="w-8 h-8 text-primary"></i>
                    </div>
                    <h4 class="text-2xl font-black text-white mb-4">Scan QR Code</h4>
                    <p class="text-slate-400 font-medium leading-relaxed">Customer scans the unique QR code on their table. No app needed, just their camera.</p>
                </div>

                <div class="relative p-12 bento-card rounded-[3rem] group" data-aos="fade-up" data-aos-delay="200">
                    <div class="absolute -top-6 left-12 w-12 h-12 bg-whatsapp text-white rounded-2xl flex items-center justify-center font-black text-xl shadow-lg shadow-whatsapp/30">2</div>
                    <div class="w-16 h-16 bg-white/5 rounded-2xl flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
                        <i data-lucide="message-circle" class="w-8 h-8 text-whatsapp"></i>
                    </div>
                    <h4 class="text-2xl font-black text-white mb-4">Order on WhatsApp</h4>
                    <p class="text-slate-400 font-medium leading-relaxed">The menu opens directly in WhatsApp. They browse, customize, and order in seconds.</p>
                </div>

                <div class="relative p-12 bento-card rounded-[3rem] group" data-aos="fade-up" data-aos-delay="300">
                    <div class="absolute -top-6 left-12 w-12 h-12 bg-accent text-white rounded-2xl flex items-center justify-center font-black text-xl shadow-lg shadow-accent/30">3</div>
                    <div class="w-16 h-16 bg-white/5 rounded-2xl flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
                        <i data-lucide="credit-card" class="w-8 h-8 text-accent"></i>
                    </div>
                    <h4 class="text-2xl font-black text-white mb-4">Instant USSD Pay</h4>
                    <p class="text-slate-400 font-medium leading-relaxed">A payment prompt appears on their phone. They enter their PIN and the bill is settled.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Bento Grid -->
    <section id="features" class="py-32 bg-[#0B0F1A]">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-20 gap-8">
                <div class="max-w-2xl">
                    <h2 class="text-primary font-black tracking-[0.3em] uppercase text-xs mb-4">Features</h2>
                    <h3 class="text-4xl md:text-6xl font-black text-white tracking-tighter leading-none">Everything you need to <br/>run a modern restaurant.</h3>
                </div>
                <a href="{{ route('restaurant.register') }}" class="text-primary font-bold flex items-center gap-2 hover:gap-4 transition-all">
                    Explore all features <i data-lucide="arrow-right" class="w-5 h-5"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <!-- Large Card -->
                <div class="md:col-span-8 bento-card rounded-[3rem] p-12 overflow-hidden relative group" data-aos="fade-right">
                    <div class="relative z-10">
                        <div class="w-14 h-14 bg-primary/20 rounded-2xl flex items-center justify-center mb-8">
                            <i data-lucide="layout-dashboard" class="w-7 h-7 text-primary"></i>
                        </div>
                        <h4 class="text-3xl font-black text-white mb-4 tracking-tighter">Premium Admin Dashboard</h4>
                        <p class="text-slate-400 font-medium max-w-md mb-8">Manage menus, track real-time orders, and analyze sales data with our sleek, intuitive dashboard built for owners.</p>
                        <div class="flex flex-wrap gap-4">
                            <span class="px-4 py-2 bg-white/5 rounded-full text-xs font-bold text-slate-400">Real-time Analytics</span>
                            <span class="px-4 py-2 bg-white/5 rounded-full text-xs font-bold text-slate-400">Inventory Management</span>
                            <span class="px-4 py-2 bg-white/5 rounded-full text-xs font-bold text-slate-400">Staff Performance</span>
                        </div>
                    </div>
                    <div class="absolute bottom-0 right-0 w-1/2 translate-y-1/4 translate-x-1/4 group-hover:translate-x-0 group-hover:translate-y-0 transition-transform duration-700">
                        <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&q=80&w=800" alt="Dashboard" class="rounded-tl-3xl shadow-2xl opacity-50 group-hover:opacity-100 transition-opacity">
                    </div>
                </div>

                <!-- Small Card -->
                <div class="md:col-span-4 bento-card rounded-[3rem] p-12 bg-primary text-white" data-aos="fade-left">
                    <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center mb-8">
                        <i data-lucide="smartphone" class="w-7 h-7 text-white"></i>
                    </div>
                    <h4 class="text-3xl font-black mb-4 tracking-tighter">WhatsApp Native</h4>
                    <p class="text-indigo-100 font-medium mb-8">No friction. No app fatigue. Just the world's most popular messaging app working for you.</p>
                    <div class="pt-8 border-t border-white/10">
                        <div class="flex items-center gap-4 mb-4">
                            <i data-lucide="check-circle-2" class="w-5 h-5 text-white"></i>
                            <span class="text-sm font-bold">Buttons & Lists UI</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <i data-lucide="check-circle-2" class="w-5 h-5 text-white"></i>
                            <span class="text-sm font-bold">Instant Notifications</span>
                        </div>
                    </div>
                </div>

                <!-- Small Card -->
                <div class="md:col-span-4 bento-card rounded-[3rem] p-12" data-aos="fade-up">
                    <div class="w-14 h-14 bg-accent/20 rounded-2xl flex items-center justify-center mb-8">
                        <i data-lucide="zap" class="w-7 h-7 text-accent"></i>
                    </div>
                    <h4 class="text-2xl font-black text-white mb-4 tracking-tighter">USSD Payments</h4>
                    <p class="text-slate-400 font-medium">Integrated with TigoPesa, M-Pesa, and Airtel Money. Instant push-to-pay prompts for customers.</p>
                </div>

                <!-- Medium Card -->
                <div class="md:col-span-8 bento-card rounded-[3rem] p-12 flex flex-col md:flex-row items-center gap-12" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex-1">
                        <div class="w-14 h-14 bg-whatsapp/20 rounded-2xl flex items-center justify-center mb-8">
                            <i data-lucide="printer" class="w-7 h-7 text-whatsapp"></i>
                        </div>
                        <h4 class="text-3xl font-black text-white mb-4 tracking-tighter">POS & Printer Ready</h4>
                        <p class="text-slate-400 font-medium">Connect your thermal printers and POS systems. Orders print automatically in the kitchen as they arrive.</p>
                    </div>
                    <div class="flex-1 bg-white/5 p-8 rounded-3xl border border-white/10">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-white/5 rounded-xl shadow-sm border border-white/5">
                                <span class="text-xs font-bold text-slate-400">Kitchen Printer</span>
                                <span class="px-2 py-1 bg-accent/20 text-accent text-[10px] font-black rounded-lg">CONNECTED</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white/5 rounded-xl shadow-sm opacity-50 border border-white/5">
                                <span class="text-xs font-bold text-slate-400">Bar Printer</span>
                                <span class="px-2 py-1 bg-white/10 text-slate-500 text-[10px] font-black rounded-lg">OFFLINE</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why TAPTAP Comparison -->
    <section class="py-32 bg-[#0B0F1A]">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <h2 class="text-primary font-black tracking-[0.3em] uppercase text-xs mb-4">The Difference</h2>
                <h3 class="text-4xl md:text-5xl font-black text-white tracking-tighter mb-6">Traditional vs. TAPTAP</h3>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <div class="bento-card p-12 rounded-[3rem]" data-aos="fade-right">
                    <h4 class="text-2xl font-black text-slate-500 mb-10 uppercase tracking-widest">Traditional Dining</h4>
                    <ul class="space-y-6">
                        <li class="flex items-start gap-4">
                            <div class="w-6 h-6 bg-red-500/20 rounded-full flex items-center justify-center shrink-0 mt-1">
                                <i data-lucide="x" class="w-4 h-4 text-red-500"></i>
                            </div>
                            <p class="text-slate-400 font-medium">Wait for waiter to bring the menu (5-10 mins)</p>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-6 h-6 bg-red-500/20 rounded-full flex items-center justify-center shrink-0 mt-1">
                                <i data-lucide="x" class="w-4 h-4 text-red-500"></i>
                            </div>
                            <p class="text-slate-400 font-medium">Manual order taking (prone to errors)</p>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-6 h-6 bg-red-500/20 rounded-full flex items-center justify-center shrink-0 mt-1">
                                <i data-lucide="x" class="w-4 h-4 text-red-500"></i>
                            </div>
                            <p class="text-slate-400 font-medium">Wait for the bill & payment machine (10-15 mins)</p>
                        </li>
                    </ul>
                </div>

                <div class="bg-primary p-12 rounded-[3rem] shadow-2xl relative overflow-hidden" data-aos="fade-left">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 blur-2xl"></div>
                    <h4 class="text-2xl font-black text-white mb-10 uppercase tracking-widest">TAPTAP Experience</h4>
                    <ul class="space-y-6">
                        <li class="flex items-start gap-4">
                            <div class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center shrink-0 mt-1">
                                <i data-lucide="check" class="w-4 h-4 text-white"></i>
                            </div>
                            <p class="text-indigo-50 font-medium">Instant menu access via QR Scan (3 seconds)</p>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center shrink-0 mt-1">
                                <i data-lucide="check" class="w-4 h-4 text-white"></i>
                            </div>
                            <p class="text-indigo-50 font-medium">Direct digital ordering to the kitchen</p>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center shrink-0 mt-1">
                                <i data-lucide="check" class="w-4 h-4 text-white"></i>
                            </div>
                            <p class="text-indigo-50 font-medium">Instant USSD payment at the table</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-32 bg-[#0B0F1A] overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <h2 class="text-primary font-black tracking-[0.3em] uppercase text-xs mb-4">Testimonials</h2>
                <h3 class="text-4xl md:text-5xl font-black text-white tracking-tighter mb-6">Loved by Restaurant Owners.</h3>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="p-10 bento-card rounded-[3rem]" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex gap-1 mb-6">
                        <i data-lucide="star" class="w-5 h-5 text-yellow-400 fill-yellow-400"></i>
                        <i data-lucide="star" class="w-5 h-5 text-yellow-400 fill-yellow-400"></i>
                        <i data-lucide="star" class="w-5 h-5 text-yellow-400 fill-yellow-400"></i>
                        <i data-lucide="star" class="w-5 h-5 text-yellow-400 fill-yellow-400"></i>
                        <i data-lucide="star" class="w-5 h-5 text-yellow-400 fill-yellow-400"></i>
                    </div>
                    <p class="text-slate-400 font-medium italic mb-8">"TAPTAP has completely changed how we handle peak hours. Our waiters are less stressed and customers love the speed of WhatsApp ordering."</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white/10 rounded-full"></div>
                        <div>
                            <p class="font-black text-white">John M.</p>
                            <p class="text-xs font-bold text-slate-500 uppercase">Owner, Samaki Samaki</p>
                        </div>
                    </div>
                </div>

                <div class="p-10 bento-card rounded-[3rem]" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex gap-1 mb-6">
                        <i data-lucide="star" class="w-5 h-5 text-yellow-400 fill-yellow-400"></i>
                        <i data-lucide="star" class="w-5 h-5 text-yellow-400 fill-yellow-400"></i>
                        <i data-lucide="star" class="w-5 h-5 text-yellow-400 fill-yellow-400"></i>
                        <i data-lucide="star" class="w-5 h-5 text-yellow-400 fill-yellow-400"></i>
                        <i data-lucide="star" class="w-5 h-5 text-yellow-400 fill-yellow-400"></i>
                    </div>
                    <p class="text-slate-400 font-medium italic mb-8">"The USSD payment integration is a game changer. No more carrying around POS machines or waiting for customers to find cash."</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white/10 rounded-full"></div>
                        <div>
                            <p class="font-black text-white">Sarah K.</p>
                            <p class="text-xs font-bold text-slate-500 uppercase">Manager, Akemi</p>
                        </div>
                    </div>
                </div>

                <div class="p-10 bento-card rounded-[3rem]" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex gap-1 mb-6">
                        <i data-lucide="star" class="w-5 h-5 text-yellow-400 fill-yellow-400"></i>
                        <i data-lucide="star" class="w-5 h-5 text-yellow-400 fill-yellow-400"></i>
                        <i data-lucide="star" class="w-5 h-5 text-yellow-400 fill-yellow-400"></i>
                        <i data-lucide="star" class="w-5 h-5 text-yellow-400 fill-yellow-400"></i>
                        <i data-lucide="star" class="w-5 h-5 text-yellow-400 fill-yellow-400"></i>
                    </div>
                    <p class="text-slate-400 font-medium italic mb-8">"Setup was incredibly fast. We were up and running in less than 24 hours. The dashboard gives me insights I never had before."</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white/10 rounded-full"></div>
                        <div>
                            <p class="font-black text-white">David L.</p>
                            <p class="text-xs font-bold text-slate-500 uppercase">Founder, Cape Town Fish Market</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-32 bg-[#0B0F1A]">
        <div class="max-w-4xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="text-primary font-black tracking-[0.3em] uppercase text-xs mb-4">FAQ</h2>
                <h3 class="text-4xl md:text-5xl font-black text-white tracking-tighter mb-6">Got Questions?</h3>
            </div>

            <div class="space-y-4">
                <div class="bento-card rounded-3xl overflow-hidden">
                    <button class="w-full p-8 text-left flex justify-between items-center group" onclick="toggleFaq(this)">
                        <span class="text-lg font-black text-white tracking-tight">Do customers need to download an app?</span>
                        <i data-lucide="chevron-down" class="w-6 h-6 text-slate-500 group-hover:text-primary transition-all"></i>
                    </button>
                    <div class="hidden px-8 pb-8 text-slate-400 font-medium leading-relaxed">
                        No! That's the magic of TAPTAP. Customers simply scan a QR code and it opens directly in WhatsApp, which they already have on their phone.
                    </div>
                </div>

                <div class="bento-card rounded-3xl overflow-hidden">
                    <button class="w-full p-8 text-left flex justify-between items-center group" onclick="toggleFaq(this)">
                        <span class="text-lg font-black text-white tracking-tight">How do payments work?</span>
                        <i data-lucide="chevron-down" class="w-6 h-6 text-slate-500 group-hover:text-primary transition-all"></i>
                    </button>
                    <div class="hidden px-8 pb-8 text-slate-400 font-medium leading-relaxed">
                        We integrate with major mobile money providers (TigoPesa, M-Pesa, Airtel Money). When a customer clicks "Pay", they receive a USSD push prompt on their phone to enter their PIN.
                    </div>
                </div>

                <div class="bento-card rounded-3xl overflow-hidden">
                    <button class="w-full p-8 text-left flex justify-between items-center group" onclick="toggleFaq(this)">
                        <span class="text-lg font-black text-white tracking-tight">Can I use my existing printers?</span>
                        <i data-lucide="chevron-down" class="w-6 h-6 text-slate-500 group-hover:text-primary transition-all"></i>
                    </button>
                    <div class="hidden px-8 pb-8 text-slate-400 font-medium leading-relaxed">
                        Yes! TAPTAP supports most standard thermal printers. Our system can automatically send orders to the kitchen and bar printers as soon as they are confirmed.
                    </div>
                </div>

                <div class="bento-card rounded-3xl overflow-hidden">
                    <button class="w-full p-8 text-left flex justify-between items-center group" onclick="toggleFaq(this)">
                        <span class="text-lg font-black text-white tracking-tight">What if I have multiple branches?</span>
                        <i data-lucide="chevron-down" class="w-6 h-6 text-slate-500 group-hover:text-primary transition-all"></i>
                    </button>
                    <div class="hidden px-8 pb-8 text-slate-500 font-medium leading-relaxed">
                        Our Enterprise plan is built for multi-branch management. You can see consolidated reports or drill down into specific locations from a single master dashboard.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- WhatsApp Bot Showcase -->
    <section id="whatsapp-bot" class="py-32 bg-[#0B0F1A] relative overflow-hidden">
        <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-primary/10 rounded-full blur-[150px] -mr-96 -mt-96"></div>
        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-24 items-center">
                <div data-aos="fade-right">
                    <h2 class="text-whatsapp font-black tracking-[0.3em] uppercase text-xs mb-4">The Bot Experience</h2>
                    <h3 class="text-5xl md:text-7xl font-black text-white tracking-tighter mb-8 leading-[0.9]">A Restaurant in <br/>their Pocket.</h3>
                    <p class="text-slate-400 text-xl mb-12 leading-relaxed font-medium">Our WhatsApp bot isn't just a chat—it's a high-performance application. We use the latest WhatsApp Business API features to provide a visual, button-driven experience.</p>
                    
                    <div class="space-y-6">
                        <div class="flex items-start gap-6">
                            <div class="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center shrink-0">
                                <i data-lucide="mouse-pointer-2" class="w-6 h-6 text-whatsapp"></i>
                            </div>
                            <div>
                                <h5 class="text-xl font-bold text-white mb-1">Interactive Buttons</h5>
                                <p class="text-slate-500">No typing required. Customers just tap buttons to choose categories and items.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-6">
                            <div class="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center shrink-0">
                                <i data-lucide="shopping-cart" class="w-6 h-6 text-whatsapp"></i>
                            </div>
                            <div>
                                <h5 class="text-xl font-bold text-white mb-1">Smart Cart</h5>
                                <p class="text-slate-500">Persistent cart management within the chat. Add, remove, or modify items easily.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-6">
                            <div class="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center shrink-0">
                                <i data-lucide="bell" class="w-6 h-6 text-whatsapp"></i>
                            </div>
                            <div>
                                <h5 class="text-xl font-bold text-white mb-1">Live Status Updates</h5>
                                <p class="text-slate-500">Customers get notified when their order is accepted, being prepared, and served.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative" data-aos="fade-left">
                    <div class="max-w-[350px] mx-auto bg-[#0F172A] rounded-[3.5rem] p-4 border-[12px] border-slate-800 shadow-2xl">
                        <div class="bg-[#E5DDD5] rounded-[2.5rem] overflow-hidden aspect-[9/19] relative flex flex-col">
                            <!-- WhatsApp Header -->
                            <div class="bg-[#075E54] p-4 pt-6 flex items-center gap-3">
                                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                                    <i data-lucide="zap" class="w-6 h-6 text-[#075E54]"></i>
                                </div>
                                <div>
                                    <p class="text-white font-bold text-sm">TAPTAP Smart Bot</p>
                                    <p class="text-[10px] text-white/70">Online</p>
                                </div>
                            </div>
                            <!-- Chat Area -->
                            <div class="flex-1 p-4 space-y-4 overflow-y-auto custom-scrollbar">
                                <div class="whatsapp-bubble max-w-[85%] text-xs text-white font-medium">
                                    Karibu Samaki Samaki! 🍽️<br>Tafadhali chagua unachotaka kufanya:
                                </div>
                                <div class="space-y-2">
                                    <button class="w-full py-3 bg-white rounded-xl text-primary font-bold text-xs shadow-sm border border-primary/10">📖 Fungua Menu</button>
                                    <button class="w-full py-3 bg-white rounded-xl text-slate-600 font-bold text-xs shadow-sm">🛒 Angalia Oda Yangu</button>
                                    <button class="w-full py-3 bg-white rounded-xl text-slate-600 font-bold text-xs shadow-sm">💳 Lipia Bill</button>
                                </div>
                                <div class="whatsapp-bubble max-w-[85%] text-xs text-white font-medium">
                                    Chagua Category:
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <button class="py-3 bg-whatsapp text-white rounded-xl font-bold text-[10px]">🐟 Samaki</button>
                                    <button class="py-3 bg-white text-slate-600 rounded-xl font-bold text-[10px]">🍗 Kuku</button>
                                </div>
                            </div>
                            <!-- Input Area -->
                            <div class="p-3 bg-white/80 backdrop-blur-md flex items-center gap-2">
                                <div class="flex-1 h-10 bg-white rounded-full border border-slate-200"></div>
                                <div class="w-10 h-10 bg-whatsapp rounded-full flex items-center justify-center">
                                    <i data-lucide="send" class="w-5 h-5 text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Smart Dining Intelligence -->
    <section class="py-32 bg-[#0B0F1A] relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-24 items-center">
                <div class="order-2 lg:order-1 relative" data-aos="fade-right">
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-6">
                            <div class="bento-card p-8 rounded-[2.5rem] mt-12">
                                <div class="w-12 h-12 bg-primary/20 rounded-2xl flex items-center justify-center mb-6">
                                    <i data-lucide="trending-up" class="w-6 h-6 text-primary"></i>
                                </div>
                                <h5 class="text-lg font-black text-white mb-2">AI Upselling</h5>
                                <p class="text-xs text-slate-500 font-medium">Smart recommendations that increase average order value by 15%.</p>
                            </div>
                            <div class="bento-card p-8 rounded-[2.5rem]">
                                <div class="w-12 h-12 bg-accent/20 rounded-2xl flex items-center justify-center mb-6">
                                    <i data-lucide="refresh-cw" class="w-6 h-6 text-accent"></i>
                                </div>
                                <h5 class="text-lg font-black text-white mb-2">Real-time Sync</h5>
                                <p class="text-xs text-slate-500 font-medium">Menu updates and stock levels sync instantly across all devices.</p>
                            </div>
                        </div>
                        <div class="space-y-6">
                            <div class="bento-card p-8 rounded-[2.5rem]">
                                <div class="w-12 h-12 bg-whatsapp/20 rounded-2xl flex items-center justify-center mb-6">
                                    <i data-lucide="megaphone" class="w-6 h-6 text-whatsapp"></i>
                                </div>
                                <h5 class="text-lg font-black text-white mb-2">Push Marketing</h5>
                                <p class="text-xs text-slate-500 font-medium">Send personalized offers directly to your customers' WhatsApp.</p>
                            </div>
                            <div class="bento-card p-8 rounded-[2.5rem]">
                                <div class="w-12 h-12 bg-blue-500/20 rounded-2xl flex items-center justify-center mb-6">
                                    <i data-lucide="bar-chart-3" class="w-6 h-6 text-blue-500"></i>
                                </div>
                                <h5 class="text-lg font-black text-white mb-2">Data-Rich Insights</h5>
                                <p class="text-xs text-slate-500 font-medium">Understand peak hours, top dishes, and customer behavior.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="order-1 lg:order-2" data-aos="fade-left">
                    <h2 class="text-primary font-black tracking-[0.3em] uppercase text-xs mb-4">Intelligence</h2>
                    <h3 class="text-5xl md:text-7xl font-black text-white tracking-tighter mb-8 leading-[0.9]">Smart Dining <br/>Intelligence.</h3>
                    <p class="text-slate-400 text-xl mb-12 leading-relaxed font-medium">TAPTAP isn't just a tool—it's your restaurant's brain. We use data to help you sell more, waste less, and keep customers coming back.</p>
                    
                    <div class="flex flex-wrap gap-4">
                        <div class="flex items-center gap-2 px-4 py-2 bg-white/5 rounded-full border border-white/10">
                            <div class="w-2 h-2 bg-primary rounded-full"></div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Automated</span>
                        </div>
                        <div class="flex items-center gap-2 px-4 py-2 bg-white/5 rounded-full border border-white/10">
                            <div class="w-2 h-2 bg-accent rounded-full"></div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Predictive</span>
                        </div>
                        <div class="flex items-center gap-2 px-4 py-2 bg-white/5 rounded-full border border-white/10">
                            <div class="w-2 h-2 bg-whatsapp rounded-full"></div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Personalized</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-32 bg-[#0B0F1A]">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <h2 class="text-primary font-black tracking-[0.3em] uppercase text-xs mb-4">Pricing</h2>
                <h3 class="text-4xl md:text-6xl font-black text-white tracking-tighter mb-6 leading-none">Simple, transparent <br/>pricing for everyone.</h3>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Starter -->
                <div class="bento-card p-12 rounded-[3rem] flex flex-col" data-aos="fade-up" data-aos-delay="100">
                    <h4 class="text-xl font-black text-white mb-2">Starter</h4>
                    <p class="text-slate-500 text-sm mb-8 font-medium">Perfect for small cafes</p>
                    <div class="mb-8">
                        <span class="text-5xl font-black text-white tracking-tighter">Free</span>
                        <span class="text-slate-500 font-bold ml-2">/ 14 days</span>
                    </div>
                    <ul class="space-y-4 mb-12 flex-1">
                        <li class="flex items-center gap-3 text-slate-400 font-medium">
                            <i data-lucide="check" class="w-5 h-5 text-primary"></i>
                            Up to 20 tables
                        </li>
                        <li class="flex items-center gap-3 text-slate-400 font-medium">
                            <i data-lucide="check" class="w-5 h-5 text-primary"></i>
                            WhatsApp Bot
                        </li>
                        <li class="flex items-center gap-3 text-slate-400 font-medium">
                            <i data-lucide="check" class="w-5 h-5 text-primary"></i>
                            Basic Analytics
                        </li>
                    </ul>
                    <a href="{{ route('restaurant.register') }}" class="w-full py-4 bg-white/5 text-white border border-white/10 rounded-2xl font-black text-center hover:bg-white/10 transition-all">Get Started</a>
                </div>

                <!-- Business -->
                <div class="bg-primary p-12 rounded-[3rem] flex flex-col relative shadow-2xl scale-105 z-10" data-aos="fade-up">
                    <div class="absolute -top-5 left-1/2 -translate-x-1/2 bg-white text-primary px-6 py-2 rounded-full text-xs font-black uppercase tracking-widest shadow-xl">Most Popular</div>
                    <h4 class="text-xl font-black text-white mb-2">Business</h4>
                    <p class="text-indigo-100 text-sm mb-8 font-medium">For growing restaurants</p>
                    <div class="mb-8">
                        <span class="text-5xl font-black text-white tracking-tighter">TZS 50k</span>
                        <span class="text-indigo-100 font-bold ml-2">/ month</span>
                    </div>
                    <ul class="space-y-4 mb-12 flex-1">
                        <li class="flex items-center gap-3 text-white font-medium">
                            <i data-lucide="check" class="w-5 h-5 text-white"></i>
                            Unlimited tables
                        </li>
                        <li class="flex items-center gap-3 text-white font-medium">
                            <i data-lucide="check" class="w-5 h-5 text-white"></i>
                            USSD Payments
                        </li>
                        <li class="flex items-center gap-3 text-white font-medium">
                            <i data-lucide="check" class="w-5 h-5 text-white"></i>
                            Printer Integration
                        </li>
                        <li class="flex items-center gap-3 text-white font-medium">
                            <i data-lucide="check" class="w-5 h-5 text-white"></i>
                            Advanced Analytics
                        </li>
                    </ul>
                    <a href="{{ route('restaurant.register') }}" class="w-full py-4 bg-white text-primary rounded-2xl font-black text-center hover:shadow-2xl hover:-translate-y-1 transition-all">Start 14-Day Free Trial</a>
                </div>

                <!-- Enterprise -->
                <div class="bento-card p-12 rounded-[3rem] flex flex-col" data-aos="fade-up" data-aos-delay="200">
                    <h4 class="text-xl font-black text-white mb-2">Enterprise</h4>
                    <p class="text-slate-500 text-sm mb-8 font-medium">For large franchises</p>
                    <div class="mb-8">
                        <span class="text-5xl font-black text-white tracking-tighter">Custom</span>
                    </div>
                    <ul class="space-y-4 mb-12 flex-1">
                        <li class="flex items-center gap-3 text-slate-400 font-medium">
                            <i data-lucide="check" class="w-5 h-5 text-primary"></i>
                            Multi-branch Dashboard
                        </li>
                        <li class="flex items-center gap-3 text-slate-400 font-medium">
                            <i data-lucide="check" class="w-5 h-5 text-primary"></i>
                            Priority Support
                        </li>
                        <li class="flex items-center gap-3 text-slate-400 font-medium">
                            <i data-lucide="check" class="w-5 h-5 text-primary"></i>
                            Custom Integrations
                        </li>
                    </ul>
                    <a href="https://wa.me/255620366103" class="w-full py-4 bg-white/5 text-white border border-white/10 rounded-2xl font-black text-center hover:bg-white/10 transition-all">Contact Sales</a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-32 bg-[#0B0F1A]">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="bg-primary rounded-[4rem] p-16 md:p-24 relative overflow-hidden shadow-2xl shadow-primary/30 text-center">
                <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full -mr-48 -mt-48 blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-96 h-96 bg-black/10 rounded-full -ml-48 -mb-48 blur-3xl"></div>
                
                <div class="relative z-10 max-w-3xl mx-auto">
                    <h2 class="text-4xl md:text-7xl font-black text-white mb-10 tracking-tighter leading-none">Ready to transform your restaurant?</h2>
                    <p class="text-xl mb-12 font-medium text-white/80">Join the hundreds of restaurants already using TAPTAP to increase their revenue and delight their customers.</p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                        <a href="{{ route('restaurant.register') }}" class="w-full sm:w-auto px-12 py-6 bg-white text-primary rounded-3xl font-black text-2xl shadow-2xl hover:scale-105 transition-all">
                            Get Started for Free
                        </a>
                        <a href="https://wa.me/255620366103" class="w-full sm:w-auto px-12 py-6 bg-black/20 text-white border border-white/20 rounded-3xl font-black text-xl hover:bg-white/10 transition-all">
                            Talk to an Expert
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#0B0F1A] pt-32 pb-12 border-t border-white/5">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-12 mb-20">
                <div class="col-span-2">
                    <a href="/" class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center">
                            <i data-lucide="zap" class="w-6 h-6 text-white"></i>
                        </div>
                        <span class="text-xl font-black text-white">TAPTAP</span>
                    </a>
                    <p class="text-slate-500 font-medium max-w-xs mb-8">The smartest way to run your restaurant in the digital age. Built for Tanzania, loved by everyone.</p>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 bg-white/5 rounded-full flex items-center justify-center text-slate-400 hover:bg-primary hover:text-white transition-all">
                            <i data-lucide="instagram" class="w-5 h-5"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white/5 rounded-full flex items-center justify-center text-slate-400 hover:bg-primary hover:text-white transition-all">
                            <i data-lucide="twitter" class="w-5 h-5"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white/5 rounded-full flex items-center justify-center text-slate-400 hover:bg-primary hover:text-white transition-all">
                            <i data-lucide="facebook" class="w-5 h-5"></i>
                        </a>
                    </div>
                </div>
                <div>
                    <h6 class="text-white font-black uppercase tracking-widest text-xs mb-8">Product</h6>
                    <ul class="space-y-4">
                        <li><a href="#features" class="text-slate-500 hover:text-primary font-bold text-sm transition-colors">Features</a></li>
                        <li><a href="#pricing" class="text-slate-500 hover:text-primary font-bold text-sm transition-colors">Pricing</a></li>
                        <li><a href="#whatsapp-bot" class="text-slate-500 hover:text-primary font-bold text-sm transition-colors">WhatsApp Bot</a></li>
                    </ul>
                </div>
                <div>
                    <h6 class="text-white font-black uppercase tracking-widest text-xs mb-8">Company</h6>
                    <ul class="space-y-4">
                        <li><a href="#" class="text-slate-500 hover:text-primary font-bold text-sm transition-colors">About Us</a></li>
                        <li><a href="#" class="text-slate-500 hover:text-primary font-bold text-sm transition-colors">Contact</a></li>
                        <li><a href="#" class="text-slate-500 hover:text-primary font-bold text-sm transition-colors">Privacy Policy</a></li>
                    </ul>
                </div>
                <div>
                    <h6 class="text-white font-black uppercase tracking-widest text-xs mb-8">Support</h6>
                    <ul class="space-y-4">
                        <li><a href="#" class="text-slate-500 hover:text-primary font-bold text-sm transition-colors">Help Center</a></li>
                        <li><a href="#" class="text-slate-500 hover:text-primary font-bold text-sm transition-colors">Status</a></li>
                        <li><a href="https://wa.me/255620366103" class="text-slate-500 hover:text-primary font-bold text-sm transition-colors">WhatsApp Support</a></li>
                    </ul>
                </div>
            </div>
            <div class="pt-12 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-slate-600 text-sm font-bold">© {{ date('Y') }} TAPTAP Smart Dining. All rights reserved.</p>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-accent rounded-full animate-pulse"></span>
                    <span class="text-slate-600 text-xs font-black uppercase tracking-widest">System Operational</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Floating WhatsApp Support -->
    <a href="https://wa.me/255620366103" class="fixed bottom-8 right-8 z-[90] group">
        <div class="absolute -inset-4 bg-whatsapp/20 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition-opacity animate-pulse"></div>
        <div class="relative bg-whatsapp text-white p-4 rounded-2xl shadow-2xl flex items-center gap-3 hover:scale-110 transition-all duration-500">
            <i data-lucide="message-circle" class="w-7 h-7"></i>
            <span class="max-w-0 overflow-hidden group-hover:max-w-xs transition-all duration-500 font-bold whitespace-nowrap">Chat with Support</span>
        </div>
    </a>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100,
            easing: 'ease-out-cubic'
        });

        // Lucide Icons
        lucide.createIcons();

        // Navbar Scroll Effect
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('navbar');
            if (window.scrollY > 50) {
                nav.classList.add('py-2', 'shadow-2xl');
                nav.classList.remove('py-4');
            } else {
                nav.classList.remove('py-2', 'shadow-2xl');
                nav.classList.add('py-4');
            }
        });

        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const closeMenuBtn = document.getElementById('close-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.remove('hidden');
            mobileMenu.classList.add('flex');
        });

        closeMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
            mobileMenu.classList.remove('flex');
        });

        // FAQ Toggle
        function toggleFaq(button) {
            const content = button.nextElementSibling;
            const icon = button.querySelector('i');
            
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
                icon.style.transition = 'all 0.3s ease';
            } else {
                content.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            }
        }
    </script>
</body>
</html>
