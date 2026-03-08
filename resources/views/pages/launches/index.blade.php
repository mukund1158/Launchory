<x-app-layout>
    {{-- Simple header: no countdown --}}
    <section class="border-b border-gray-100 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Today's Launches</h1>
                    <p class="text-sm text-gray-500 mt-1">Vote for your favorites. Top 3 win a badge and dofollow backlink.</p>
                </div>
                <a href="{{ route('launches.archive') }}" class="text-sm font-semibold text-amber-600 hover:text-amber-700">View archive →</a>
            </div>
        </div>
    </section>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        {{-- Featured products: proper listing (main content) --}}
        <div class="mb-8">
            <h2 class="text-lg font-bold text-gray-900 mb-1">Featured launches</h2>
            <p class="text-sm text-gray-500 mb-6">Editor-picked products launching today.</p>

            @if($featured->count() > 0)
                <div class="space-y-4">
                    @foreach($featured as $product)
                        <a href="{{ route('product.show', $product->slug) }}" class="block group">
                            <div class="bg-white rounded-2xl border border-amber-100/80 p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center gap-4 hover:border-amber-200 hover:shadow-lg transition-all">
                                <div class="flex items-center gap-4 flex-1 min-w-0">
                                    <img src="{{ $product->logo_url }}" alt="{{ $product->name }}" class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl object-cover border-2 border-white shadow-md shrink-0" />
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h3 class="font-bold text-gray-900 group-hover:text-amber-600 truncate text-lg">{{ $product->name }}</h3>
                                            <span class="inline-flex items-center gap-0.5 text-[10px] font-bold uppercase tracking-wider bg-gradient-to-r from-amber-400 to-orange-400 text-white px-2 py-0.5 rounded-full shrink-0">
                                                <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                Featured
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-500 mt-0.5 line-clamp-2">{{ $product->tagline }}</p>
                                        <div class="flex items-center gap-3 mt-2 flex-wrap">
                                            <span class="text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-full">{{ $product->category->icon }} {{ $product->category->name }}</span>
                                            <span class="text-xs text-gray-400">by {{ $product->user->name }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 shrink-0 border-t sm:border-t-0 sm:border-l border-gray-100 pt-4 sm:pt-0 sm:pl-6">
                                    <span class="text-sm font-semibold text-gray-500">{{ number_format($product->vote_count) }} votes</span>
                                    <livewire:vote-button :product-id="$product->id" :vote-count="$product->vote_count" :has-voted="auth()->check() && $product->hasVotedBy(auth()->id())" :key="'feat-'.$product->id" />
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 bg-gray-50 rounded-2xl border border-gray-100">
                    <p class="text-gray-500">No featured launches today. Check back later or browse all launches below.</p>
                </div>
            @endif
        </div>

        {{-- All today's launches (top 3 + others) --}}
        @if($top3->count() > 0 || $others->count() > 0)
            <div class="pt-8 border-t border-gray-100">
                <h2 class="text-lg font-bold text-gray-900 mb-1">All launches today</h2>
                <p class="text-sm text-gray-500 mb-6">Sorted by votes.</p>

                <div class="space-y-3">
                    @foreach($top3->concat($others) as $index => $product)
                        @php
                            $rank = $index + 1;
                            $isTop3 = $rank <= 3;
                            $medals = ['🥇', '🥈', '🥉'];
                        @endphp
                        <div class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-5 flex items-center gap-4 hover:shadow-md hover:border-gray-200 transition-all group">
                            <div class="w-8 text-center shrink-0">
                                @if($isTop3)
                                    <span class="text-xl">{{ $medals[$index] }}</span>
                                @else
                                    <span class="text-sm font-bold text-gray-300">#{{ $rank }}</span>
                                @endif
                            </div>
                            <img src="{{ $product->logo_url }}" alt="{{ $product->name }}" class="w-12 h-12 rounded-xl object-cover border border-gray-100 shrink-0" />
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('product.show', $product->slug) }}" class="font-bold text-gray-900 group-hover:text-amber-600 truncate block">{{ $product->name }}</a>
                                <p class="text-sm text-gray-500 truncate mt-0.5">{{ $product->tagline }}</p>
                            </div>
                            <span class="hidden sm:inline text-xs bg-gray-50 text-gray-500 px-2.5 py-1 rounded-full shrink-0">{{ $product->category->icon }} {{ $product->category->name }}</span>
                            <livewire:vote-button :product-id="$product->id" :vote-count="$product->vote_count" :has-voted="auth()->check() && $product->hasVotedBy(auth()->id())" :key="'list-'.$product->id" />
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($top3->count() === 0 && $featured->count() === 0 && $others->count() === 0)
            <div class="text-center py-20 bg-gradient-to-br from-gray-50 to-amber-50/30 rounded-3xl border border-gray-100">
                <div class="text-6xl mb-5">🚀</div>
                <p class="text-xl font-bold text-gray-700 mb-2">No launches today</p>
                <p class="text-gray-500 mb-8">Be the first to submit your product!</p>
                <a href="{{ route('submit') }}" class="inline-flex items-center gap-2 gradient-amber text-white font-bold px-8 py-3.5 rounded-xl shadow-lg shadow-amber-500/20 hover:-translate-y-0.5 transition-all">
                    Submit Your Product
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            </div>
        @endif
    </div>
</x-app-layout>
