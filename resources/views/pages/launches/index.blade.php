<x-app-layout>
    {{-- Page header --}}
    <div class="bg-gradient-to-b from-orange-50 to-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <span class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl text-2xl shadow-lg shadow-amber-500/20">🚀</span>
                        <h1 class="text-3xl sm:text-4xl font-extrabold">Today's Launches</h1>
                    </div>
                    <p class="text-gray-500">Vote for the products you love. Top 3 win a badge and dofollow backlink.</p>
                </div>

                @if($launchPeriod)
                    <div x-data="countdown('{{ $launchPeriod->ends_at->toIso8601String() }}')" class="flex items-center gap-2">
                        <span class="text-xs text-gray-400 mr-1">Ends in</span>
                        @foreach(['days' => 'D', 'hours' => 'H', 'minutes' => 'M', 'seconds' => 'S'] as $unit => $label)
                            <div class="bg-gray-900 rounded-xl px-3 py-2 text-center min-w-[3.2rem]">
                                <span class="text-lg font-bold text-white font-mono" x-text="{{ $unit }}">00</span>
                                <span class="text-[9px] text-gray-400 block tracking-widest">{{ $label }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        @if($products->count() > 0)
            {{-- Top 3 podium --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-10">
                @foreach($products->take(3) as $index => $product)
                    @php
                        $styles = [
                            ['bg' => 'bg-gradient-to-br from-amber-50 via-yellow-50 to-orange-50', 'border' => 'border-amber-300 ring-1 ring-amber-200/50', 'emoji' => '🥇', 'rank' => '1st Place', 'shadow' => 'shadow-amber-100'],
                            ['bg' => 'bg-gradient-to-br from-gray-50 via-slate-50 to-gray-100', 'border' => 'border-gray-200', 'emoji' => '🥈', 'rank' => '2nd Place', 'shadow' => ''],
                            ['bg' => 'bg-gradient-to-br from-orange-50 via-amber-50 to-yellow-50', 'border' => 'border-amber-600/30', 'emoji' => '🥉', 'rank' => '3rd Place', 'shadow' => ''],
                        ][$index];
                    @endphp
                    <div class="{{ $styles['bg'] }} rounded-2xl border {{ $styles['border'] }} p-6 card-hover {{ $styles['shadow'] }}">
                        <div class="flex items-center justify-between mb-5">
                            <span class="text-4xl">{{ $styles['emoji'] }}</span>
                            <span class="text-xs font-bold uppercase tracking-wider text-gray-400">{{ $styles['rank'] }}</span>
                        </div>
                        <div class="flex items-center gap-3 mb-3">
                            <img src="{{ $product->logo_url }}" alt="{{ $product->name }}" class="w-14 h-14 rounded-2xl object-cover border-2 border-white shadow-md" />
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('product.show', $product->slug) }}" class="font-bold text-lg text-gray-900 hover:text-amber-600 transition-colors truncate block">{{ $product->name }}</a>
                                <a href="{{ route('makers.show', $product->user->username ?? $product->user->id) }}" class="text-xs text-gray-400 hover:text-gray-600 transition-colors">by {{ $product->user->name }}</a>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 line-clamp-2 mb-5">{{ $product->tagline }}</p>
                        <div class="flex items-center justify-between pt-4 border-t border-white/60">
                            <span class="text-xs bg-white/80 text-gray-600 px-2.5 py-1 rounded-full border border-gray-200/60">{{ $product->category->icon }} {{ $product->category->name }}</span>
                            <livewire:vote-button :product-id="$product->id" :vote-count="$product->vote_count" :has-voted="auth()->check() && $product->hasVotedBy(auth()->id())" :key="'launch-top-'.$product->id" />
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Leaderboard list --}}
            @if($products->count() > 3)
                <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Other Launches</h3>
                <div class="space-y-3">
                    @foreach($products->slice(3) as $index => $product)
                        @php $rank = $index + 1; @endphp
                        <div class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-5 flex items-center gap-4 card-hover group">
                            <span class="text-base font-bold text-gray-300 w-8 text-center shrink-0">{{ $rank }}</span>
                            <img src="{{ $product->logo_url }}" alt="{{ $product->name }}" class="w-12 h-12 rounded-xl object-cover border border-gray-100 shadow-sm shrink-0" />
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('product.show', $product->slug) }}" class="font-bold text-gray-900 group-hover:text-amber-500 truncate block transition-colors">{{ $product->name }}</a>
                                <p class="text-sm text-gray-400 truncate mt-0.5">{{ $product->tagline }}</p>
                            </div>
                            <a href="{{ route('makers.show', $product->user->username ?? $product->user->id) }}" class="hidden lg:flex items-center gap-2 shrink-0">
                                <img src="{{ $product->user->avatar_url }}" class="w-6 h-6 rounded-full border border-gray-200" />
                                <span class="text-xs text-gray-400">{{ $product->user->name }}</span>
                            </a>
                            <span class="hidden sm:inline text-xs bg-gray-50 text-gray-500 px-2.5 py-1 rounded-full border border-gray-100 shrink-0">{{ $product->category->icon }} {{ $product->category->name }}</span>
                            <livewire:vote-button :product-id="$product->id" :vote-count="$product->vote_count" :has-voted="auth()->check() && $product->hasVotedBy(auth()->id())" :key="'launch-list-'.$product->id" />
                        </div>
                    @endforeach
                </div>
            @endif
        @else
            <div class="text-center py-20 bg-gray-50 rounded-3xl border border-gray-100">
                <div class="text-6xl mb-5">🚀</div>
                <p class="text-xl font-bold text-gray-700 mb-2">No launches today</p>
                <p class="text-gray-400 mb-8">Be the first to submit your product!</p>
                <a href="{{ route('submit') }}" class="inline-flex gradient-amber text-white font-bold px-8 py-3.5 rounded-xl shadow-lg shadow-amber-500/20 hover:-translate-y-0.5 transition-all">Submit Your Product</a>
            </div>
        @endif
    </div>

    <script>
        function countdown(endDate) {
            return {
                days: '00', hours: '00', minutes: '00', seconds: '00',
                init() { this.update(); setInterval(() => this.update(), 1000); },
                update() {
                    const diff = Math.max(0, new Date(endDate) - new Date());
                    this.days = String(Math.floor(diff / 86400000)).padStart(2, '0');
                    this.hours = String(Math.floor((diff % 86400000) / 3600000)).padStart(2, '0');
                    this.minutes = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
                    this.seconds = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');
                }
            }
        }
    </script>
</x-app-layout>
