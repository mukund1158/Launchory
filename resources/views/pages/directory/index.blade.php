<x-app-layout>
    {{-- Page header --}}
    <div class="relative overflow-hidden bg-gradient-to-br from-indigo-50 via-blue-50/50 to-white border-b border-gray-100">
        <div class="absolute top-0 right-0 w-96 h-96 bg-blue-200/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-indigo-200/20 rounded-full blur-3xl translate-y-1/2 -translate-x-1/3"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center text-2xl text-white shadow-lg shadow-blue-500/25">
                            @if(isset($category))
                                {{ $category->icon }}
                            @else
                                📂
                            @endif
                        </div>
                        <div>
                            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900">
                                @if(isset($category))
                                    {{ $category->name }}
                                @else
                                    Product Directory
                                @endif
                            </h1>
                        </div>
                    </div>
                    <p class="text-gray-500 mt-1 max-w-lg">
                        @if(isset($category))
                            Browse {{ $category->products_count ?? '' }} products in {{ $category->name }}
                        @else
                            Discover {{ \App\Models\Product::approved()->directory()->count() }} products from indie makers around the world
                        @endif
                    </p>
                </div>
                <a href="{{ route('submit') }}" class="inline-flex items-center gap-2 gradient-amber text-white font-semibold text-sm px-5 py-2.5 rounded-xl shadow-md shadow-amber-500/20 hover:shadow-lg hover:-translate-y-0.5 transition-all shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Your Product
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
        <div class="flex flex-col lg:flex-row gap-8">
            {{-- Sidebar --}}
            <aside class="lg:w-72 shrink-0">
                <div class="lg:sticky lg:top-24">
                    {{-- Categories card --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                                Categories
                            </h3>
                        </div>
                        <nav class="p-2 max-h-[calc(100vh-12rem)] overflow-y-auto space-y-0.5">
                            <a href="{{ route('directory.index') }}"
                               class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                                      {{ !isset($category) ? 'bg-gradient-to-r from-amber-50 to-orange-50 text-amber-700 shadow-sm border border-amber-100/60' : 'text-gray-600 hover:bg-gray-50' }}">
                                <span class="flex items-center gap-2.5">
                                    <span class="w-8 h-8 rounded-lg {{ !isset($category) ? 'bg-amber-100' : 'bg-gray-100' }} flex items-center justify-center text-sm transition-colors">🌐</span>
                                    All Categories
                                </span>
                                <span class="text-xs {{ !isset($category) ? 'bg-amber-200/60 text-amber-800' : 'bg-gray-100 text-gray-400' }} px-2 py-0.5 rounded-full font-bold tabular-nums">{{ $categories->sum('products_count') }}</span>
                            </a>
                            @foreach($categories as $cat)
                                @php $isActive = isset($category) && $category->id === $cat->id; @endphp
                                <a href="{{ route('directory.category', $cat->slug) }}"
                                   class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                                          {{ $isActive ? 'bg-gradient-to-r from-amber-50 to-orange-50 text-amber-700 shadow-sm border border-amber-100/60' : 'text-gray-600 hover:bg-gray-50' }}">
                                    <span class="flex items-center gap-2.5">
                                        <span class="w-8 h-8 rounded-lg {{ $isActive ? 'bg-amber-100' : 'bg-gray-50' }} flex items-center justify-center text-sm transition-colors">{{ $cat->icon }}</span>
                                        <span class="truncate">{{ $cat->name }}</span>
                                    </span>
                                    <span class="text-xs {{ $isActive ? 'bg-amber-200/60 text-amber-800' : 'bg-gray-100 text-gray-400' }} px-2 py-0.5 rounded-full font-bold tabular-nums">{{ $cat->products_count }}</span>
                                </a>
                            @endforeach
                        </nav>
                    </div>

                    {{-- Submit CTA --}}
                    <div class="mt-4 bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-5 text-white relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-amber-500/10 rounded-full blur-2xl"></div>
                        <div class="relative">
                            <p class="font-bold text-sm mb-1">Got a product?</p>
                            <p class="text-xs text-gray-400 leading-relaxed mb-3">List your product in our directory and get discovered by thousands.</p>
                            <a href="{{ route('submit') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-amber-400 hover:text-amber-300 transition-colors">
                                Submit now
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </aside>

            {{-- Main content --}}
            <div class="flex-1 min-w-0">
                <livewire:directory-search :category-id="isset($category) ? $category->id : 0" />
            </div>
        </div>
    </div>
</x-app-layout>
