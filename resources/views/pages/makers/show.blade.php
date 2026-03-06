<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        {{-- Profile header --}}
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5 mb-10">
            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-20 h-20 rounded-full object-cover border-2 border-gray-200 shrink-0" />
            <div class="text-center sm:text-left">
                <h1 class="text-2xl font-extrabold">{{ $user->name }}</h1>
                @if($user->username)
                    <p class="text-gray-400 text-sm">@{{ $user->username }}</p>
                @endif
                @if($user->bio)
                    <p class="text-gray-600 text-sm mt-2 max-w-lg">{{ $user->bio }}</p>
                @endif
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3 mt-3">
                    @if($user->twitter_handle)
                        <a href="https://x.com/{{ ltrim($user->twitter_handle, '@') }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-xs font-medium text-gray-500 hover:text-gray-700 bg-gray-50 px-3 py-1.5 rounded-full">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            {{ $user->twitter_handle }}
                        </a>
                    @endif
                    @if($user->website_url)
                        <a href="{{ $user->website_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-xs font-medium text-amber-600 hover:text-amber-700 bg-amber-50 px-3 py-1.5 rounded-full">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            Website
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Stats --}}
        <div class="flex gap-6 mb-8 pb-8 border-b border-gray-100">
            <div class="text-center">
                <p class="text-2xl font-bold">{{ $products->count() }}</p>
                <p class="text-xs text-gray-500">Products</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold">{{ $totalVotes }}</p>
                <p class="text-xs text-gray-500">Total Votes</p>
            </div>
        </div>

        {{-- Products --}}
        @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($products as $product)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 p-4">
                        <div class="flex items-start gap-3">
                            <img src="{{ $product->logo_url }}" alt="{{ $product->name }}" class="w-12 h-12 rounded-xl object-cover border border-gray-100" />
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 truncate">{{ $product->name }}</h3>
                                <p class="text-sm text-gray-500 line-clamp-2 mt-0.5">{{ $product->tagline }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between mt-4">
                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">{{ $product->category->icon }} {{ $product->category->name }}</span>
                            <a href="{{ route('product.show', $product->slug) }}" class="text-sm font-medium text-amber-600 hover:text-amber-700">View →</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16 text-gray-400">
                <p class="text-4xl mb-3">📦</p>
                <p class="font-medium">No products yet</p>
            </div>
        @endif
    </div>
</x-app-layout>
