<x-app-layout>
    {{-- Page header: compact --}}
    <section class="border-b border-gray-100 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-5 sm:py-6">
            <div class="flex items-center gap-3">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900">
                        @if(isset($category))
                            {{ $category->name }}
                        @else
                            Product Directory
                        @endif
                    </h1>
                    <p class="text-sm text-gray-500 mt-0.5">
                        @if(isset($category))
                            {{ $category->products_count ?? 0 }} products
                        @else
                            {{ \App\Models\Product::approved()->directory()->count() }} products from indie makers
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </section>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
        <livewire:directory-search :category-id="isset($category) ? $category->id : 0" :categories="$categories" />
    </div>
</x-app-layout>
