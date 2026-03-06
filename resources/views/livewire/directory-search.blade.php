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
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 sm:p-5 mb-6">
        <div class="flex flex-col gap-3">
            {{-- Search bar --}}
            <div class="relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input
                    wire:model.live.debounce.300ms="search"
                    type="text"
                    placeholder="Search products by name or tagline..."
                    class="w-full rounded-xl border border-gray-200 pl-12 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent bg-gray-50/50 placeholder-gray-400"
                />
                @if($search)
                    <button wire:click="$set('search', '')" class="absolute right-3 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center transition-colors">
                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                @endif
            </div>

            {{-- Filter row --}}
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-xs text-gray-400 font-medium mr-1">Filters:</span>

                {{-- Pricing pills --}}
                <div class="flex items-center bg-gray-50 rounded-xl p-1 border border-gray-100">
                    <button wire:click="$set('pricing', '')"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ $pricing === '' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        All
                    </button>
                    <button wire:click="$set('pricing', 'free')"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ $pricing === 'free' ? 'bg-white text-emerald-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        Free
                    </button>
                    <button wire:click="$set('pricing', 'freemium')"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ $pricing === 'freemium' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        Freemium
                    </button>
                    <button wire:click="$set('pricing', 'paid')"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ $pricing === 'paid' ? 'bg-white text-purple-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        Paid
                    </button>
                </div>

                {{-- Sort --}}
                <div class="flex items-center bg-gray-50 rounded-xl p-1 border border-gray-100 ml-auto">
                    <button wire:click="$set('sort', 'newest')"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all flex items-center gap-1 {{ $sort === 'newest' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Newest
                    </button>
                    <button wire:click="$set('sort', 'votes')"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all flex items-center gap-1 {{ $sort === 'votes' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                        Top Voted
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Results bar --}}
    <div class="flex items-center justify-between mb-5">
        <p class="text-sm text-gray-400">
            <span class="font-semibold text-gray-700">{{ $products->total() }}</span>
            {{ Str::plural('product', $products->total()) }} found
            @if($search)
                <span class="text-gray-400">for "<span class="text-gray-600">{{ $search }}</span>"</span>
            @endif
        </p>
        <div wire:loading class="text-xs text-amber-500 flex items-center gap-1.5 bg-amber-50 px-3 py-1.5 rounded-full">
            <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
            Loading...
        </div>
    </div>

    {{-- Product grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" wire:loading.class="opacity-50">
        @forelse($products as $product)
            <a href="{{ route('product.show', $product->slug) }}" class="group relative bg-white rounded-2xl border border-gray-100 hover:border-gray-200 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 overflow-hidden">
                {{-- Featured ribbon --}}
                @if($product->is_featured)
                    <div class="absolute top-0 right-0 z-10">
                        <div class="bg-gradient-to-r from-amber-400 to-orange-400 text-white text-[9px] font-bold uppercase tracking-wider px-3 py-1 rounded-bl-xl shadow-sm flex items-center gap-1">
                            <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            Featured
                        </div>
                    </div>
                @endif

                <div class="p-5">
                    {{-- Top: Logo + Info --}}
                    <div class="flex items-start gap-4">
                        <img src="{{ $product->logo_url }}" alt="{{ $product->name }}" class="w-14 h-14 rounded-2xl object-cover border border-gray-100 shadow-sm shrink-0 group-hover:shadow-md transition-shadow" />
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-gray-900 group-hover:text-amber-600 truncate transition-colors text-base">{{ $product->name }}</h3>
                            <p class="text-sm text-gray-500 line-clamp-2 mt-1 leading-relaxed">{{ $product->tagline }}</p>
                        </div>
                    </div>

                    {{-- Bottom: Meta --}}
                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-50">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-xs bg-gray-50 text-gray-500 px-2.5 py-1 rounded-lg border border-gray-100 flex items-center gap-1">
                                {{ $product->category->icon }}
                                <span class="hidden sm:inline">{{ $product->category->name }}</span>
                            </span>
                            <span class="text-xs px-2.5 py-1 rounded-lg font-medium
                                {{ $product->pricing === 'free' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : ($product->pricing === 'freemium' ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'bg-purple-50 text-purple-600 border border-purple-100') }}">
                                {{ ucfirst($product->pricing) }}
                            </span>
                        </div>
                        <div class="flex items-center gap-3">
                            @if($product->vote_count > 0)
                                <span class="flex items-center gap-1 text-xs text-gray-400">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                    {{ $product->vote_count }}
                                </span>
                            @endif
                            <span class="text-sm font-semibold text-amber-600 group-hover:text-amber-700 flex items-center gap-1 transition-colors">
                                View <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full text-center py-20 bg-gradient-to-br from-gray-50 to-blue-50/30 rounded-3xl border border-gray-100">
                <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center text-3xl mx-auto mb-4">🔍</div>
                <p class="text-lg font-bold text-gray-800">No products found</p>
                <p class="text-sm text-gray-400 mt-1 mb-5">Try adjusting your search or filters</p>
                @if($search || $pricing)
                    <button wire:click="$set('search', ''); $set('pricing', '')" class="text-sm font-semibold text-amber-600 hover:text-amber-700 bg-amber-50 hover:bg-amber-100 px-4 py-2 rounded-xl transition-all">
                        Clear all filters
                    </button>
                @endif
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $products->links() }}
    </div>
</div>
