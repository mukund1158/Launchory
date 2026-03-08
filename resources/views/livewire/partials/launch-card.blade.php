@php
    $hasVoted = auth()->check() && $product->hasVotedBy(auth()->id());
    $isTop3 = $showMedal && $rank !== null && isset($rankColors[$rank]);
    $cardBorder = $isTop3 ? ($rankColors[$rank]['border'] . ' ' . $rankColors[$rank]['bg']) : 'border-gray-100 bg-white hover:border-gray-200';
@endphp
<div class="group relative rounded-2xl border transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5 {{ $cardBorder }}">
    <div class="flex items-center gap-4 p-4 sm:p-5">
        {{-- Rank --}}
        <div class="shrink-0 w-8 text-center">
            @if($showMedal && isset($rankColors[$rank]))
                <span class="text-xl">{{ $rankColors[$rank]['emoji'] }}</span>
            @elseif($rank !== null)
                <span class="text-sm font-bold text-gray-300">#{{ $rank }}</span>
            @else
                <span class="text-amber-500"><svg class="w-5 h-5 inline" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg></span>
            @endif
        </div>

        {{-- Logo --}}
        <img src="{{ $product->logo_url }}" alt="{{ $product->name }}"
             class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl object-cover border-2 border-white shadow-md shrink-0" />

        {{-- Info --}}
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('product.show', $product->slug) }}" class="font-bold text-gray-900 hover:text-amber-600 transition-colors text-base sm:text-lg truncate">
                    {{ $product->name }}
                </a>
                @if($product->is_featured && !$showMedal)
                    <span class="inline-flex items-center gap-0.5 text-[10px] font-bold uppercase tracking-wider bg-gradient-to-r from-amber-400 to-orange-400 text-white px-2 py-0.5 rounded-full">
                        <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        Featured
                    </span>
                @endif
            </div>
            <p class="text-sm text-gray-500 truncate mt-0.5">{{ $product->tagline }}</p>
            <div class="flex items-center gap-3 mt-2">
                <span class="text-xs bg-white/80 text-gray-500 px-2.5 py-1 rounded-full border border-gray-100 shadow-sm">{{ $product->category->icon }} {{ $product->category->name }}</span>
                <span class="text-xs text-gray-400 hidden sm:inline">by {{ $product->user->name }}</span>
            </div>
        </div>

        {{-- Vote button --}}
        <button
            wire:click="vote({{ $product->id }})"
            wire:loading.attr="disabled"
            class="shrink-0 flex flex-col items-center gap-1 px-4 py-3 rounded-xl border-2 transition-all duration-200 cursor-pointer min-w-[4rem]
                   {{ $hasVoted
                      ? 'bg-amber-400 border-amber-400 text-white shadow-lg shadow-amber-400/30 scale-105'
                      : 'bg-white border-gray-200 text-gray-600 hover:border-amber-400 hover:text-amber-500 hover:shadow-md' }}"
        >
            <svg class="w-5 h-5" fill="{{ $hasVoted ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
            </svg>
            <span class="text-sm font-bold">{{ $product->vote_count }}</span>
        </button>
    </div>
</div>
