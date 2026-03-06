<x-app-layout>
    {{-- Header --}}
    <div class="relative overflow-hidden bg-gradient-to-br from-amber-50 via-orange-50/30 to-white border-b border-gray-100">
        <div class="absolute top-0 right-0 w-72 h-72 bg-amber-200/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
        <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 text-center">
            <div class="inline-flex items-center gap-2 bg-white/80 backdrop-blur-sm border border-amber-200/60 rounded-full px-4 py-1.5 mb-4 shadow-sm">
                <span class="text-sm">🚀</span>
                <span class="text-xs font-semibold text-gray-600 tracking-wide">FREE TO SUBMIT</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900">Submit Your Product</h1>
            <p class="text-gray-500 mt-2 max-w-md mx-auto">Get discovered by thousands of makers, early adopters, and tech enthusiasts.</p>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <livewire:submit-product />
    </div>
</x-app-layout>
