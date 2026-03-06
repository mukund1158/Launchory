<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-extrabold">My Products</h1>
                <p class="text-gray-500 text-sm mt-1">Manage all your submitted products.</p>
            </div>
            <a href="{{ route('submit') }}" class="bg-amber-400 hover:bg-amber-500 text-white font-semibold px-6 py-2.5 rounded-xl transition-colors">
                + Submit New Product
            </a>
        </div>

        @if($products->count() > 0)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">
                            <tr>
                                <th class="px-5 py-3">Product</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3">Listing</th>
                                <th class="px-5 py-3">Votes</th>
                                <th class="px-5 py-3">Submitted</th>
                                <th class="px-5 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($products as $product)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $product->logo_url }}" alt="{{ $product->name }}" class="w-9 h-9 rounded-lg object-cover border border-gray-100 shrink-0" />
                                            <div class="min-w-0">
                                                <p class="font-semibold text-gray-900 truncate">{{ $product->name }}</p>
                                                <p class="text-xs text-gray-400 truncate">{{ $product->category->icon }} {{ $product->category->name }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="text-xs font-medium px-2.5 py-1 rounded-full
                                            {{ $product->status === 'approved' ? 'bg-green-100 text-green-700' : ($product->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                            {{ ucfirst($product->status) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-gray-600">{{ ucfirst(str_replace('_', ' + ', $product->listing_type)) }}</td>
                                    <td class="px-5 py-4 font-bold text-gray-700">▲ {{ $product->vote_count }}</td>
                                    <td class="px-5 py-4 text-gray-400">{{ $product->created_at->format('M d, Y') }}</td>
                                    <td class="px-5 py-4">
                                        <a href="{{ route('product.show', $product->slug) }}" class="text-sm font-medium text-amber-600 hover:text-amber-700">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="text-center py-16 text-gray-400 bg-white rounded-2xl border border-gray-100">
                <p class="text-4xl mb-3">📦</p>
                <p class="font-medium">No products yet</p>
                <p class="text-sm mt-1">Submit your first product to get started!</p>
                <a href="{{ route('submit') }}" class="inline-block mt-4 bg-amber-400 hover:bg-amber-500 text-white font-semibold px-6 py-2.5 rounded-xl transition-colors">Submit Product</a>
            </div>
        @endif
    </div>
</x-app-layout>
