<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        {{-- Top section --}}
        <div class="flex flex-col sm:flex-row items-start gap-6">
            <img src="{{ $product->logo_url }}" alt="{{ $product->name }}" class="w-20 h-20 rounded-2xl object-cover border border-gray-100 shadow-sm shrink-0" />
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl sm:text-3xl font-extrabold truncate">{{ $product->name }}</h1>
                    @if($product->badge)
                        <span class="text-2xl">{{ $product->badge->badge_emoji }}</span>
                    @endif
                </div>
                <p class="text-gray-500 mt-1">{{ $product->tagline }}</p>
                <div class="flex flex-wrap items-center gap-3 mt-4">
                    <span class="text-xs bg-gray-100 text-gray-600 px-3 py-1.5 rounded-full">{{ $product->category->icon }} {{ $product->category->name }}</span>
                    <span class="text-xs px-3 py-1.5 rounded-full {{ $product->pricing === 'free' ? 'bg-green-100 text-green-700' : ($product->pricing === 'freemium' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700') }}">
                        {{ ucfirst($product->pricing) }}
                    </span>
                    <a href="{{ $product->website_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-xs font-medium text-amber-600 hover:text-amber-700 bg-amber-50 px-3 py-1.5 rounded-full">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        Visit Website
                    </a>
                    @if($product->twitter_handle)
                        <a href="https://x.com/{{ ltrim($product->twitter_handle, '@') }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-xs font-medium text-gray-500 hover:text-gray-700 bg-gray-50 px-3 py-1.5 rounded-full">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            {{ $product->twitter_handle }}
                        </a>
                    @endif
                </div>
            </div>
            <div class="shrink-0">
                <livewire:vote-button :product-id="$product->id" :vote-count="$product->vote_count" :has-voted="$hasVoted" />
            </div>
        </div>

        {{-- Main content --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-10">
            {{-- Left: Description --}}
            <div class="lg:col-span-2 space-y-8">
                <div>
                    <h2 class="text-lg font-bold mb-3">About</h2>
                    <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>

                @if($product->maker_comment)
                    <div class="bg-amber-50 rounded-2xl p-6">
                        <h3 class="text-sm font-bold text-amber-800 mb-2">💬 Maker's Comment</h3>
                        <p class="text-sm text-amber-900 leading-relaxed">{{ $product->maker_comment }}</p>
                    </div>
                @endif
            </div>

            {{-- Right: Sidebar --}}
            <div class="space-y-6">
                {{-- Maker card --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Maker</h3>
                    <div class="flex items-center gap-3">
                        <img src="{{ $product->user->avatar_url }}" alt="{{ $product->user->name }}" class="w-10 h-10 rounded-full object-cover border border-gray-200" />
                        <div>
                            <p class="font-semibold text-sm">{{ $product->user->name }}</p>
                            @if($product->user->username)
                                <a href="{{ route('makers.show', $product->user->username) }}" class="text-xs text-amber-600 hover:text-amber-700">@{{ $product->user->username }}</a>
                            @endif
                        </div>
                    </div>
                    @if($product->user->bio)
                        <p class="text-xs text-gray-500 mt-3 leading-relaxed">{{ $product->user->bio }}</p>
                    @endif
                    @if($product->user->username)
                        <a href="{{ route('makers.show', $product->user->username) }}" class="block text-center text-sm font-medium text-amber-600 hover:text-amber-700 mt-4 bg-amber-50 rounded-lg py-2">View Profile</a>
                    @endif
                </div>

                {{-- Product info --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Info</h3>
                    <dl class="space-y-2.5 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Submitted</dt>
                            <dd class="font-medium">{{ $product->created_at->format('M d, Y') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Listing</dt>
                            <dd class="font-medium">{{ ucfirst(str_replace('_', ' + ', $product->listing_type)) }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Category</dt>
                            <dd class="font-medium">{{ $product->category->name }}</dd>
                        </div>
                        @if($product->launch_date)
                            <div class="flex justify-between">
                                <dt class="text-gray-500">Launch Date</dt>
                                <dd class="font-medium">{{ $product->launch_date->format('M d, Y') }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>

                {{-- Share --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5" x-data="{ copied: false }">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Share</h3>
                    <div class="flex gap-2">
                        <a href="https://x.com/intent/tweet?text={{ urlencode($product->name . ' — ' . $product->tagline) }}&url={{ urlencode(route('product.show', $product->slug)) }}"
                           target="_blank" rel="noopener"
                           class="flex-1 text-center text-sm font-medium bg-gray-900 text-white py-2 rounded-lg hover:bg-gray-800 transition-colors">
                            Tweet
                        </a>
                        <button
                            @click="navigator.clipboard.writeText('{{ route('product.show', $product->slug) }}'); copied = true; setTimeout(() => copied = false, 2000)"
                            class="flex-1 text-center text-sm font-medium border border-gray-200 py-2 rounded-lg hover:bg-gray-50 transition-colors"
                            x-text="copied ? 'Copied!' : 'Copy Link'"
                        ></button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Badge section --}}
        @if($product->badge)
            <div class="mt-12 bg-gray-50 rounded-2xl p-6 sm:p-8">
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-3xl">{{ $product->badge->badge_emoji }}</span>
                    <div>
                        <h3 class="font-bold text-lg">{{ ucfirst($product->badge->rank) }} Launch Winner</h3>
                        <p class="text-sm text-gray-500">{{ $product->badge->launch_date->format('F d, Y') }}</p>
                    </div>
                </div>

                <div class="mt-4">
                    <p class="text-sm font-medium text-gray-700 mb-2">Embed this badge on your website:</p>
                    @php
                        $badgeCode = '<a href="' . route('product.show', $product->slug) . '" rel="dofollow">' . "\n" . '  <img src="' . route('badge.generate', $product->slug) . '"' . "\n" . '       alt="Featured on Launchory" width="200" />' . "\n" . '</a>';
                    @endphp
                    <div class="relative" x-data="{ badgeCopied: false, code: @js($badgeCode) }">
                        <pre class="bg-gray-900 text-gray-300 text-xs rounded-xl p-4 overflow-x-auto"><code>{{ $badgeCode }}</code></pre>
                        <button
                            @click="navigator.clipboard.writeText(code); badgeCopied = true; setTimeout(() => badgeCopied = false, 2000)"
                            class="absolute top-3 right-3 text-xs font-medium bg-gray-700 hover:bg-gray-600 text-gray-200 px-3 py-1.5 rounded-lg transition-colors"
                            x-text="badgeCopied ? 'Copied!' : 'Copy'"
                        ></button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
