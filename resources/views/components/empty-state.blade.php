@props([
    'title' => 'No items yet',
    'description' => 'Nothing to show here at the moment.',
    'icon' => null,
    'actionUrl' => null,
    'actionLabel' => null,
])

<div class="glass-card rounded-2xl p-8 md:p-12 text-center">
    @if($icon ?? null)
        <div class="mx-auto w-16 h-16 rounded-2xl bg-white/5 flex items-center justify-center text-white/40 mb-6">
            {{ $icon }}
        </div>
    @else
        <div class="mx-auto w-16 h-16 rounded-2xl bg-violet-500/10 flex items-center justify-center text-violet-400/80 mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
            </svg>
        </div>
    @endif
    <h3 class="text-lg font-semibold text-white mb-2">{{ $title }}</h3>
    <p class="text-sm text-white/50 max-w-sm mx-auto mb-6">{{ $description }}</p>
    @if($actionUrl && $actionLabel)
        <a href="{{ $actionUrl }}" class="inline-flex items-center justify-center min-h-[44px] px-6 py-3 bg-gradient-to-r from-violet-600 to-cyan-600 text-white font-semibold rounded-xl shadow-lg shadow-violet-500/25 hover:shadow-violet-500/40 transition-all focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 focus:ring-offset-[#0f0a1e]">
            {{ $actionLabel }}
        </a>
    @endif
</div>
