<x-app-layout>
    {{-- Page header --}}
    <div class="bg-gradient-to-b from-blue-50/50 to-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
            <div class="flex items-center gap-3 mb-3">
                <span class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-2xl text-2xl shadow-lg shadow-blue-500/20">📁</span>
                <h1 class="text-3xl sm:text-4xl font-extrabold">
                    @if(isset($category))
                        {{ $category->icon }} {{ $category->name }}
                    @else
                        Product Directory
                    @endif
                </h1>
            </div>
            <p class="text-gray-500">Discover {{ \App\Models\Product::approved()->directory()->count() }} products from indie makers</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex flex-col lg:flex-row gap-8">
            {{-- Sidebar --}}
            <aside class="lg:w-64 shrink-0">
                <div class="lg:sticky lg:top-8">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-3 px-3">Categories</h3>
                    <nav class="space-y-0.5">
                        <a href="{{ route('directory.index') }}"
                           class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all
                                  {{ !isset($category) ? 'bg-amber-50 text-amber-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50' }}">
                            <span class="flex items-center gap-2">
                                <span class="w-5 text-center">🌐</span>
                                All Categories
                            </span>
                            <span class="text-xs {{ !isset($category) ? 'bg-amber-200/60 text-amber-800' : 'bg-gray-100 text-gray-400' }} px-2 py-0.5 rounded-full font-semibold">{{ $categories->sum('products_count') }}</span>
                        </a>
                        @foreach($categories as $cat)
                            <a href="{{ route('directory.category', $cat->slug) }}"
                               class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all
                                      {{ isset($category) && $category->id === $cat->id ? 'bg-amber-50 text-amber-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50' }}">
                                <span class="flex items-center gap-2">
                                    <span class="w-5 text-center">{{ $cat->icon }}</span>
                                    {{ $cat->name }}
                                </span>
                                <span class="text-xs {{ isset($category) && $category->id === $cat->id ? 'bg-amber-200/60 text-amber-800' : 'bg-gray-100 text-gray-400' }} px-2 py-0.5 rounded-full font-semibold">{{ $cat->products_count }}</span>
                            </a>
                        @endforeach
                    </nav>
                </div>
            </aside>

            {{-- Main content --}}
            <div class="flex-1 min-w-0">
                <livewire:directory-search :category-id="isset($category) ? $category->id : 0" />
            </div>
        </div>
    </div>
</x-app-layout>
