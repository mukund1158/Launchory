<x-app-layout>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center mb-12">
            <h1 class="text-3xl sm:text-4xl font-extrabold">Simple, transparent pricing</h1>
            <p class="text-gray-500 mt-3 max-w-lg mx-auto">Launch for free or boost your visibility with a featured listing.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Free --}}
            <div class="bg-white rounded-2xl border-2 border-gray-200 p-8 flex flex-col">
                <h2 class="text-lg font-bold">Free</h2>
                <p class="text-gray-500 text-sm mt-1">Everything you need to get started</p>
                <div class="mt-6">
                    <span class="text-4xl font-extrabold">$0</span>
                    <span class="text-gray-400 text-sm ml-1">/ forever</span>
                </div>
                <ul class="mt-8 space-y-3 text-sm text-gray-600 flex-1">
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Submit to launch queue
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Community voting
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Permanent directory listing
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Dofollow backlink if top 3
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Badge if winner
                    </li>
                </ul>
                <a href="{{ route('submit') }}" class="mt-8 block text-center bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-3 rounded-xl transition-colors">
                    Get Started Free
                </a>
            </div>

            {{-- Featured Launch --}}
            <div class="bg-white rounded-2xl border-2 border-amber-400 p-8 flex flex-col relative">
                <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-amber-400 text-white text-xs font-bold px-4 py-1 rounded-full">Popular</span>
                <h2 class="text-lg font-bold">Featured Launch</h2>
                <p class="text-gray-500 text-sm mt-1">Maximum visibility on launch day</p>
                <div class="mt-6">
                    <span class="text-4xl font-extrabold">$19</span>
                    <span class="text-gray-400 text-sm ml-1">/ one-time</span>
                </div>
                <ul class="mt-8 space-y-3 text-sm text-gray-600 flex-1">
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Everything in Free
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Highlighted card on launch day
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Top placement in list
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Mentioned in weekly newsletter
                    </li>
                </ul>
                <a href="mailto:hello@launchory.com?subject=Featured Launch" class="mt-8 block text-center bg-amber-400 hover:bg-amber-500 text-white font-bold py-3 rounded-xl transition-colors">
                    Get Featured
                </a>
            </div>

            {{-- Featured Directory --}}
            <div class="bg-white rounded-2xl border-2 border-gray-900 p-8 flex flex-col">
                <h2 class="text-lg font-bold">Featured Directory</h2>
                <p class="text-gray-500 text-sm mt-1">Ongoing visibility in the directory</p>
                <div class="mt-6">
                    <span class="text-4xl font-extrabold">$9</span>
                    <span class="text-gray-400 text-sm ml-1">/ month</span>
                </div>
                <ul class="mt-8 space-y-3 text-sm text-gray-600 flex-1">
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Everything in Free
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Homepage spotlight
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Category page top placement
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Amber "Featured" badge
                    </li>
                </ul>
                <a href="mailto:hello@launchory.com?subject=Featured Directory" class="mt-8 block text-center bg-gray-900 hover:bg-gray-800 text-white font-bold py-3 rounded-xl transition-colors">
                    Get Featured
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
