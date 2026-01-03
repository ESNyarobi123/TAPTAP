<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TAPTAP - Smart Dining & WhatsApp Ordering System</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        'primary': '#3B82F6',
                        'secondary': '#0F172A',
                        'accent': '#10B981',
                        'whatsapp': '#25D366',
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }
        .text-gradient {
            background: linear-gradient(135deg, #3B82F6 0%, #10B981 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .bg-gradient-premium {
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        .card-shadow {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
        }
        .whatsapp-glow {
            box-shadow: 0 0 20px rgba(37, 211, 102, 0.2);
        }
    </style>
</head>
<body class="font-sans antialiased bg-[#F8FAFC] text-slate-900 overflow-x-hidden">
    
    <!-- Navigation -->
    <nav class="fixed w-full z-50 glass transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between items-center h-24">
                <!-- Logo -->
                <div class="flex items-center gap-3 group cursor-pointer">
                    <div class="w-12 h-12 bg-primary rounded-2xl flex items-center justify-center shadow-lg shadow-primary/20 transform group-hover:rotate-6 transition-all">
                        <i data-lucide="shield-check" class="w-7 h-7 text-white"></i>
                    </div>
                    <div>
                        <span class="text-2xl font-black text-secondary tracking-tighter block leading-none">TAP<span class="text-primary">TAP</span></span>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em]">Smart Dining</span>
                    </div>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-10">
                    <a href="#how-it-helps" class="text-slate-500 hover:text-primary font-bold transition-colors text-sm uppercase tracking-widest">How it Helps</a>
                    <a href="#whatsapp-bot" class="text-slate-500 hover:text-primary font-bold transition-colors text-sm uppercase tracking-widest">WhatsApp Bot</a>
                    <a href="#features" class="text-slate-500 hover:text-primary font-bold transition-colors text-sm uppercase tracking-widest">Features</a>
                    
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="bg-secondary text-white px-8 py-3 rounded-2xl font-bold hover:shadow-xl hover:-translate-y-1 transition-all text-sm uppercase tracking-widest">
                                Dashboard
                            </a>
                        @else
                            <div class="flex items-center gap-6">
                                <a href="{{ route('login') }}" class="text-secondary font-black hover:text-primary transition-colors text-sm uppercase tracking-widest">Log in</a>
                                <a href="{{ route('restaurant.register') }}" class="bg-primary text-white px-8 py-4 rounded-2xl font-black shadow-lg shadow-primary/30 hover:bg-secondary hover:shadow-secondary/20 hover:-translate-y-1 transition-all text-sm uppercase tracking-widest">
                                    Register Restaurant
                                </a>
                            </div>
                        @endauth
                    @endif
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button class="p-2 text-slate-600">
                        <i data-lucide="menu" class="w-8 h-8"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-48 pb-32 lg:pt-64 lg:pb-48 overflow-hidden">
        <div class="absolute top-0 right-0 -mr-40 -mt-40 w-[600px] h-[600px] bg-primary/5 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-0 left-0 -ml-40 -mb-40 w-[500px] h-[500px] bg-accent/5 rounded-full blur-[100px]"></div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-20 items-center">
                <div>
                    <div class="inline-flex items-center px-4 py-2 rounded-full bg-whatsapp/10 text-whatsapp text-[10px] font-black uppercase tracking-widest mb-8 border border-whatsapp/10">
                        <span class="w-2 h-2 bg-whatsapp rounded-full mr-3 animate-pulse"></span>
                        New: Smart WhatsApp Ordering Bot
                    </div>
                    
                    <h1 class="text-6xl md:text-8xl font-black text-secondary tracking-tighter mb-8 leading-[0.85]">
                        Dining inside <br/>
                        <span class="text-whatsapp">WhatsApp.</span>
                    </h1>
                    
                    <p class="text-xl text-slate-500 mb-12 max-w-xl leading-relaxed font-medium">
                        TAPTAP brings your restaurant menu directly into your customers' favorite app. No downloads, no friction—just scan, chat, and eat.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row items-center gap-6">
                        <a href="{{ route('restaurant.register') }}" class="w-full sm:w-auto px-10 py-6 bg-secondary text-white rounded-3xl font-black text-xl shadow-2xl shadow-secondary/20 hover:bg-primary hover:scale-105 transition-all text-center">
                            Get Started Free
                        </a>
                        <a href="#whatsapp-bot" class="w-full sm:w-auto px-10 py-6 bg-white text-secondary border border-slate-200 rounded-3xl font-black text-lg hover:border-whatsapp hover:bg-slate-50 transition-all flex items-center justify-center gap-3">
                            <i data-lucide="message-circle" class="w-6 h-6 text-whatsapp"></i>
                            See the Bot
                        </a>
                    </div>

                    <div class="mt-16 flex items-center gap-12 border-t border-slate-100 pt-12">
                        <div>
                            <div class="text-3xl font-black text-secondary tracking-tighter">100%</div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">WhatsApp Native</div>
                        </div>
                        <div class="w-px h-10 bg-slate-200"></div>
                        <div>
                            <div class="text-3xl font-black text-secondary tracking-tighter">3s</div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Ordering Speed</div>
                        </div>
                        <div class="w-px h-10 bg-slate-200"></div>
                        <div>
                            <div class="text-3xl font-black text-secondary tracking-tighter">USSD</div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Instant Pay</div>
                        </div>
                    </div>
                </div>

                <div class="relative lg:block hidden">
                    <div class="relative z-20 animate-float">
                        <div class="bg-secondary rounded-[3rem] p-4 shadow-2xl border-8 border-slate-800">
                            <div class="bg-[#E5DDD5] rounded-[2.2rem] overflow-hidden aspect-[9/16] relative">
                                <!-- WhatsApp UI Mockup -->
                                <div class="bg-[#075E54] p-4 flex items-center gap-3">
                                    <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                                        <i data-lucide="shield-check" class="w-5 h-5 text-white"></i>
                                    </div>
                                    <div class="text-white">
                                        <p class="text-xs font-bold leading-none">TAPTAP Bot</p>
                                        <p class="text-[8px] opacity-70">Online</p>
                                    </div>
                                </div>
                                <div class="p-4 space-y-4">
                                    <div class="bg-white p-3 rounded-2xl rounded-tl-none shadow-sm max-w-[80%]">
                                        <p class="text-[10px] text-slate-800">Karibu Samaki Samaki 🍽️<br>Chagua huduma yako:</p>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="bg-white py-2 px-4 rounded-xl border border-primary/20 text-primary text-[10px] font-bold text-center whatsapp-glow">
                                            🍽️ Menu
                                        </div>
                                        <div class="bg-white py-2 px-4 rounded-xl border border-slate-100 text-slate-600 text-[10px] font-bold text-center">
                                            🛒 Cart / Oda
                                        </div>
                                        <div class="bg-white py-2 px-4 rounded-xl border border-slate-100 text-slate-600 text-[10px] font-bold text-center">
                                            💳 Malipo
                                        </div>
                                    </div>
                                    <div class="bg-white p-3 rounded-2xl rounded-tl-none shadow-sm max-w-[80%]">
                                        <p class="text-[10px] text-slate-800">Chagua Category:</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <div class="bg-whatsapp text-white py-2 px-4 rounded-xl text-[9px] font-bold">🐟 Samaki</div>
                                        <div class="bg-white py-2 px-4 rounded-xl border border-slate-100 text-slate-600 text-[9px] font-bold">🍗 Kuku</div>
                                    </div>
                                </div>
                                <div class="absolute bottom-0 w-full p-4 bg-white/80 backdrop-blur-sm flex items-center gap-2">
                                    <div class="flex-1 h-8 bg-white rounded-full border border-slate-200"></div>
                                    <div class="w-8 h-8 bg-whatsapp rounded-full flex items-center justify-center">
                                        <i data-lucide="send" class="w-4 h-4 text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Decorative Blobs -->
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-whatsapp rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>
                    <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-primary rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- WhatsApp Bot Section -->
    <section id="whatsapp-bot" class="py-32 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-20 items-center">
                <div class="order-2 lg:order-1">
                    <div class="grid grid-cols-2 gap-6">
                        <div class="bg-slate-50 p-8 rounded-[3rem] border border-slate-100 hover:border-whatsapp transition-all group">
                            <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center mb-6 shadow-sm group-hover:scale-110 transition-transform">
                                <i data-lucide="qr-code" class="w-6 h-6 text-whatsapp"></i>
                            </div>
                            <h5 class="text-secondary font-black text-xl mb-2 tracking-tighter">QR to Chat</h5>
                            <p class="text-slate-500 text-sm font-medium leading-relaxed">Scan a table QR and instantly start chatting with the bot. No apps needed.</p>
                        </div>
                        <div class="bg-slate-50 p-8 rounded-[3rem] border border-slate-100 hover:border-primary transition-all group">
                            <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center mb-6 shadow-sm group-hover:scale-110 transition-transform">
                                <i data-lucide="search" class="w-6 h-6 text-primary"></i>
                            </div>
                            <h5 class="text-secondary font-black text-xl mb-2 tracking-tighter">Smart Search</h5>
                            <p class="text-slate-500 text-sm font-medium leading-relaxed">Customers can search for your restaurant by name directly in WhatsApp.</p>
                        </div>
                        <div class="bg-slate-50 p-8 rounded-[3rem] border border-slate-100 hover:border-accent transition-all group">
                            <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center mb-6 shadow-sm group-hover:scale-110 transition-transform">
                                <i data-lucide="credit-card" class="w-6 h-6 text-accent"></i>
                            </div>
                            <h5 class="text-secondary font-black text-xl mb-2 tracking-tighter">USSD Pay</h5>
                            <p class="text-slate-500 text-sm font-medium leading-relaxed">Integrated mobile money payments. Pay without leaving the chat.</p>
                        </div>
                        <div class="bg-slate-50 p-8 rounded-[3rem] border border-slate-100 hover:border-secondary transition-all group">
                            <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center mb-6 shadow-sm group-hover:scale-110 transition-transform">
                                <i data-lucide="star" class="w-6 h-6 text-secondary"></i>
                            </div>
                            <h5 class="text-secondary font-black text-xl mb-2 tracking-tighter">Feedback & Tips</h5>
                            <p class="text-slate-500 text-sm font-medium leading-relaxed">Collect ratings and tips for your staff automatically after every meal.</p>
                        </div>
                    </div>
                </div>
                <div class="order-1 lg:order-2">
                    <h2 class="text-whatsapp font-black tracking-[0.3em] uppercase text-[10px] mb-4">The WhatsApp Experience</h2>
                    <h3 class="text-4xl md:text-6xl font-black text-secondary tracking-tighter mb-8 leading-none">A Mini-App Inside <br/>WhatsApp.</h3>
                    <p class="text-slate-500 text-lg mb-10 leading-relaxed font-medium">TAPTAP isn't just a bot; it's a full-featured dining application that lives where your customers already are. From browsing the menu to paying the bill, everything happens in one conversation.</p>
                    <div class="space-y-4">
                        <div class="flex items-center gap-4">
                            <div class="w-6 h-6 bg-whatsapp/10 rounded-full flex items-center justify-center">
                                <i data-lucide="check" class="w-4 h-4 text-whatsapp"></i>
                            </div>
                            <span class="text-secondary font-bold">Buttons-first interface for speed</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-6 h-6 bg-whatsapp/10 rounded-full flex items-center justify-center">
                                <i data-lucide="check" class="w-4 h-4 text-whatsapp"></i>
                            </div>
                            <span class="text-secondary font-bold">Real-time order tracking</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-6 h-6 bg-whatsapp/10 rounded-full flex items-center justify-center">
                                <i data-lucide="check" class="w-4 h-4 text-whatsapp"></i>
                            </div>
                            <span class="text-secondary font-bold">Automated USSD payment prompts</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How it Helps Section -->
    <section id="how-it-helps" class="py-32 bg-[#F8FAFC] relative">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-24">
                <h2 class="text-primary font-black tracking-[0.3em] uppercase text-[10px] mb-4">The TAPTAP Advantage</h2>
                <h3 class="text-4xl md:text-5xl font-black text-secondary tracking-tighter mb-6">Why Modern Restaurants Choose TAPTAP</h3>
                <p class="text-slate-500 font-medium">We don't just provide software; we provide a complete ecosystem to grow your business.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <!-- Advantage 1 -->
                <div class="group p-12 rounded-[3rem] bg-white hover:bg-secondary transition-all duration-500 border border-slate-100 card-shadow">
                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mb-8 shadow-sm group-hover:scale-110 transition-transform">
                        <i data-lucide="zap" class="w-8 h-8 text-primary"></i>
                    </div>
                    <h4 class="text-2xl font-black text-secondary mb-4 group-hover:text-white transition-colors">Instant Table Turnover</h4>
                    <p class="text-slate-500 group-hover:text-slate-400 transition-colors leading-relaxed font-medium">Customers scan, order, and pay without waiting for a waiter. Reduce wait times by up to 30%.</p>
                </div>

                <!-- Advantage 2 -->
                <div class="group p-12 rounded-[3rem] bg-white hover:bg-primary transition-all duration-500 border border-slate-100 card-shadow">
                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mb-8 shadow-sm group-hover:scale-110 transition-transform">
                        <i data-lucide="users" class="w-8 h-8 text-secondary"></i>
                    </div>
                    <h4 class="text-2xl font-black text-secondary mb-4 group-hover:text-white transition-colors">Staff Optimization</h4>
                    <p class="text-slate-500 group-hover:text-blue-50 transition-colors leading-relaxed font-medium">Your waiters focus on delivering food and hospitality, not writing down orders. Do more with fewer staff.</p>
                </div>

                <!-- Advantage 3 -->
                <div class="group p-12 rounded-[3rem] bg-white hover:bg-accent transition-all duration-500 border border-slate-100 card-shadow">
                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mb-8 shadow-sm group-hover:scale-110 transition-transform">
                        <i data-lucide="bar-chart-3" class="w-8 h-8 text-secondary"></i>
                    </div>
                    <h4 class="text-2xl font-black text-secondary mb-4 group-hover:text-white transition-colors">Real-time Insights</h4>
                    <p class="text-slate-500 group-hover:text-emerald-50 transition-colors leading-relaxed font-medium">Track sales, popular items, and staff performance in real-time from your premium admin dashboard.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-32 bg-white">
        <div class="max-w-5xl mx-auto px-6 text-center">
            <div class="bg-gradient-premium rounded-[4rem] p-16 md:p-24 relative overflow-hidden shadow-2xl shadow-secondary/30">
                <div class="absolute top-0 right-0 w-64 h-64 bg-primary/10 rounded-full -mr-32 -mt-32 blur-3xl"></div>
                <div class="relative z-10">
                    <h2 class="text-4xl md:text-7xl font-black text-white mb-10 tracking-tighter leading-none">Ready to scale your <br/><span class="text-primary">restaurant?</span></h2>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                        <a href="{{ route('restaurant.register') }}" class="w-full sm:w-auto px-12 py-6 bg-primary text-white rounded-3xl font-black text-2xl shadow-2xl shadow-primary/40 hover:bg-white hover:text-primary transition-all transform hover:-translate-y-2">
                            Start Free Trial
                        </a>
                        <a href="#" class="w-full sm:w-auto px-12 py-6 bg-white/5 text-white border border-white/10 rounded-3xl font-black text-xl hover:bg-white/10 transition-all">
                            Talk to an Expert
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white py-24 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-12">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-secondary rounded-xl flex items-center justify-center">
                        <i data-lucide="shield-check" class="w-6 h-6 text-white"></i>
                    </div>
                    <span class="font-black text-2xl text-secondary tracking-tighter">TAP<span class="text-primary">TAP</span></span>
                </div>
                <div class="flex flex-wrap justify-center gap-10 text-slate-400 font-black uppercase tracking-widest text-[10px]">
                    <a href="#" class="hover:text-primary transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-primary transition-colors">Terms of Service</a>
                    <a href="#" class="hover:text-primary transition-colors">Help Center</a>
                    <a href="#" class="hover:text-primary transition-colors">API Docs</a>
                </div>
                <div class="text-slate-400 font-bold text-xs">
                    &copy; {{ date('Y') }} TAPTAP. Built for the future of food.
                </div>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
