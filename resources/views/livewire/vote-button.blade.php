<?php

use Livewire\Volt\Component;
use App\Models\Vote;
use App\Models\Product;

new class extends Component {
    public int $productId;
    public int $voteCount;
    public bool $hasVoted = false;

    public function mount(int $productId, int $voteCount, bool $hasVoted): void
    {
        $this->productId = $productId;
        $this->voteCount = $voteCount;
        $this->hasVoted = $hasVoted;
    }

    public function toggle(): void
    {
        if (!auth()->check()) {
            $this->redirect(route('login'));
            return;
        }

        $product = Product::findOrFail($this->productId);
        $userId = auth()->id();
        $existing = Vote::where('user_id', $userId)->where('product_id', $this->productId)->first();

        if ($existing) {
            $existing->delete();
            $product->decrement('vote_count');
            $this->voteCount--;
            $this->hasVoted = false;
        } else {
            Vote::create(['user_id' => $userId, 'product_id' => $this->productId]);
            $product->increment('vote_count');
            $this->voteCount++;
            $this->hasVoted = true;
        }
    }
}

?>

<div>
    <button
        wire:click="toggle"
        wire:loading.attr="disabled"
        class="flex flex-col items-center gap-1 px-4 py-2 rounded-xl border-2 transition-all duration-200 cursor-pointer
               {{ $hasVoted
                  ? 'bg-amber-400 border-amber-400 text-white'
                  : 'bg-white border-gray-200 text-gray-600 hover:border-amber-400 hover:text-amber-500' }}"
    >
        <svg class="w-5 h-5" fill="{{ $hasVoted ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
        </svg>
        <span class="text-sm font-bold">{{ $voteCount }}</span>
    </button>
</div>
