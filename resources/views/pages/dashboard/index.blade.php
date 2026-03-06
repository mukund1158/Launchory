<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-2xl font-extrabold">Welcome back, {{ auth()->user()->name }}! 👋</h1>
        <p class="text-gray-500 text-sm mt-1">Here's an overview of your products on Launchory.</p>

        {{-- Quick stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-8">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <p class="text-sm text-gray-500">My Products</p>
                <p class="text-3xl font-bold mt-1">{{ $productCount }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <p class="text-sm text-gray-500">Total Votes Received</p>
                <p class="text-3xl font-bold mt-1">{{ $totalVotes }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <p class="text-sm text-gray-500">Pending Review</p>
                <p class="text-3xl font-bold mt-1 {{ $pendingCount > 0 ? 'text-amber-500' : '' }}">{{ $pendingCount }}</p>
            </div>
        </div>

        {{-- Quick action --}}
        <div class="mt-8">
            <a href="{{ route('submit') }}" class="inline-block bg-amber-400 hover:bg-amber-500 text-white font-semibold px-6 py-2.5 rounded-xl transition-colors">
                + Submit New Product
            </a>
        </div>

        {{-- Recent products --}}
        <div class="mt-10">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold">Recent Products</h2>
                @if($productCount > 5)
                    <a href="{{ route('dashboard.products') }}" class="text-sm font-medium text-amber-600 hover:text-amber-700">View All →</a>
                @endif
            </div>

            @if($recentProducts->count() > 0)
                <div class="space-y-3">
                    @foreach($recentProducts as $product)
                        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex items-center gap-4">
                            <img src="{{ $product->logo_url }}" alt="{{ $product->name }}" class="w-10 h-10 rounded-lg object-cover border border-gray-100 shrink-0" />
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 truncate">{{ $product->name }}</p>
                                <p class="text-sm text-gray-500 truncate">{{ $product->tagline }}</p>
                            </div>
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full shrink-0
                                {{ $product->status === 'approved' ? 'bg-green-100 text-green-700' : ($product->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                {{ ucfirst($product->status) }}
                            </span>
                            <span class="text-sm font-bold text-gray-400 shrink-0">▲ {{ $product->vote_count }}</span>
                            <a href="{{ route('product.show', $product->slug) }}" class="text-sm font-medium text-amber-600 hover:text-amber-700 shrink-0">View</a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 text-gray-400 bg-white rounded-2xl border border-gray-100">
                    <p class="text-4xl mb-3">📦</p>
                    <p class="font-medium">No products yet</p>
                    <p class="text-sm mt-1">Submit your first product to get started!</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
