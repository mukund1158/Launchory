<x-app-layout>
    {{-- Header --}}
    <div class="relative overflow-hidden bg-gradient-to-br from-amber-50 via-orange-50/30 to-white border-b border-gray-100">
        <div class="absolute top-0 right-0 w-96 h-96 bg-amber-200/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-orange-200/15 rounded-full blur-3xl translate-y-1/2 -translate-x-1/3"></div>
        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-14 sm:py-20 text-center">
            <div class="inline-flex items-center gap-2 bg-white/80 backdrop-blur-sm border border-amber-200/60 rounded-full px-4 py-1.5 mb-5 shadow-sm">
                <span class="text-sm">💰</span>
                <span class="text-xs font-semibold text-gray-600 tracking-wide">SIMPLE & TRANSPARENT</span>
            </div>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gray-900">Choose the right plan for your product</h1>
            <p class="text-gray-500 mt-4 max-w-xl mx-auto text-lg">Launch for free or boost your visibility with premium placement. No hidden fees.</p>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
        {{-- Launch Plans --}}
        <div class="mb-16">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-lg text-white shadow-lg shadow-amber-500/20">⚡</div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900">Launch Plans</h2>
                    <p class="text-sm text-gray-500">Compete for votes on launch day</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Free Launch --}}
                <div class="bg-white rounded-2xl border-2 border-gray-200 p-7 sm:p-8 flex flex-col relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gray-50 rounded-bl-[80px]"></div>
                    <div class="relative">
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="text-xl font-bold text-gray-900">Free Launch</h3>
                            <span class="text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-600 border border-emerald-100 px-2.5 py-0.5 rounded-full">Free Forever</span>
                        </div>
                        <p class="text-sm text-gray-500">Everything you need to launch your product</p>
                    </div>
                    <div class="mt-6 mb-8">
                        <span class="text-5xl font-extrabold text-gray-900">$0</span>
                    </div>
                    <ul class="space-y-3.5 text-sm text-gray-600 flex-1">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Launch day listing
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Community voting
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Winner badge if top 3
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Dofollow backlink if top 3
                        </li>
                        <li class="flex items-start gap-3 text-gray-400">
                            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            <span class="line-through">Directory listing</span>
                        </li>
                        <li class="flex items-start gap-3 text-gray-400">
                            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            <span class="line-through">Featured placement</span>
                        </li>
                    </ul>
                    <a href="{{ route('submit') }}" class="mt-8 block text-center bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-3.5 rounded-xl transition-all text-sm">
                        Get Started Free
                    </a>
                </div>

                {{-- Featured Launch --}}
                <div class="bg-white rounded-2xl border-2 border-amber-400 p-7 sm:p-8 flex flex-col relative overflow-hidden shadow-lg shadow-amber-500/10">
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                        <span class="bg-gradient-to-r from-amber-400 to-orange-400 text-white text-xs font-bold px-5 py-1.5 rounded-full shadow-md shadow-amber-500/20">Popular</span>
                    </div>
                    <div class="absolute top-0 right-0 w-32 h-32 bg-amber-50 rounded-bl-[80px]"></div>
                    <div class="relative">
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="text-xl font-bold text-gray-900">Featured Launch</h3>
                            <span class="text-[10px] font-bold uppercase tracking-wider bg-amber-100 text-amber-700 border border-amber-200 px-2.5 py-0.5 rounded-full">One-time</span>
                        </div>
                        <p class="text-sm text-gray-500">Maximum visibility on launch day</p>
                    </div>
                    <div class="mt-6 mb-8">
                        <span class="text-5xl font-extrabold text-gray-900">$19</span>
                    </div>
                    <ul class="space-y-3.5 text-sm text-gray-600 flex-1">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Everything in Free Launch
                        </li>
                        <li class="flex items-start gap-3 font-medium text-gray-800">
                            <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Highlighted card on launch day
                        </li>
                        <li class="flex items-start gap-3 font-medium text-gray-800">
                            <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Top placement in launch list
                        </li>
                        <li class="flex items-start gap-3 font-medium text-gray-800">
                            <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Mentioned in weekly newsletter
                        </li>
                    </ul>
                    <a href="{{ route('submit') }}" class="mt-8 block text-center gradient-amber text-white font-bold py-3.5 rounded-xl shadow-lg shadow-amber-500/20 hover:shadow-xl hover:-translate-y-0.5 transition-all text-sm">
                        Get Featured Launch
                    </a>
                </div>
            </div>
        </div>

        {{-- Directory Plans --}}
        <div class="mb-16">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-lg text-white shadow-lg shadow-blue-500/20">📁</div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900">Directory Plans</h2>
                    <p class="text-sm text-gray-500">Permanent listing in our product directory</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Standard Directory --}}
                <div class="bg-white rounded-2xl border-2 border-gray-200 p-7 sm:p-8 flex flex-col relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50/50 rounded-bl-[80px]"></div>
                    <div class="relative">
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="text-xl font-bold text-gray-900">Directory Listing</h3>
                            <span class="text-[10px] font-bold uppercase tracking-wider bg-blue-50 text-blue-600 border border-blue-100 px-2.5 py-0.5 rounded-full">Monthly</span>
                        </div>
                        <p class="text-sm text-gray-500">Get your product listed permanently</p>
                    </div>
                    <div class="mt-6 mb-8">
                        <span class="text-5xl font-extrabold text-gray-900">$9</span>
                        <span class="text-gray-400 text-sm ml-1">/month</span>
                    </div>
                    <ul class="space-y-3.5 text-sm text-gray-600 flex-1">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Permanent directory listing
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Dofollow backlink to your site
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Category page listing
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Maker profile page
                        </li>
                        <li class="flex items-start gap-3 text-gray-400">
                            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            <span class="line-through">Featured placement</span>
                        </li>
                    </ul>
                    <a href="{{ route('submit') }}" class="mt-8 block text-center bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-3.5 rounded-xl transition-all text-sm">
                        Get Directory Listing
                    </a>
                </div>

                {{-- Featured Directory --}}
                <div class="bg-white rounded-2xl border-2 border-blue-400 p-7 sm:p-8 flex flex-col relative overflow-hidden shadow-lg shadow-blue-500/10">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-bl-[80px]"></div>
                    <div class="relative">
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="text-xl font-bold text-gray-900">Featured Directory</h3>
                            <span class="text-[10px] font-bold uppercase tracking-wider bg-blue-100 text-blue-700 border border-blue-200 px-2.5 py-0.5 rounded-full">Monthly</span>
                        </div>
                        <p class="text-sm text-gray-500">Premium placement & maximum visibility</p>
                    </div>
                    <div class="mt-6 mb-8">
                        <span class="text-5xl font-extrabold text-gray-900">$19</span>
                        <span class="text-gray-400 text-sm ml-1">/month</span>
                    </div>
                    <ul class="space-y-3.5 text-sm text-gray-600 flex-1">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Everything in Directory Listing
                        </li>
                        <li class="flex items-start gap-3 font-medium text-gray-800">
                            <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Homepage spotlight section
                        </li>
                        <li class="flex items-start gap-3 font-medium text-gray-800">
                            <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Category page top position
                        </li>
                        <li class="flex items-start gap-3 font-medium text-gray-800">
                            <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            "Featured" badge on listing
                        </li>
                    </ul>
                    <a href="{{ route('submit') }}" class="mt-8 block text-center bg-blue-500 hover:bg-blue-600 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-blue-500/20 hover:shadow-xl hover:-translate-y-0.5 transition-all text-sm">
                        Get Featured Directory
                    </a>
                </div>
            </div>
        </div>

        {{-- Bundle Plan --}}
        <div class="mb-16">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-400 to-violet-500 flex items-center justify-center text-lg text-white shadow-lg shadow-purple-500/20">🚀</div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900">Launch + Directory Bundles</h2>
                    <p class="text-sm text-gray-500">Best of both worlds — launch day competition + permanent directory listing</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Standard Bundle --}}
                <div class="bg-white rounded-2xl border-2 border-gray-200 p-7 sm:p-8 flex flex-col relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-purple-50/50 rounded-bl-[80px]"></div>
                    <div class="relative">
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="text-xl font-bold text-gray-900">Launch + Directory</h3>
                            <span class="text-[10px] font-bold uppercase tracking-wider bg-purple-50 text-purple-600 border border-purple-100 px-2.5 py-0.5 rounded-full">Monthly</span>
                        </div>
                        <p class="text-sm text-gray-500">Free launch + permanent directory listing</p>
                    </div>
                    <div class="mt-6 mb-8">
                        <span class="text-5xl font-extrabold text-gray-900">$9</span>
                        <span class="text-gray-400 text-sm ml-1">/month</span>
                    </div>
                    <ul class="space-y-3.5 text-sm text-gray-600 flex-1">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Launch day listing (free)
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Community voting & badges
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Permanent directory listing
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Dofollow backlink
                        </li>
                    </ul>
                    <a href="{{ route('submit') }}" class="mt-8 block text-center bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-3.5 rounded-xl transition-all text-sm">
                        Get Launch + Directory
                    </a>
                </div>

                {{-- Featured Bundle --}}
                <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-7 sm:p-8 flex flex-col relative overflow-hidden shadow-xl">
                    <div class="absolute top-0 right-0 w-48 h-48 bg-amber-500/10 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 w-32 h-32 bg-purple-500/10 rounded-full blur-2xl"></div>
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                        <span class="bg-gradient-to-r from-amber-400 to-orange-400 text-white text-xs font-bold px-5 py-1.5 rounded-full shadow-md shadow-amber-500/30">Best Value</span>
                    </div>
                    <div class="relative">
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="text-xl font-bold text-white">Featured Bundle</h3>
                            <span class="text-[10px] font-bold uppercase tracking-wider bg-white/10 text-amber-400 border border-white/10 px-2.5 py-0.5 rounded-full">Monthly</span>
                        </div>
                        <p class="text-sm text-gray-400">The ultimate package for maximum exposure</p>
                    </div>
                    <div class="mt-6 mb-8">
                        <span class="text-5xl font-extrabold text-white">$39</span>
                        <span class="text-gray-500 text-sm ml-1">/month</span>
                    </div>
                    <ul class="space-y-3.5 text-sm text-gray-300 flex-1">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Everything in Launch + Directory
                        </li>
                        <li class="flex items-start gap-3 font-medium text-white">
                            <svg class="w-5 h-5 text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Featured on launch day
                        </li>
                        <li class="flex items-start gap-3 font-medium text-white">
                            <svg class="w-5 h-5 text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Homepage spotlight
                        </li>
                        <li class="flex items-start gap-3 font-medium text-white">
                            <svg class="w-5 h-5 text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Newsletter mention
                        </li>
                        <li class="flex items-start gap-3 font-medium text-white">
                            <svg class="w-5 h-5 text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            "Featured" badge everywhere
                        </li>
                    </ul>
                    <a href="{{ route('submit') }}" class="mt-8 block text-center gradient-amber text-white font-bold py-3.5 rounded-xl shadow-lg shadow-amber-500/25 hover:shadow-xl hover:-translate-y-0.5 transition-all text-sm">
                        Get Featured Bundle
                    </a>
                </div>
            </div>
        </div>

        {{-- Comparison Table --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 sm:px-8 py-6 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900">Compare all plans</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="text-left py-4 px-6 font-semibold text-gray-500 w-1/3">Feature</th>
                            <th class="text-center py-4 px-4 font-semibold text-gray-900">Free Launch</th>
                            <th class="text-center py-4 px-4 font-semibold text-gray-900">Featured Launch</th>
                            <th class="text-center py-4 px-4 font-semibold text-gray-900">Directory</th>
                            <th class="text-center py-4 px-4 font-semibold text-gray-900">Featured Bundle</th>
                        </tr>
                        <tr class="border-b border-gray-100 bg-gray-50/30">
                            <th class="text-left py-2 px-6 font-normal text-gray-400 text-xs">Price</th>
                            <td class="text-center py-2 px-4 font-bold text-gray-900">$0</td>
                            <td class="text-center py-2 px-4 font-bold text-gray-900">$19</td>
                            <td class="text-center py-2 px-4 font-bold text-gray-900">$9/mo</td>
                            <td class="text-center py-2 px-4 font-bold text-amber-600">$39/mo</td>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $features = [
                                ['Launch day listing', true, true, false, true],
                                ['Community voting', true, true, false, true],
                                ['Winner badge', true, true, false, true],
                                ['Directory listing', false, false, true, true],
                                ['Dofollow backlink', 'Top 3', 'Top 3', true, true],
                                ['Highlighted card', false, true, false, true],
                                ['Top placement', false, true, false, true],
                                ['Homepage spotlight', false, false, false, true],
                                ['Category top position', false, false, false, true],
                                ['Newsletter mention', false, true, false, true],
                                ['Featured badge', false, true, false, true],
                            ];
                        @endphp
                        @foreach($features as $row)
                            <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                                <td class="py-3.5 px-6 text-gray-700 font-medium">{{ $row[0] }}</td>
                                @for($i = 1; $i <= 4; $i++)
                                    <td class="text-center py-3.5 px-4">
                                        @if($row[$i] === true)
                                            <svg class="w-5 h-5 text-emerald-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        @elseif($row[$i] === false)
                                            <svg class="w-5 h-5 text-gray-200 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                        @else
                                            <span class="text-xs font-medium text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">{{ $row[$i] }}</span>
                                        @endif
                                    </td>
                                @endfor
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- FAQ --}}
        <div class="mt-16 max-w-3xl mx-auto">
            <h3 class="text-2xl font-extrabold text-center mb-8">Frequently asked questions</h3>
            <div class="space-y-4" x-data="{ open: null }">
                @foreach([
                    ['q' => 'Can I launch for free?', 'a' => 'Yes! The Free Launch plan lets you compete on launch day with community voting. If you finish in the top 3, you earn a winner badge and a dofollow backlink — all for free. Directory listing requires a paid plan.'],
                    ['q' => 'What\'s the difference between Launch and Directory?', 'a' => 'Launch is a one-day competition where the community votes for products. Directory is a permanent listing that stays online and drives ongoing organic traffic. You can choose one or both.'],
                    ['q' => 'When do I need to pay?', 'a' => 'Only after your product is approved. Our team reviews every submission within 24 hours. Once approved, you\'ll receive a secure payment link via email for paid plans.'],
                    ['q' => 'Can I upgrade my plan later?', 'a' => 'Absolutely. You can start with a free launch and upgrade to a directory listing or featured placement anytime from your dashboard.'],
                    ['q' => 'What is a dofollow backlink?', 'a' => 'A dofollow backlink tells search engines to pass SEO authority from our site to yours, helping improve your domain rating and search rankings. It\'s one of the most valuable things you can get for your site\'s SEO.'],
                ] as $i => $faq)
                    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                        <button @click="open = open === {{ $i }} ? null : {{ $i }}" class="flex items-center justify-between w-full px-6 py-4 text-left">
                            <span class="text-sm font-semibold text-gray-900">{{ $faq['q'] }}</span>
                            <svg class="w-5 h-5 text-gray-400 shrink-0 transition-transform duration-200" :class="open === {{ $i }} && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open === {{ $i }}" x-collapse class="px-6 pb-4">
                            <p class="text-sm text-gray-500 leading-relaxed">{{ $faq['a'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
