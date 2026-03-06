<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-extrabold">🏆 Launch Archive</h1>
        <p class="text-gray-500 mt-2 mb-8">Past launch winners and their badges.</p>

        @if($winners->count() > 0)
            <div class="space-y-3">
                @foreach($winners as $product)
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex items-center gap-4">
                        <span class="text-xl shrink-0">{{ $product->badge->badge_emoji }}</span>
                        <img src="{{ $product->logo_url }}" alt="{{ $product->name }}" class="w-10 h-10 rounded-lg object-cover border border-gray-100 shrink-0" />
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('product.show', $product->slug) }}" class="font-semibold text-gray-900 hover:text-amber-500 truncate block">{{ $product->name }}</a>
                            <p class="text-sm text-gray-500 truncate">{{ $product->tagline }}</p>
                        </div>
                        <span class="hidden sm:inline text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full shrink-0">{{ $product->category->icon }} {{ $product->category->name }}</span>
                        <div class="text-right shrink-0">
                            <span class="text-xs font-medium text-gray-700 block">{{ ucfirst($product->badge->rank) }}</span>
                            <span class="text-xs text-gray-400">{{ $product->badge->launch_date->format('M d, Y') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $winners->links() }}
            </div>
        @else
            <div class="text-center py-20 text-gray-400">
                <p class="text-5xl mb-4">🏆</p>
                <p class="text-lg font-medium">No winners yet</p>
                <p class="text-sm mt-1">Launch your product and be the first winner!</p>
            </div>
        @endif
    </div>
</x-app-layout>
