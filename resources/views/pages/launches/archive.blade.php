<x-app-layout>
    {{-- Compact header (matches launches index) --}}
    <section class="border-b border-gray-100 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Launch Archive</h1>
                    <p class="text-sm text-gray-500 mt-1">Past launch winners and their badges.</p>
                </div>
                <a href="{{ route('launches.index') }}" class="text-sm font-semibold text-amber-600 hover:text-amber-700">← Today's launches</a>
            </div>
        </div>
    </section>

    <section class="py-10 sm:py-12 bg-gray-50/50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($winners->count() > 0)
                <div class="space-y-3">
                    @foreach($winners as $product)
                        <a href="{{ route('product.show', $product->slug) }}" class="group flex items-center gap-4 p-4 rounded-2xl border border-gray-100 bg-white hover:border-amber-200 hover:shadow-md transition-all">
                            <span class="text-2xl shrink-0">{{ $product->badge->badge_emoji }}</span>
                            <img src="{{ $product->logo_url }}" alt="{{ $product->name }}" class="w-12 h-12 rounded-xl object-cover border border-gray-100 shadow-sm shrink-0" />
                            <div class="flex-1 min-w-0">
                                <span class="font-semibold text-gray-900 group-hover:text-amber-600 truncate block">{{ $product->name }}</span>
                                <p class="text-sm text-gray-500 truncate">{{ $product->tagline }}</p>
                            </div>
                            <span class="hidden sm:inline text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-full shrink-0">{{ $product->category->icon }} {{ $product->category->name }}</span>
                            <div class="text-right shrink-0">
                                <span class="text-xs font-semibold text-gray-700 block">{{ ucfirst($product->badge->rank) }}</span>
                                <span class="text-xs text-gray-400">{{ $product->badge->launch_date->format('M d, Y') }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $winners->links() }}
                </div>
            @else
                <div class="text-center py-20 bg-white rounded-3xl border border-gray-100">
                    <p class="text-6xl mb-4">🏆</p>
                    <p class="text-xl font-bold text-gray-800 mb-2">No winners yet</p>
                    <p class="text-sm text-gray-500">Launch your product and be the first winner!</p>
                </div>
            @endif
        </div>
    </section>
</x-app-layout>
