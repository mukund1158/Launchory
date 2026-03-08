<x-app-layout>
    <div class="max-w-xl mx-auto px-4 py-16 text-center">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-10">
            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-emerald-400 to-green-500 flex items-center justify-center text-4xl mx-auto mb-6 shadow-lg shadow-emerald-500/20">
                ✓
            </div>
            <h1 class="text-2xl font-extrabold text-gray-900 mb-2">Payment received</h1>
            <p class="text-gray-500 mb-8">
                Thanks for your payment. <strong class="text-gray-700">{{ $product->name }}</strong> is approved and will appear in the directory and on launch day as planned.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ route('dashboard.products') }}" class="gradient-amber text-white font-semibold px-7 py-3 rounded-xl shadow-md shadow-amber-500/20 hover:shadow-lg transition-all text-sm inline-flex items-center gap-2">
                    View my products
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
                <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-800 font-medium px-5 py-3 rounded-xl border border-gray-200 hover:border-gray-300 transition-all text-sm">
                    Back to home
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
