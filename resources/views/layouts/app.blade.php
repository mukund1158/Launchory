<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/png" href="{{ asset('images/logo/launchory-favicon.png') }}">

        {!! SEOMeta::generate() !!}
        {!! OpenGraph::generate() !!}

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="antialiased bg-white text-[#1a1a1a]" style="font-family: 'Inter', sans-serif;">
        {{-- Flash Toast --}}
        @if(session('success') || session('error'))
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 4000)"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-4"
            class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-5 py-3.5 rounded-2xl shadow-2xl text-sm font-semibold backdrop-blur-sm
                   {{ session('success') ? 'bg-emerald-500/95 text-white' : 'bg-red-500/95 text-white' }}"
        >
            @if(session('success'))
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            @else
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            @endif
            {{ session('success') ?? session('error') }}
        </div>
        @endif

        <div class="min-h-screen flex flex-col">
            {{-- Navbar --}}
            <nav class="sticky top-0 z-40 bg-white/80 backdrop-blur-xl border-b border-gray-100/80" x-data="{ mobileOpen: false }">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-16">
                        {{-- Left: Logo --}}
                        <a href="{{ route('home') }}" class="flex items-center group">
                            <img src="{{ asset('images/logo/launchory-logo.png') }}" alt="Launchory" class="h-48 w-auto group-hover:opacity-90 transition-opacity" />
                        </a>

                        {{-- Center: Nav links (desktop) --}}
                        <div class="hidden sm:flex items-center gap-1">
                            <a href="{{ route('launches.index') }}" class="relative px-4 py-2 text-sm font-medium rounded-xl transition-all duration-200
                                {{ request()->routeIs('launches.*') ? 'text-amber-600 bg-amber-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                                Launches
                                @if(request()->routeIs('launches.*'))
                                    <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-1 h-1 rounded-full bg-amber-500"></span>
                                @endif
                            </a>
                            <a href="{{ route('directory.index') }}" class="relative px-4 py-2 text-sm font-medium rounded-xl transition-all duration-200
                                {{ request()->routeIs('directory.*') ? 'text-amber-600 bg-amber-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                                Directory
                                @if(request()->routeIs('directory.*'))
                                    <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-1 h-1 rounded-full bg-amber-500"></span>
                                @endif
                            </a>
                            <a href="{{ route('pricing') }}" class="relative px-4 py-2 text-sm font-medium rounded-xl transition-all duration-200
                                {{ request()->routeIs('pricing') ? 'text-amber-600 bg-amber-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                                Pricing
                            </a>
                        </div>

                        {{-- Right: Actions (desktop) --}}
                        <div class="hidden sm:flex items-center gap-3">
                            <a href="{{ route('submit') }}" class="gradient-amber text-white font-semibold text-sm px-5 py-2.5 rounded-xl shadow-md shadow-amber-500/20 hover:shadow-lg hover:shadow-amber-500/30 hover:-translate-y-0.5 transition-all duration-200">
                                Submit Product
                            </a>

                            @auth
                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open" class="flex items-center gap-2 p-1.5 rounded-xl hover:bg-gray-50 transition-colors">
                                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full object-cover ring-2 ring-gray-100" />
                                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>

                                    <div x-show="open" @click.away="open = false"
                                         x-transition:enter="transition ease-out duration-150"
                                         x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-100"
                                         x-transition:leave-start="opacity-100 scale-100"
                                         x-transition:leave-end="opacity-0 scale-95"
                                         class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 py-2 z-50">
                                        <div class="px-4 py-2.5 border-b border-gray-100 mb-1">
                                            <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                                            <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                                        </div>
                                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                            Dashboard
                                        </a>
                                        <a href="{{ route('dashboard.products') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                            My Products
                                        </a>
                                        <a href="{{ route('profile') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            Settings
                                        </a>
                                        <div class="border-t border-gray-100 my-1.5"></div>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="flex items-center gap-3 w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                                Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900 px-3 py-2 rounded-xl hover:bg-gray-50 transition-all">Login</a>
                                <a href="{{ route('register') }}" class="text-sm font-semibold text-gray-700 border-2 border-gray-200 hover:border-gray-300 hover:bg-gray-50 px-4 py-2 rounded-xl transition-all">Register</a>
                            @endauth
                        </div>

                        {{-- Mobile hamburger --}}
                        <button @click="mobileOpen = !mobileOpen" class="sm:hidden p-2 rounded-xl text-gray-500 hover:bg-gray-100 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Mobile menu --}}
                    <div x-show="mobileOpen"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="sm:hidden pb-4 space-y-1">
                        <a href="{{ route('launches.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all {{ request()->routeIs('launches.*') ? 'text-amber-600 bg-amber-50' : 'text-gray-600 hover:bg-gray-50' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            Launches
                        </a>
                        <a href="{{ route('directory.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all {{ request()->routeIs('directory.*') ? 'text-amber-600 bg-amber-50' : 'text-gray-600 hover:bg-gray-50' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            Directory
                        </a>
                        <a href="{{ route('pricing') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all {{ request()->routeIs('pricing') ? 'text-amber-600 bg-amber-50' : 'text-gray-600 hover:bg-gray-50' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Pricing
                        </a>
                        <div class="pt-2 pb-1 px-3">
                            <a href="{{ route('submit') }}" class="block w-full text-center gradient-amber text-white font-semibold text-sm px-5 py-2.5 rounded-xl shadow-md shadow-amber-500/20">Submit Product</a>
                        </div>
                        @auth
                            <div class="border-t border-gray-100 mt-2 pt-2">
                                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-xl">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                    Dashboard
                                </a>
                                <a href="{{ route('dashboard.products') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-xl">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    My Products
                                </a>
                                <a href="{{ route('profile') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-xl">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Settings
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-3 w-full text-left px-3 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 rounded-xl">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="border-t border-gray-100 mt-2 pt-2 flex gap-2 px-3">
                                <a href="{{ route('login') }}" class="flex-1 text-center text-sm font-semibold text-gray-700 border-2 border-gray-200 hover:border-gray-300 px-4 py-2.5 rounded-xl transition-all">Login</a>
                                <a href="{{ route('register') }}" class="flex-1 text-center text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 px-4 py-2.5 rounded-xl transition-all">Register</a>
                            </div>
                        @endauth
                    </div>
                </div>
            </nav>

            {{-- Page Content --}}
            <main class="flex-1">
                {{ $slot }}
            </main>

            {{-- Footer --}}
            <footer class="gradient-dark text-white mt-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-10">
                        {{-- Left: Branding --}}
                        <div class="md:col-span-4">
                            <div class="h-16">
                                <a href="{{ route('home') }}" class="h-12 -ml-6 inline-flex items-center">
                                    <img src="{{ asset('images/logo/launchory-logo.png') }}" alt="Launchory" class="h-48 w-auto" />
                                </a>
                            </div>
                            <p class="text-gray-400 text-sm mt-3 leading-relaxed max-w-xs">The launchpad for indie makers. Launch your product, get discovered, and grow your audience.</p>
                            <div class="flex items-center gap-3 mt-5">
                                <a href="https://x.com/launchory" target="_blank" rel="noopener" class="w-9 h-9 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 flex items-center justify-center text-gray-400 hover:text-white transition-all">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                </a>
                            </div>
                        </div>

                        {{-- Middle: Links --}}
                        <div class="md:col-span-4 grid grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-4">Platform</h4>
                                <nav class="space-y-2.5">
                                    <a href="{{ route('launches.index') }}" class="block text-sm text-gray-400 hover:text-white transition-colors">Launches</a>
                                    <a href="{{ route('directory.index') }}" class="block text-sm text-gray-400 hover:text-white transition-colors">Directory</a>
                                    <a href="{{ route('submit') }}" class="block text-sm text-gray-400 hover:text-white transition-colors">Submit Product</a>
                                    <a href="{{ route('pricing') }}" class="block text-sm text-gray-400 hover:text-white transition-colors">Pricing</a>
                                </nav>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-4">Resources</h4>
                                <nav class="space-y-2.5">
                                    <a href="{{ route('sitemap') }}" class="block text-sm text-gray-400 hover:text-white transition-colors">Sitemap</a>
                                </nav>
                            </div>
                        </div>

                        {{-- Right: Newsletter --}}
                        <div class="md:col-span-4">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-4">Stay in the loop</h4>
                            <p class="text-sm text-gray-400 mb-4">Get weekly updates on the hottest new launches.</p>
                            <form action="{{ route('newsletter.subscribe') }}" method="POST" class="flex gap-2">
                                @csrf
                                <input type="email" name="email" placeholder="you@email.com" required
                                       class="flex-1 text-sm rounded-xl border border-white/10 bg-white/5 text-white placeholder-gray-500 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent" />
                                <button type="submit" class="gradient-amber text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-lg shadow-amber-500/20 hover:shadow-xl transition-all shrink-0">Subscribe</button>
                            </form>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-between mt-12 pt-8 border-t border-white/10 text-xs text-gray-500">
                        <p>&copy; {{ date('Y') }} Launchory. All rights reserved.</p>
                        <p class="mt-2 sm:mt-0">Made with <span class="text-red-400">&#10084;</span> for indie makers</p>
                    </div>
                </div>
            </footer>
        </div>

        @livewireScripts
    </body>
</html>
