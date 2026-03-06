<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\Product;

new class extends Component {
    use WithPagination;

    public string $search = '';
    public string $pricing = '';
    public string $sort = 'newest';
    public int $categoryId = 0;

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingPricing(): void { $this->resetPage(); }
    public function updatingSort(): void { $this->resetPage(); }

    public function with(): array
    {
        return [
            'products' => $this->loadProducts(),
        ];
    }

    public function loadProducts()
    {
        return Product::approved()
            ->directory()
            ->with(['user', 'category', 'badge'])
            ->when($this->search, fn($q) => $q->where(fn($q2) =>
                $q2->where('name', 'like', "%{$this->search}%")
                   ->orWhere('tagline', 'like', "%{$this->search}%")
            ))
            ->when($this->pricing, fn($q) => $q->where('pricing', $this->pricing))
            ->when($this->categoryId, fn($q) => $q->where('category_id', $this->categoryId))
            ->when($this->sort === 'newest', fn($q) => $q->latest())
            ->when($this->sort === 'votes', fn($q) => $q->orderBy('vote_count', 'desc'))
            ->orderBy('is_featured', 'desc')
            ->paginate(12);
    }
}

?>

<div>
    {{-- Search + Filters --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input
                    wire:model.live.debounce.300ms="search"
                    type="text"
                    placeholder="Search products..."
                    class="w-full rounded-xl border border-gray-200 pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent bg-gray-50/50"
                />
            </div>
            <select wire:model.live="pricing" class="rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent bg-gray-50/50 cursor-pointer">
                <option value="">All Pricing</option>
                <option value="free">Free</option>
                <option value="freemium">Freemium</option>
                <option value="paid">Paid</option>
            </select>
            <select wire:model.live="sort" class="rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent bg-gray-50/50 cursor-pointer">
                <option value="newest">Newest First</option>
                <option value="votes">Most Voted</option>
            </select>
        </div>
    </div>

    {{-- Results count --}}
    <div class="flex items-center justify-between mb-5">
        <p class="text-sm text-gray-400"><span class="font-semibold text-gray-600">{{ $products->total() }}</span> products found</p>
        <div wire:loading class="text-xs text-amber-500 flex items-center gap-1">
            <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
            Loading...
        </div>
    </div>

    {{-- Product grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($products as $product)
            <div class="relative bg-white rounded-2xl border border-gray-100 p-5 card-hover group">
                @if($product->is_featured)
                    <span class="absolute top-4 right-4 inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider bg-gradient-to-r from-amber-400 to-orange-400 text-white px-2.5 py-0.5 rounded-full shadow-sm">
                        <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        Featured
                    </span>
                @endif
                <div class="flex items-start gap-3.5">
                    <img src="{{ $product->logo_url }}" alt="{{ $product->name }}" class="w-12 h-12 rounded-xl object-cover border border-gray-100 shadow-sm" />
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-gray-900 group-hover:text-amber-600 truncate transition-colors">{{ $product->name }}</h3>
                        <p class="text-sm text-gray-500 line-clamp-2 mt-1 leading-relaxed">{{ $product->tagline }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-50">
                    <div class="flex gap-2 flex-wrap">
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
        @empty
            <div class="col-span-full text-center py-20 bg-gray-50 rounded-3xl border border-gray-100">
                <div class="text-5xl mb-4">🔍</div>
                <p class="text-lg font-semibold text-gray-700">No products found</p>
                <p class="text-sm text-gray-400 mt-1">Try a different search or filter</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $products->links() }}
    </div>
</div>
