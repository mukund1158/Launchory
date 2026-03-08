<?php

use Livewire\Volt\Component;
use App\Models\Product;
use App\Models\Vote;

new class extends Component {
    public function with(): array
    {
        $all = Product::approved()
            ->launches()
            ->today()
            ->byVotes()
            ->with(['user', 'category'])
            ->get();

        $top3 = $all->take(3)->values();
        $top3Ids = $top3->pluck('id')->all();
        $featured = $all->where('is_featured', true)->whereNotIn('id', $top3Ids)->values();
        $featuredIds = $featured->pluck('id')->all();
        $others = $all->whereNotIn('id', array_merge($top3Ids, $featuredIds))->values();

        return [
            'top3' => $top3,
            'featured' => $featured,
            'others' => $others,
        ];
    }

    public function vote(int $productId): void
    {
        if (!auth()->check()) {
            $this->redirect(route('login'));
            return;
        }

        $product = Product::findOrFail($productId);
        $userId = auth()->id();
        $existing = Vote::where('user_id', $userId)->where('product_id', $productId)->first();

        if ($existing) {
            $existing->delete();
            $product->decrement('vote_count');
        } else {
            Vote::create(['user_id' => $userId, 'product_id' => $productId]);
            $product->increment('vote_count');
        }
    }
}

?>

@php
    $rankColors = [
        0 => ['border' => 'border-amber-200', 'bg' => 'bg-gradient-to-r from-amber-50/80 to-yellow-50/50', 'emoji' => '🥇'],
        1 => ['border' => 'border-gray-200', 'bg' => 'bg-gradient-to-r from-gray-50/80 to-slate-50/50', 'emoji' => '🥈'],
        2 => ['border' => 'border-orange-200', 'bg' => 'bg-gradient-to-r from-orange-50/80 to-amber-50/50', 'emoji' => '🥉'],
    ];
    $hasAny = $top3->count() > 0 || $featured->count() > 0 || $others->count() > 0;
@endphp
<div wire:poll.30s>
    @if($hasAny)
        <div class="space-y-8">
            {{-- Section 1: Top 3 --}}
            @if($top3->count() > 0)
                <div>
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3">Today's top 3</h3>
                    <div class="space-y-3">
                        @foreach($top3 as $index => $product)
                            @include('livewire.partials.launch-card', ['product' => $product, 'rank' => $index, 'rankColors' => $rankColors, 'showMedal' => true])
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Section 2: Featured --}}
            @if($featured->count() > 0)
                <div>
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3">Featured</h3>
                    <div class="space-y-3">
                        @foreach($featured as $index => $product)
                            @include('livewire.partials.launch-card', ['product' => $product, 'rank' => null, 'rankColors' => [], 'showMedal' => false])
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Section 3: Others --}}
            @if($others->count() > 0)
                <div>
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3">More launches</h3>
                    <div class="space-y-3">
                        @foreach($others as $index => $product)
                            @include('livewire.partials.launch-card', ['product' => $product, 'rank' => $index + 4, 'rankColors' => [], 'showMedal' => false])
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @else
        <div class="text-center py-16 bg-gradient-to-br from-gray-50 to-amber-50/30 rounded-3xl border border-gray-100">
            <div class="text-6xl mb-4">🚀</div>
            <p class="text-xl font-bold text-gray-800 mb-2">No launches yet today</p>
            <p class="text-sm text-gray-400 mb-6 max-w-sm mx-auto">Be the first to launch your product and get discovered by the community!</p>
            <a href="{{ route('submit') }}" class="inline-flex items-center gap-2 gradient-amber text-white font-bold px-7 py-3.5 rounded-xl shadow-lg shadow-amber-500/20 hover:-translate-y-0.5 transition-all">
                Submit Your Product
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>
    @endif
</div>
