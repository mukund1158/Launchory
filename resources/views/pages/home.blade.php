<x-app-layout>
    {{-- Hero Section --}}
    <section class="relative overflow-hidden gradient-hero">
        {{-- Floating decorative elements --}}
        <div class="absolute top-20 left-10 w-72 h-72 bg-amber-200/30 rounded-full blur-3xl animate-float"></div>
        <div class="absolute bottom-10 right-10 w-96 h-96 bg-orange-200/20 rounded-full blur-3xl animate-float-delay"></div>
        <div class="absolute top-40 right-1/4 w-48 h-48 bg-yellow-200/20 rounded-full blur-2xl"></div>

        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-20 sm:pt-24 sm:pb-28 text-center">
            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 bg-white/80 backdrop-blur-sm border border-amber-200/60 rounded-full px-4 py-1.5 mb-8 shadow-sm">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                </span>
                <span class="text-xs font-semibold text-gray-600 tracking-wide">{{ $todayLaunches->count() }} PRODUCTS LAUNCHING TODAY</span>
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-[1.1]">
                Where Makers Launch
                <br>
                <span class="text-gradient">Their Next Big Thing</span>
            </h1>

            <p class="mt-6 text-lg sm:text-xl text-gray-500 max-w-2xl mx-auto leading-relaxed">
                Submit your product, get upvoted by the community, earn a <strong class="text-gray-700">dofollow backlink</strong> and a winner badge. Join the makers who launch here first.
            </p>

            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('submit') }}" class="group gradient-amber text-white font-bold px-8 py-4 rounded-2xl text-base shadow-lg shadow-amber-500/25 hover:shadow-xl hover:shadow-amber-500/30 hover:-translate-y-1 transition-all duration-300 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Submit Your Product
                </a>
                <a href="#today-launches" class="group bg-white hover:bg-gray-50 text-gray-700 font-semibold px-8 py-4 rounded-2xl text-base border border-gray-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 flex items-center gap-2">
                    See Today's Launches
                    <svg class="w-4 h-4 group-hover:translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                </a>
            </div>

            {{-- Live stats --}}
            <div class="mt-14 flex flex-wrap items-center justify-center gap-6 sm:gap-10">
                <div class="flex items-center gap-2.5 bg-white/60 backdrop-blur-sm rounded-2xl px-5 py-3 border border-white/80 shadow-sm">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-lg">🚀</div>
                    <div class="text-left">
                        <p class="text-xl font-extrabold text-gray-900">{{ number_format($stats['products']) ?: '0' }}</p>
                        <p class="text-[11px] text-gray-400 uppercase tracking-wider font-medium">Products</p>
                    </div>
                </div>
                <div class="flex items-center gap-2.5 bg-white/60 backdrop-blur-sm rounded-2xl px-5 py-3 border border-white/80 shadow-sm">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center text-lg">👨‍💻</div>
                    <div class="text-left">
                        <p class="text-xl font-extrabold text-gray-900">{{ number_format($stats['makers']) ?: '0' }}</p>
                        <p class="text-[11px] text-gray-400 uppercase tracking-wider font-medium">Makers</p>
                    </div>
                </div>
                <div class="flex items-center gap-2.5 bg-white/60 backdrop-blur-sm rounded-2xl px-5 py-3 border border-white/80 shadow-sm">
                    <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center text-lg">🔺</div>
                    <div class="text-left">
                        <p class="text-xl font-extrabold text-gray-900">{{ number_format($stats['votes']) ?: '0' }}</p>
                        <p class="text-[11px] text-gray-400 uppercase tracking-wider font-medium">Upvotes</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Today's Launches (Live) --}}
    <section id="today-launches" class="py-16 sm:py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white text-lg shadow-lg shadow-amber-500/20">🚀</div>
                        <div>
                            <h2 class="text-2xl sm:text-3xl font-extrabold">Today's Launches</h2>
                            <p class="text-gray-400 text-sm">Vote for your favorites &mdash; products reorder in real time</p>
                        </div>
                    </div>
                </div>

                @if($launchPeriod)
                    <div x-data="countdown('{{ $launchPeriod->ends_at->toIso8601String() }}')" class="flex items-center gap-1.5 mt-4 sm:mt-0">
                        <span class="text-xs text-gray-400 mr-1">Ends in</span>
                        @foreach(['days' => 'D', 'hours' => 'H', 'minutes' => 'M', 'seconds' => 'S'] as $unit => $label)
                            <div class="bg-gray-900 rounded-xl px-2.5 py-2 text-center min-w-[2.8rem]">
                                <span class="text-base font-bold text-white font-mono" x-text="{{ $unit }}">00</span>
                                <span class="text-[9px] text-gray-500 block tracking-widest">{{ $label }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Live Livewire component with vote reordering --}}
            <livewire:home-launches />

            @if($todayLaunches->count() > 0)
                <div class="text-center mt-8">
                    <a href="{{ route('launches.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-amber-600 hover:text-amber-700 bg-amber-50 hover:bg-amber-100 px-5 py-2.5 rounded-xl transition-all">
                        View all launches
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                </div>
            @endif
        </div>
    </section>

    {{-- How It Works --}}
    <section class="py-16 sm:py-20 bg-gray-50/60 border-y border-gray-100">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-2xl sm:text-3xl font-extrabold">How Launchory Works</h2>
                <p class="text-gray-500 mt-2">Three simple steps to get your product in front of thousands</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="relative bg-white rounded-2xl border border-gray-100 p-7 text-center shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-2xl text-white shadow-lg shadow-amber-500/20 mx-auto mb-5">1</div>
                    <h3 class="font-bold text-lg text-gray-900 mb-2">Submit Your Product</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Fill out a quick form with your product details, logo, and description. Choose a launch date or list in the directory.</p>
                </div>
                <div class="relative bg-white rounded-2xl border border-gray-100 p-7 text-center shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-2xl text-white shadow-lg shadow-blue-500/20 mx-auto mb-5">2</div>
                    <h3 class="font-bold text-lg text-gray-900 mb-2">Get Upvoted</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">The community votes on launches. Share your launch page and rally support. Top products win badges and backlinks.</p>
                </div>
                <div class="relative bg-white rounded-2xl border border-gray-100 p-7 text-center shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-400 to-green-500 flex items-center justify-center text-2xl text-white shadow-lg shadow-emerald-500/20 mx-auto mb-5">3</div>
                    <h3 class="font-bold text-lg text-gray-900 mb-2">Get Discovered</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Winners get a dofollow backlink, a winner badge for their site, and permanent visibility in our directory.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Featured Products --}}
    @if($featured->count() > 0)
    <section class="py-16 sm:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-400 to-amber-500 flex items-center justify-center text-white text-lg shadow-lg shadow-amber-500/20">⭐</div>
                    <div>
                        <h2 class="text-2xl sm:text-3xl font-extrabold">Featured Products</h2>
                        <p class="text-gray-400 text-sm">Handpicked by our team</p>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($featured as $product)
                    <div class="relative bg-white rounded-2xl border border-gray-100 p-6 card-hover group overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-amber-50 to-transparent rounded-bl-[80px]"></div>
                        <div class="absolute top-4 right-4">
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider bg-gradient-to-r from-amber-400 to-orange-400 text-white px-3 py-1 rounded-full shadow-sm">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                Featured
                            </span>
                        </div>
                        <img src="{{ $product->logo_url }}" alt="{{ $product->name }}" class="relative w-14 h-14 rounded-2xl object-cover border border-gray-100 shadow-sm mb-4" />
                        <a href="{{ route('product.show', $product->slug) }}" class="relative font-bold text-gray-900 group-hover:text-amber-600 transition-colors block text-lg mb-1">{{ $product->name }}</a>
                        <p class="relative text-sm text-gray-500 line-clamp-2 mb-4 leading-relaxed">{{ $product->tagline }}</p>
                        <div class="relative flex items-center justify-between pt-4 border-t border-gray-100">
                            <span class="text-xs bg-gray-50 text-gray-500 px-2.5 py-1 rounded-full border border-gray-100">{{ $product->category->icon }} {{ $product->category->name }}</span>
                            <a href="{{ route('product.show', $product->slug) }}" class="text-sm font-semibold text-amber-600 hover:text-amber-700 flex items-center gap-1 transition-colors">
                                View <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Browse by Category --}}
    @if($categories->count() > 0)
    <section class="py-16 sm:py-20 bg-gray-50/60 border-y border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white text-lg shadow-lg shadow-blue-500/20">📂</div>
                    <div>
                        <h2 class="text-2xl sm:text-3xl font-extrabold">Browse by Category</h2>
                        <p class="text-gray-400 text-sm">Find products in your niche</p>
                    </div>
                </div>
                <a href="{{ route('directory.index') }}" class="hidden sm:inline-flex items-center gap-1 text-sm font-semibold text-amber-600 hover:text-amber-700 transition-colors">
                    View All <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
                @foreach($categories as $cat)
                    <a href="{{ route('directory.category', $cat->slug) }}" class="group bg-white rounded-2xl border border-gray-100 p-4 hover:border-amber-200 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl w-10 h-10 rounded-xl bg-gray-50 group-hover:bg-amber-50 flex items-center justify-center transition-colors">{{ $cat->icon }}</span>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-800 group-hover:text-amber-600 truncate transition-colors">{{ $cat->name }}</p>
                                <p class="text-xs text-gray-400">{{ $cat->products_count }} {{ Str::plural('product', $cat->products_count) }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Recently Added to Directory --}}
    @if($latestDirectory->count() > 0)
    <section class="py-16 sm:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-400 to-green-500 flex items-center justify-center text-white text-lg shadow-lg shadow-emerald-500/20">📁</div>
                    <div>
                        <h2 class="text-2xl sm:text-3xl font-extrabold">Recently Added</h2>
                        <p class="text-gray-400 text-sm">Fresh products in the directory</p>
                    </div>
                </div>
                <a href="{{ route('directory.index') }}" class="hidden sm:inline-flex items-center gap-1 text-sm font-semibold text-amber-600 hover:text-amber-700 transition-colors">
                    Browse All <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($latestDirectory as $product)
                    <div class="bg-white rounded-2xl border border-gray-100 p-5 card-hover group">
                        <div class="flex items-start gap-3.5">
                            <img src="{{ $product->logo_url }}" alt="{{ $product->name }}" class="w-12 h-12 rounded-xl object-cover border border-gray-100 shadow-sm" />
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('product.show', $product->slug) }}" class="font-bold text-gray-900 group-hover:text-amber-600 truncate block transition-colors">{{ $product->name }}</a>
                                <p class="text-sm text-gray-500 line-clamp-2 mt-0.5 leading-relaxed">{{ $product->tagline }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-50">
                            <div class="flex items-center gap-2">
                                <span class="text-xs bg-gray-50 text-gray-500 px-2.5 py-1 rounded-full border border-gray-100">{{ $product->category->icon }} {{ $product->category->name }}</span>
                                <span class="text-xs px-2.5 py-1 rounded-full font-medium
                                    {{ $product->pricing === 'free' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : ($product->pricing === 'freemium' ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'bg-purple-50 text-purple-600 border border-purple-100') }}">
                                    {{ ucfirst($product->pricing) }}
                                </span>
                            </div>
                            <a href="{{ route('product.show', $product->slug) }}" class="text-sm font-semibold text-amber-600 hover:text-amber-700 flex items-center gap-1 transition-colors">
                                View <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="text-center mt-8 sm:hidden">
                <a href="{{ route('directory.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-amber-600 hover:text-amber-700">
                    Browse All <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            </div>
        </div>
    </section>
    @endif

    {{-- Past Winners --}}
    @if($latestWinners->count() > 0)
    <section class="py-16 sm:py-20 bg-gray-50/60 border-y border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-400 to-violet-500 flex items-center justify-center text-white text-lg shadow-lg shadow-purple-500/20">🏆</div>
                    <div>
                        <h2 class="text-2xl sm:text-3xl font-extrabold">Past Winners</h2>
                        <p class="text-gray-400 text-sm">Products that topped the leaderboard</p>
                    </div>
                </div>
                <a href="{{ route('launches.archive') }}" class="hidden sm:inline-flex items-center gap-1 text-sm font-semibold text-amber-600 hover:text-amber-700 transition-colors">
                    View Archive <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($latestWinners as $product)
                    <div class="bg-white rounded-2xl border border-gray-100 p-5 card-hover group">
                        <div class="flex items-center gap-3.5">
                            <div class="relative">
                                <img src="{{ $product->logo_url }}" alt="{{ $product->name }}" class="w-12 h-12 rounded-xl object-cover border border-gray-100 shadow-sm" />
                                <span class="absolute -top-1.5 -right-1.5 text-sm">{{ $product->badge->badge_emoji }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('product.show', $product->slug) }}" class="font-bold text-gray-900 group-hover:text-amber-600 truncate block transition-colors">{{ $product->name }}</a>
                                <p class="text-sm text-gray-500 truncate">{{ $product->tagline }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-50">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center gap-1 text-xs font-medium {{ $product->badge->badge_color }} px-2.5 py-1 rounded-full">
                                    {{ $product->badge->badge_emoji }} {{ ucfirst($product->badge->rank) }}
                                </span>
                                <span class="text-xs text-gray-400">{{ $product->badge->launch_date->format('M d') }}</span>
                            </div>
                            <a href="{{ route('product.show', $product->slug) }}" class="text-sm font-semibold text-amber-600 hover:text-amber-700 flex items-center gap-1 transition-colors">
                                View <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- What You Get --}}
    <section class="py-16 sm:py-20 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-2xl sm:text-3xl font-extrabold">Why Launch on Launchory?</h2>
                <p class="text-gray-500 mt-2">Everything you need to boost your product's visibility</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="flex items-start gap-4 bg-gradient-to-br from-amber-50/50 to-white rounded-2xl border border-amber-100/60 p-6">
                    <div class="w-11 h-11 rounded-xl bg-amber-100 flex items-center justify-center text-xl shrink-0">🔗</div>
                    <div>
                        <h3 class="font-bold text-gray-900 mb-1">Dofollow Backlink</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">Winners get a permanent dofollow link, boosting your SEO and domain authority.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4 bg-gradient-to-br from-blue-50/50 to-white rounded-2xl border border-blue-100/60 p-6">
                    <div class="w-11 h-11 rounded-xl bg-blue-100 flex items-center justify-center text-xl shrink-0">🏅</div>
                    <div>
                        <h3 class="font-bold text-gray-900 mb-1">Winner Badge</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">Embed a beautiful badge on your site to showcase your achievement and build trust.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4 bg-gradient-to-br from-green-50/50 to-white rounded-2xl border border-green-100/60 p-6">
                    <div class="w-11 h-11 rounded-xl bg-green-100 flex items-center justify-center text-xl shrink-0">👥</div>
                    <div>
                        <h3 class="font-bold text-gray-900 mb-1">Community Exposure</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">Get your product in front of makers, early adopters, and tech enthusiasts actively looking for tools.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4 bg-gradient-to-br from-purple-50/50 to-white rounded-2xl border border-purple-100/60 p-6">
                    <div class="w-11 h-11 rounded-xl bg-purple-100 flex items-center justify-center text-xl shrink-0">📁</div>
                    <div>
                        <h3 class="font-bold text-gray-900 mb-1">Permanent Listing</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">Your product stays in our directory forever, driving long-term organic traffic to your site.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-20 sm:py-24 gradient-dark relative overflow-hidden">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-1/4 w-72 h-72 bg-orange-500/10 rounded-full blur-3xl"></div>
        <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/10 rounded-full px-4 py-1.5 mb-6">
                <span class="text-amber-400 text-sm">✨</span>
                <span class="text-xs font-semibold text-gray-300 tracking-wide">FREE TO SUBMIT</span>
            </div>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-4">Ready to launch your product?</h2>
            <p class="text-gray-400 text-lg mb-10 max-w-lg mx-auto">Join {{ $stats['makers'] ?: 'hundreds of' }} makers who've already gotten discovered on Launchory.</p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('submit') }}" class="inline-flex items-center gap-2 gradient-amber text-white font-bold px-10 py-4 rounded-2xl text-lg shadow-lg shadow-amber-500/25 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    Submit Your Product
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
                <a href="{{ route('pricing') }}" class="inline-flex items-center gap-2 text-white/80 hover:text-white font-semibold border border-white/20 hover:border-white/40 px-8 py-4 rounded-2xl transition-all">
                    View Pricing
                </a>
            </div>
        </div>
    </section>

    <script>
        function countdown(endDate) {
            return {
                days: '00', hours: '00', minutes: '00', seconds: '00',
                init() { this.update(); setInterval(() => this.update(), 1000); },
                update() {
                    const diff = Math.max(0, new Date(endDate) - new Date());
                    this.days = String(Math.floor(diff / 86400000)).padStart(2, '0');
                    this.hours = String(Math.floor((diff % 86400000) / 3600000)).padStart(2, '0');
                    this.minutes = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
                    this.seconds = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');
                }
            }
        }
    </script>
</x-app-layout>
