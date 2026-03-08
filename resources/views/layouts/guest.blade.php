<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/png" href="{{ asset('images/logo/launchory-favicon.png') }}">

        <title>{{ config('app.name', 'Launchory') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased" style="font-family: 'Inter', sans-serif;">
        <div class="min-h-screen flex">
            {{-- Left side - Branding --}}
            <div class="hidden lg:flex lg:w-1/2 gradient-dark relative overflow-hidden flex-col justify-between p-12">
                {{-- Decorative --}}
                <div class="absolute top-0 right-0 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-72 h-72 bg-orange-500/10 rounded-full blur-3xl"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-amber-500/5 rounded-full blur-2xl"></div>

                {{-- Logo --}}
                <div class="relative">
                    <a href="{{ route('home') }}" class="inline-flex items-center">
                        <img src="{{ asset('images/logo/launchory-logo.png') }}" alt="Launchory" class="h-48 w-auto" />
                    </a>
                </div>

                {{-- Middle content --}}
                <div class="relative">
                    <h2 class="text-4xl font-extrabold text-white leading-tight mb-4">
                        Launch your product.<br>
                        <span class="text-amber-400">Get discovered.</span>
                    </h2>
                    <p class="text-gray-400 text-lg leading-relaxed max-w-md">
                        Join hundreds of makers who've already gotten their products in front of thousands of users.
                    </p>

                    {{-- Stats --}}
                    <div class="flex gap-8 mt-10">
                        <div>
                            <p class="text-2xl font-extrabold text-amber-400">500+</p>
                            <p class="text-xs text-gray-500 uppercase tracking-wider mt-1">Makers</p>
                        </div>
                        <div>
                            <p class="text-2xl font-extrabold text-amber-400">1.2K</p>
                            <p class="text-xs text-gray-500 uppercase tracking-wider mt-1">Products</p>
                        </div>
                        <div>
                            <p class="text-2xl font-extrabold text-amber-400">10K+</p>
                            <p class="text-xs text-gray-500 uppercase tracking-wider mt-1">Votes</p>
                        </div>
                    </div>
                </div>

                {{-- Testimonial --}}
                <div class="relative glass-dark rounded-2xl p-6 border border-white/10">
                    <p class="text-gray-300 text-sm leading-relaxed italic">"Launchory helped us get our first 100 users. The dofollow backlink alone made it worth it!"</p>
                    <div class="flex items-center gap-3 mt-4">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-xs font-bold text-white">J</div>
                        <div>
                            <p class="text-sm font-semibold text-white">James K.</p>
                            <p class="text-xs text-gray-500">Founder, SaaSly</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right side - Form --}}
            <div class="w-full lg:w-1/2 flex flex-col justify-center items-center px-6 sm:px-12 py-12 bg-white">
                {{-- Mobile logo --}}
                <div class="lg:hidden mb-8">
                    <a href="{{ route('home') }}" class="inline-flex items-center">
                        <img src="{{ asset('images/logo/launchory-logo.png') }}" alt="Launchory" class="h-9 w-auto" />
                    </a>
                </div>

                <div class="w-full max-w-md">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
