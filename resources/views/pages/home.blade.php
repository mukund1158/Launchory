<x-app-layout>
    {{-- Compact top bar (no hero) — like TinyLaunch / Product Hunt --}}
    <section class="border-b border-gray-100 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">
                    Launch today, get a badge & dofollow backlink
                </h1>
                <p class="text-sm text-gray-500 mt-1">Top 3 launches win badges and a dofollow link. Free to submit.</p>
            </div>
            <div class="flex items-center gap-6 mt-4 pt-4 border-t border-gray-50 text-sm text-gray-500">
                <span class="flex items-center gap-1.5"><span class="font-semibold text-gray-700">{{ number_format($stats['products']) }}</span> products</span>
                <span class="flex items-center gap-1.5"><span class="font-semibold text-gray-700">{{ number_format($stats['makers']) }}</span> makers</span>
                <span class="flex items-center gap-1.5"><span class="font-semibold text-gray-700">{{ number_format($stats['votes']) }}</span> upvotes</span>
            </div>
        </div>
    </section>

    {{-- Today's Launches (primary content first) --}}
    <section id="today-launches" class="py-10 sm:py-12 bg-gray-50/50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div class="flex items-center gap-4 flex-wrap">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Launching now</h2>
                    <a href="{{ route('launches.index') }}" class="text-sm font-semibold text-amber-600 hover:text-amber-700">View all launches →</a>
                </div>
                {{-- Next launch card (boxed countdown) --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 inline-flex flex-col">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Next launch at 12:00 PM</span>
                    <div x-data="countdown('{{ $nextLaunchAt->toIso8601String() }}')" class="flex items-center gap-2">
                        @foreach(['days' => 'D', 'hours' => 'H', 'minutes' => 'M', 'seconds' => 'S'] as $unit => $label)
                            <div class="bg-gray-900 rounded-xl px-3 py-2.5 text-center min-w-[3.25rem]">
                                <span class="text-lg font-bold text-white font-mono block" x-text="{{ $unit }}">00</span>
                                <span class="text-[9px] text-gray-400 block tracking-widest">{{ $label }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <livewire:home-launches />
            @if($todayLaunches->count() > 0)
                <div class="text-center mt-6">
                    <a href="{{ route('launches.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-amber-600 hover:text-amber-700">View all launches <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></a>
                </div>
            @endif
        </div>
    </section>

    {{-- Featured by category (different category sections at top) --}}
    @if($featuredByCategory->isNotEmpty())
    <section class="py-10 sm:py-12 bg-white border-t border-gray-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            @foreach($featuredByCategory as $item)
                @php $cat = $item['category']; $products = $item['products']; @endphp
                @if($products->isNotEmpty())
                <div class="mb-10 last:mb-0">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-base font-bold text-gray-900">{{ $cat->icon }} {{ $cat->name }}</h2>
                        <a href="{{ route('directory.category', $cat->slug) }}" class="text-sm font-semibold text-amber-600 hover:text-amber-700">View category →</a>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach($products as $product)
                        <a href="{{ $product->website_url ?: route('product.show', $product->slug) }}" target="_blank" rel="noopener noreferrer" class="group flex items-start gap-3 p-4 rounded-xl border border-gray-100 hover:border-amber-200 hover:shadow-md transition-all">
                            <img src="{{ $product->logo_url }}" alt="{{ $product->name }}" class="w-11 h-11 rounded-xl object-cover border border-gray-100 shrink-0" />
                            <div class="min-w-0 flex-1">
                                <h3 class="font-semibold text-gray-900 group-hover:text-amber-600 truncate">{{ $product->name }}</h3>
                                <p class="text-xs text-gray-500 line-clamp-2 mt-0.5">{{ $product->tagline }}</p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </section>
    @endif

    {{-- Browse by category (horizontal pills) --}}
    @if($categories->count() > 0)
    <section class="py-8 sm:py-10 bg-gray-50/50 border-t border-gray-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-base font-bold text-gray-900 mb-4">Browse by category</h2>
            <div class="flex flex-wrap gap-2">
                @foreach($categories as $cat)
                    <a href="{{ route('directory.category', $cat->slug) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-gray-200 text-sm font-medium text-gray-700 hover:border-amber-300 hover:bg-amber-50 hover:text-amber-700 transition-all">
                        <span>{{ $cat->icon }}</span>
                        <span>{{ $cat->name }}</span>
                        <span class="text-gray-400 text-xs">({{ $cat->products_count }})</span>
                    </a>
                @endforeach
            </div>
            <div class="mt-4">
                <a href="{{ route('directory.index') }}" class="text-sm font-semibold text-amber-600 hover:text-amber-700">View full directory →</a>
            </div>
        </div>
    </section>
    @endif

    {{-- Recently added to directory --}}
    @if($latestDirectory->count() > 0)
    <section class="py-10 sm:py-12 bg-white border-t border-gray-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-bold text-gray-900">Recently added</h2>
                <a href="{{ route('directory.index') }}" class="text-sm font-semibold text-amber-600 hover:text-amber-700">Browse all →</a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($latestDirectory as $product)
                <a href="{{ $product->website_url ?: route('product.show', $product->slug) }}" target="_blank" rel="noopener noreferrer" class="group flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:border-amber-200 hover:shadow-sm transition-all">
                    <img src="{{ $product->logo_url }}" alt="{{ $product->name }}" class="w-10 h-10 rounded-lg object-cover border border-gray-100 shrink-0" />
                    <div class="min-w-0 flex-1">
                        <h3 class="font-semibold text-gray-900 group-hover:text-amber-600 truncate text-sm">{{ $product->name }}</h3>
                        <p class="text-xs text-gray-500 truncate">{{ $product->category->name }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Latest winners --}}
    @if($latestWinners->count() > 0)
    <section class="py-10 sm:py-12 bg-gray-50/50 border-t border-gray-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-bold text-gray-900">Latest winners</h2>
                <a href="{{ route('launches.archive') }}" class="text-sm font-semibold text-amber-600 hover:text-amber-700">View archive →</a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($latestWinners as $product)
                <a href="{{ $product->website_url ?: route('product.show', $product->slug) }}" target="_blank" rel="noopener noreferrer" class="group flex items-center gap-3 p-4 rounded-xl bg-white border border-gray-100 hover:border-amber-200 hover:shadow-sm transition-all">
                    <div class="relative shrink-0">
                        <img src="{{ $product->logo_url }}" alt="{{ $product->name }}" class="w-12 h-12 rounded-xl object-cover border border-gray-100" />
                        <span class="absolute -top-1 -right-1 text-sm">{{ $product->badge->badge_emoji }}</span>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 class="font-semibold text-gray-900 group-hover:text-amber-600 truncate">{{ $product->name }}</h3>
                        <p class="text-xs text-gray-500">{{ $product->badge->badge_emoji }} {{ ucfirst($product->badge->rank) }} · {{ $product->badge->launch_date->format('M d, Y') }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Compact CTA --}}
    <section class="py-12 sm:py-14 bg-white border-t border-gray-100">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-gray-600 mb-6">Join {{ $stats['makers'] ?: 'hundreds of' }} makers. Submit once, get a dofollow backlink and winner badge when you make the top 3.</p>
            <div class="flex items-center justify-center gap-4 text-sm">
                <a href="{{ route('pricing') }}" class="font-semibold text-amber-600 hover:text-amber-700">View pricing</a>
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
