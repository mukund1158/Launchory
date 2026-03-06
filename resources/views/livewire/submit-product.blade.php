<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Models\Product;
use App\Models\Category;

new class extends Component {
    use WithFileUploads;

    public int $step = 1;
    public int $totalSteps = 5;

    // Step 1: Product Info
    public string $name = '';
    public string $tagline = '';
    public string $website_url = '';
    public int $category_id = 0;
    public string $pricing = 'free';

    // Step 2: Details
    public string $description = '';
    public string $twitter_handle = '';
    public string $maker_comment = '';
    public $logo;

    // Step 3: Listing Type + Plan combined
    public string $listing_type = 'launch';
    public string $launch_date = '';
    public string $plan = 'free';

    public function with(): array
    {
        return [
            'categories' => Category::ordered()->get(),
        ];
    }

    public function updatedListingType(): void
    {
        // Reset plan to the default for each listing type
        $this->plan = match($this->listing_type) {
            'launch' => 'free',
            'directory' => 'directory_standard',
            'both' => 'bundle_standard',
        };
    }

    public function nextStep(): void
    {
        $this->validateStep();
        $this->step++;
    }

    public function prevStep(): void
    {
        $this->step--;
    }

    public function goToStep(int $target): void
    {
        if ($target < $this->step) {
            $this->step = $target;
        }
    }

    public function getAvailablePlans(): array
    {
        return match($this->listing_type) {
            'launch' => [
                ['value' => 'free', 'title' => 'Free Launch', 'price' => '$0', 'period' => '', 'tag' => null, 'tag_color' => '', 'desc' => 'Launch day listing with community voting. Win a badge and dofollow backlink if you finish in the top 3.', 'features' => [
                    ['text' => 'Launch day listing', 'premium' => false],
                    ['text' => 'Community voting', 'premium' => false],
                    ['text' => 'Badge if top 3', 'premium' => false],
                    ['text' => 'Dofollow backlink if top 3', 'premium' => false],
                ]],
                ['value' => 'launch_featured', 'title' => 'Featured Launch', 'price' => '$19', 'period' => 'one-time', 'tag' => 'Popular', 'tag_color' => 'bg-gradient-to-r from-amber-400 to-orange-400 text-white', 'desc' => 'Maximum visibility on launch day. Highlighted card, top placement, and newsletter mention.', 'features' => [
                    ['text' => 'Everything in Free', 'premium' => false],
                    ['text' => 'Highlighted card on launch day', 'premium' => true],
                    ['text' => 'Top placement in list', 'premium' => true],
                    ['text' => 'Newsletter mention', 'premium' => true],
                ]],
            ],
            'directory' => [
                ['value' => 'directory_standard', 'title' => 'Directory Listing', 'price' => '$9', 'period' => '/month', 'tag' => null, 'tag_color' => '', 'desc' => 'Permanent listing in our product directory with a dofollow backlink to your website.', 'features' => [
                    ['text' => 'Permanent directory listing', 'premium' => false],
                    ['text' => 'Dofollow backlink', 'premium' => false],
                    ['text' => 'Category page listing', 'premium' => false],
                    ['text' => 'Maker profile', 'premium' => false],
                ]],
                ['value' => 'directory_featured', 'title' => 'Featured Directory', 'price' => '$19', 'period' => '/month', 'tag' => 'Best Value', 'tag_color' => 'bg-gradient-to-r from-amber-400 to-orange-400 text-white', 'desc' => 'Premium placement with homepage spotlight, category top position, and featured badge.', 'features' => [
                    ['text' => 'Everything in Standard', 'premium' => false],
                    ['text' => 'Homepage spotlight', 'premium' => true],
                    ['text' => 'Category top position', 'premium' => true],
                    ['text' => 'Featured badge', 'premium' => true],
                ]],
            ],
            'both' => [
                ['value' => 'bundle_standard', 'title' => 'Launch + Directory', 'price' => '$9', 'period' => '/month', 'tag' => null, 'tag_color' => '', 'desc' => 'Free launch day listing with community voting, plus a permanent directory listing with dofollow backlink.', 'features' => [
                    ['text' => 'Launch day listing (free)', 'premium' => false],
                    ['text' => 'Community voting & badges', 'premium' => false],
                    ['text' => 'Permanent directory listing', 'premium' => false],
                    ['text' => 'Dofollow backlink', 'premium' => false],
                ]],
                ['value' => 'bundle_featured', 'title' => 'Featured Bundle', 'price' => '$39', 'period' => '/month', 'tag' => 'Best Value', 'tag_color' => 'bg-gradient-to-r from-amber-400 to-orange-400 text-white', 'desc' => 'The full package — featured launch with top placement, plus featured directory with homepage spotlight.', 'features' => [
                    ['text' => 'Everything in Standard', 'premium' => false],
                    ['text' => 'Featured on launch day', 'premium' => true],
                    ['text' => 'Homepage spotlight', 'premium' => true],
                    ['text' => 'Newsletter mention', 'premium' => true],
                ]],
            ],
        };
    }

    public function getPlanLabel(): string
    {
        foreach ($this->getAvailablePlans() as $p) {
            if ($p['value'] === $this->plan) {
                return $p['title'] . ' — ' . $p['price'] . $p['period'];
            }
        }
        return $this->plan;
    }

    public function isFeaturedPlan(): bool
    {
        return in_array($this->plan, ['launch_featured', 'directory_featured', 'bundle_featured']);
    }

    public function isPaidPlan(): bool
    {
        return $this->plan !== 'free';
    }

    public function validateStep(): void
    {
        $validPlans = array_column($this->getAvailablePlans(), 'value');

        match($this->step) {
            1 => $this->validate([
                'name' => 'required|min:2|max:100',
                'tagline' => 'required|min:10|max:100',
                'website_url' => 'required|url',
                'category_id' => 'required|exists:categories,id',
                'pricing' => 'required|in:free,freemium,paid',
            ]),
            2 => $this->validate([
                'description' => 'required|min:30',
                'logo' => 'nullable|image|max:2048',
            ]),
            3 => $this->validate([
                'listing_type' => 'required|in:launch,directory,both',
                'launch_date' => 'required_if:listing_type,launch,both|nullable|date|after_or_equal:today',
            ]),
            4 => $this->validate([
                'plan' => ['required', 'in:' . implode(',', $validPlans)],
            ]),
            default => null,
        };
    }

    public function submit(): void
    {
        $logoPath = null;
        if ($this->logo) {
            $logoPath = $this->logo->store('logos', 'public');
        }

        $product = Product::create([
            'user_id' => auth()->id(),
            'name' => $this->name,
            'tagline' => $this->tagline,
            'website_url' => $this->website_url,
            'category_id' => $this->category_id,
            'pricing' => $this->pricing,
            'description' => $this->description,
            'twitter_handle' => $this->twitter_handle,
            'maker_comment' => $this->maker_comment,
            'logo' => $logoPath,
            'listing_type' => $this->listing_type,
            'launch_date' => $this->listing_type !== 'directory' ? $this->launch_date : null,
            'status' => 'pending',
            'is_featured' => $this->isFeaturedPlan(),
        ]);

        $this->step = 6;
    }
}

?>

<div class="max-w-2xl mx-auto">
    {{-- Step indicator --}}
    @if($step <= $totalSteps)
    <div class="mb-8">
        {{-- Step dots --}}
        <div class="flex items-center justify-between mb-6">
            @php
                $labels = ['Product', 'Details', 'Listing', 'Plan', 'Review'];
            @endphp
            @foreach($labels as $i => $label)
                @php $num = $i + 1; @endphp
                <div class="flex items-center {{ $i < count($labels) - 1 ? 'flex-1' : '' }}">
                    <button
                        wire:click="goToStep({{ $num }})"
                        class="relative flex flex-col items-center gap-1.5 {{ $num < $step ? 'cursor-pointer' : 'cursor-default' }}"
                    >
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-300
                            {{ $num < $step ? 'bg-amber-400 text-white shadow-md shadow-amber-400/30' : ($num === $step ? 'bg-amber-400 text-white shadow-lg shadow-amber-400/30 ring-4 ring-amber-100' : 'bg-gray-100 text-gray-400') }}">
                            @if($num < $step)
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            @else
                                {{ $num }}
                            @endif
                        </div>
                        <span class="text-[11px] font-semibold {{ $num <= $step ? 'text-amber-600' : 'text-gray-400' }} hidden sm:block">{{ $label }}</span>
                    </button>
                    @if($i < count($labels) - 1)
                        <div class="flex-1 h-0.5 mx-2 mt-[-1.2rem] sm:mt-[-0.4rem] rounded-full {{ $num < $step ? 'bg-amber-400' : 'bg-gray-100' }} transition-all duration-300"></div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Step 1: Product Info --}}
    @if($step === 1)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-8 py-5 border-b border-gray-100 bg-gray-50/50">
            <h2 class="text-lg font-bold text-gray-900">Tell us about your product</h2>
            <p class="text-sm text-gray-500 mt-0.5">Basic information to get started</p>
        </div>
        <div class="p-8 space-y-5">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Product Name <span class="text-red-400">*</span></label>
                <input wire:model="name" type="text" placeholder="e.g. Notion, Figma, Linear"
                       class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm bg-gray-50/50 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent focus:bg-white transition-all" />
                @error('name') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tagline <span class="text-red-400">*</span> <span class="text-gray-400 font-normal text-xs">(max 100 chars)</span></label>
                <input wire:model="tagline" type="text" placeholder="A short catchy description of what it does"
                       class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm bg-gray-50/50 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent focus:bg-white transition-all" />
                <div class="flex items-center justify-between mt-1.5">
                    @error('tagline') <p class="text-red-500 text-xs">{{ $message }}</p> @else <span></span> @enderror
                    <span class="text-xs {{ strlen($tagline) > 80 ? (strlen($tagline) > 100 ? 'text-red-500' : 'text-amber-500') : 'text-gray-400' }}">{{ strlen($tagline) }}/100</span>
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Website URL <span class="text-red-400">*</span></label>
                <div class="relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    <input wire:model="website_url" type="url" placeholder="https://yourproduct.com"
                           class="w-full rounded-xl border border-gray-200 pl-10 pr-4 py-3 text-sm bg-gray-50/50 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent focus:bg-white transition-all" />
                </div>
                @error('website_url') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Category <span class="text-red-400">*</span></label>
                    <select wire:model="category_id" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm bg-gray-50/50 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent cursor-pointer">
                        <option value="0">Select a category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Pricing Model <span class="text-red-400">*</span></label>
                    <div class="flex items-center bg-gray-50 rounded-xl p-1 border border-gray-200">
                        @foreach(['free' => 'Free', 'freemium' => 'Freemium', 'paid' => 'Paid'] as $val => $label)
                            <button type="button" wire:click="$set('pricing', '{{ $val }}')"
                                class="flex-1 px-3 py-2 rounded-lg text-xs font-semibold transition-all text-center
                                    {{ $pricing === $val ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="px-8 py-4 border-t border-gray-100 bg-gray-50/30 flex justify-end">
            <button wire:click="nextStep" wire:loading.attr="disabled" class="gradient-amber text-white font-semibold px-7 py-2.5 rounded-xl shadow-md shadow-amber-500/20 hover:shadow-lg hover:-translate-y-0.5 transition-all flex items-center gap-2 text-sm">
                Continue
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </button>
        </div>
    </div>
    @endif

    {{-- Step 2: Details --}}
    @if($step === 2)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-8 py-5 border-b border-gray-100 bg-gray-50/50">
            <h2 class="text-lg font-bold text-gray-900">Add more details</h2>
            <p class="text-sm text-gray-500 mt-0.5">Help people understand what makes your product special</p>
        </div>
        <div class="p-8 space-y-5">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Description <span class="text-red-400">*</span></label>
                <textarea wire:model="description" rows="5" placeholder="Describe what your product does, who it's for, and what makes it unique..."
                          class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm bg-gray-50/50 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent focus:bg-white transition-all"></textarea>
                @error('description') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Product Logo</label>
                <div class="flex items-start gap-4">
                    @if($logo)
                        <img src="{{ $logo->temporaryUrl() }}" class="w-16 h-16 rounded-2xl object-cover border-2 border-gray-100 shadow-sm shrink-0" />
                    @else
                        <div class="w-16 h-16 rounded-2xl bg-gray-100 border-2 border-dashed border-gray-200 flex items-center justify-center text-gray-400 shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif
                    <div class="flex-1">
                        <label class="block w-full cursor-pointer">
                            <div class="border-2 border-dashed border-gray-200 rounded-xl p-4 text-center hover:border-amber-300 hover:bg-amber-50/30 transition-all">
                                <svg class="w-6 h-6 text-gray-400 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                <p class="text-xs text-gray-500"><span class="font-semibold text-amber-600">Click to upload</span> or drag and drop</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">PNG, JPG up to 2MB. Square recommended.</p>
                            </div>
                            <input wire:model="logo" type="file" accept="image/*" class="hidden" />
                        </label>
                        <div wire:loading wire:target="logo" class="text-xs text-amber-500 mt-2 flex items-center gap-1">
                            <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            Uploading...
                        </div>
                    </div>
                </div>
                @error('logo') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Twitter / X Handle <span class="text-gray-400 font-normal text-xs">(optional)</span></label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm">@</span>
                    <input wire:model="twitter_handle" type="text" placeholder="yourhandle"
                           class="w-full rounded-xl border border-gray-200 pl-8 pr-4 py-3 text-sm bg-gray-50/50 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent focus:bg-white transition-all" />
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Maker's Comment <span class="text-gray-400 font-normal text-xs">(optional — shown on product page)</span></label>
                <textarea wire:model="maker_comment" rows="3" placeholder="Why did you build this? What problem does it solve for you?"
                          class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm bg-gray-50/50 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent focus:bg-white transition-all"></textarea>
            </div>
        </div>
        <div class="px-8 py-4 border-t border-gray-100 bg-gray-50/30 flex justify-between">
            <button wire:click="prevStep" class="text-gray-600 hover:text-gray-800 font-medium px-5 py-2.5 rounded-xl border border-gray-200 hover:border-gray-300 hover:bg-gray-50 transition-all text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                Back
            </button>
            <button wire:click="nextStep" wire:loading.attr="disabled" class="gradient-amber text-white font-semibold px-7 py-2.5 rounded-xl shadow-md shadow-amber-500/20 hover:shadow-lg hover:-translate-y-0.5 transition-all flex items-center gap-2 text-sm">
                Continue
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </button>
        </div>
    </div>
    @endif

    {{-- Step 3: Listing Type --}}
    @if($step === 3)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-8 py-5 border-b border-gray-100 bg-gray-50/50">
            <h2 class="text-lg font-bold text-gray-900">How do you want to list?</h2>
            <p class="text-sm text-gray-500 mt-0.5">This determines which plans are available in the next step</p>
        </div>
        <div class="p-8 space-y-4">
            @php
                $listingOptions = [
                    ['value' => 'launch', 'icon' => '⚡', 'title' => 'Launch Only', 'desc' => 'Compete for votes on launch day. Top 3 win badges & dofollow backlinks.', 'price_hint' => 'Free or from $19', 'tag' => 'Free Available', 'tag_color' => 'bg-emerald-50 text-emerald-600 border-emerald-100'],
                    ['value' => 'directory', 'icon' => '📁', 'title' => 'Directory Only', 'desc' => 'Get a permanent listing in the product directory with a dofollow backlink.', 'price_hint' => 'From $9/mo', 'tag' => null, 'tag_color' => ''],
                    ['value' => 'both', 'icon' => '🚀', 'title' => 'Launch + Directory', 'desc' => 'Best of both worlds — compete on launch day AND get a permanent directory listing.', 'price_hint' => 'From $9/mo', 'tag' => 'Recommended', 'tag_color' => 'bg-amber-50 text-amber-700 border-amber-200'],
                ];
            @endphp

            @foreach($listingOptions as $option)
            <label class="group flex items-start gap-4 p-5 rounded-2xl border-2 cursor-pointer transition-all duration-200
                          {{ $listing_type === $option['value'] ? 'border-amber-400 bg-amber-50/50 shadow-sm' : 'border-gray-100 hover:border-gray-200 hover:bg-gray-50/50' }}">
                <div class="mt-0.5">
                    <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all
                        {{ $listing_type === $option['value'] ? 'border-amber-400 bg-amber-400' : 'border-gray-300' }}">
                        @if($listing_type === $option['value'])
                            <div class="w-2 h-2 rounded-full bg-white"></div>
                        @endif
                    </div>
                    <input wire:model.live="listing_type" type="radio" value="{{ $option['value'] }}" class="hidden" />
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-lg">{{ $option['icon'] }}</span>
                        <span class="font-bold text-gray-900">{{ $option['title'] }}</span>
                        @if($option['tag'])
                            <span class="text-[10px] font-bold uppercase tracking-wider {{ $option['tag_color'] }} border px-2 py-0.5 rounded-full">{{ $option['tag'] }}</span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-500 mt-1 leading-relaxed">{{ $option['desc'] }}</p>
                    <p class="text-xs text-gray-400 mt-2 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $option['price_hint'] }}
                    </p>
                </div>
            </label>
            @endforeach

            @if($listing_type !== 'directory')
            <div class="mt-2 p-5 bg-gray-50 rounded-2xl border border-gray-100">
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Preferred Launch Date <span class="text-red-400">*</span>
                </label>
                <input wire:model="launch_date" type="date" min="{{ date('Y-m-d') }}"
                       class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition-all" />
                <p class="text-xs text-gray-400 mt-1.5">Your product will be scheduled for this date after approval.</p>
                @error('launch_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            @endif
        </div>
        <div class="px-8 py-4 border-t border-gray-100 bg-gray-50/30 flex justify-between">
            <button wire:click="prevStep" class="text-gray-600 hover:text-gray-800 font-medium px-5 py-2.5 rounded-xl border border-gray-200 hover:border-gray-300 hover:bg-gray-50 transition-all text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                Back
            </button>
            <button wire:click="nextStep" wire:loading.attr="disabled" class="gradient-amber text-white font-semibold px-7 py-2.5 rounded-xl shadow-md shadow-amber-500/20 hover:shadow-lg hover:-translate-y-0.5 transition-all flex items-center gap-2 text-sm">
                Continue
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </button>
        </div>
    </div>
    @endif

    {{-- Step 4: Choose Plan (context-aware based on listing_type) --}}
    @if($step === 4)
    @php $plans = $this->getAvailablePlans(); @endphp
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-8 py-5 border-b border-gray-100 bg-gray-50/50">
            <h2 class="text-lg font-bold text-gray-900">Choose your plan</h2>
            <p class="text-sm text-gray-500 mt-0.5">
                @if($listing_type === 'launch')
                    Plans for <span class="font-semibold text-gray-700">Launch Only</span> listing
                @elseif($listing_type === 'directory')
                    Plans for <span class="font-semibold text-gray-700">Directory</span> listing
                @else
                    Plans for <span class="font-semibold text-gray-700">Launch + Directory</span> listing
                @endif
            </p>
        </div>
        <div class="p-6 sm:p-8">
            <div class="grid grid-cols-1 gap-4">
                @foreach($plans as $planOption)
                <label class="group relative flex items-start gap-4 p-5 rounded-2xl border-2 cursor-pointer transition-all duration-200
                              {{ $plan === $planOption['value'] ? 'border-amber-400 bg-amber-50/50 shadow-sm' : 'border-gray-100 hover:border-gray-200 hover:bg-gray-50/50' }}">
                    @if($planOption['tag'])
                        <div class="absolute -top-2.5 right-4">
                            <span class="{{ $planOption['tag_color'] }} text-[10px] font-bold uppercase tracking-wider px-3 py-0.5 rounded-full shadow-sm">{{ $planOption['tag'] }}</span>
                        </div>
                    @endif
                    <div class="mt-0.5 shrink-0">
                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all
                            {{ $plan === $planOption['value'] ? 'border-amber-400 bg-amber-400' : 'border-gray-300' }}">
                            @if($plan === $planOption['value'])
                                <div class="w-2 h-2 rounded-full bg-white"></div>
                            @endif
                        </div>
                        <input wire:model.live="plan" type="radio" value="{{ $planOption['value'] }}" class="hidden" />
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between flex-wrap gap-2">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-gray-900 text-base">{{ $planOption['title'] }}</span>
                                @if($planOption['period'])
                                    <span class="text-[10px] font-bold uppercase tracking-wider bg-gray-100 text-gray-600 border border-gray-200 px-2 py-0.5 rounded-full">{{ $planOption['period'] }}</span>
                                @endif
                            </div>
                            <span class="text-lg font-extrabold text-gray-900">{{ $planOption['price'] }}</span>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">{{ $planOption['desc'] }}</p>
                        <div class="flex flex-wrap gap-x-4 gap-y-1 mt-3">
                            @foreach($planOption['features'] as $feature)
                                <span class="text-xs flex items-center gap-1 {{ $feature['premium'] ? 'text-amber-600 font-medium' : 'text-gray-500' }}">
                                    <svg class="w-3.5 h-3.5 {{ $feature['premium'] ? 'text-amber-500' : 'text-emerald-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    {{ $feature['text'] }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </label>
                @endforeach
            </div>

            @if($this->isPaidPlan())
                <div class="mt-5 p-4 bg-blue-50 rounded-xl border border-blue-100 flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm text-blue-700">Payment will be processed after your product is approved. You'll receive an email with a secure payment link.</p>
                </div>
            @endif
        </div>
        <div class="px-8 py-4 border-t border-gray-100 bg-gray-50/30 flex justify-between">
            <button wire:click="prevStep" class="text-gray-600 hover:text-gray-800 font-medium px-5 py-2.5 rounded-xl border border-gray-200 hover:border-gray-300 hover:bg-gray-50 transition-all text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                Back
            </button>
            <button wire:click="nextStep" wire:loading.attr="disabled" class="gradient-amber text-white font-semibold px-7 py-2.5 rounded-xl shadow-md shadow-amber-500/20 hover:shadow-lg hover:-translate-y-0.5 transition-all flex items-center gap-2 text-sm">
                Continue
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </button>
        </div>
    </div>
    @endif

    {{-- Step 5: Review --}}
    @if($step === 5)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-8 py-5 border-b border-gray-100 bg-gray-50/50">
            <h2 class="text-lg font-bold text-gray-900">Review & Submit</h2>
            <p class="text-sm text-gray-500 mt-0.5">Make sure everything looks good before submitting</p>
        </div>
        <div class="p-8">
            {{-- Product preview card --}}
            <div class="bg-gray-50 rounded-2xl border border-gray-100 p-5 mb-6">
                <div class="flex items-start gap-4">
                    @if($logo)
                        <img src="{{ $logo->temporaryUrl() }}" class="w-14 h-14 rounded-2xl object-cover border border-gray-200 shadow-sm" />
                    @else
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-xl text-white font-bold shadow-sm">
                            {{ strtoupper(substr($name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-gray-900 text-lg">{{ $name }}</h3>
                        <p class="text-sm text-gray-500 mt-0.5">{{ $tagline }}</p>
                    </div>
                </div>
            </div>

            {{-- Details grid --}}
            <div class="space-y-0 divide-y divide-gray-100">
                <div class="flex items-center justify-between py-3">
                    <span class="text-sm text-gray-500">Website</span>
                    <span class="text-sm font-medium text-amber-600 truncate ml-4">{{ $website_url }}</span>
                </div>
                <div class="flex items-center justify-between py-3">
                    <span class="text-sm text-gray-500">Category</span>
                    <span class="text-sm font-medium text-gray-900">{{ $categories->firstWhere('id', $category_id)?->name ?? '—' }}</span>
                </div>
                <div class="flex items-center justify-between py-3">
                    <span class="text-sm text-gray-500">Product Pricing</span>
                    <span class="text-xs px-2.5 py-1 rounded-full font-medium
                        {{ $pricing === 'free' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : ($pricing === 'freemium' ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'bg-purple-50 text-purple-600 border border-purple-100') }}">
                        {{ ucfirst($pricing) }}
                    </span>
                </div>
                <div class="flex items-center justify-between py-3">
                    <span class="text-sm text-gray-500">Listing Type</span>
                    <span class="text-sm font-medium text-gray-900">
                        @if($listing_type === 'both') Launch + Directory
                        @elseif($listing_type === 'launch') Launch Only
                        @else Directory Only
                        @endif
                    </span>
                </div>
                @if($launch_date)
                <div class="flex items-center justify-between py-3">
                    <span class="text-sm text-gray-500">Launch Date</span>
                    <span class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($launch_date)->format('M d, Y') }}</span>
                </div>
                @endif
                <div class="flex items-center justify-between py-3">
                    <span class="text-sm text-gray-500">Plan</span>
                    <span class="text-sm font-bold {{ $this->isPaidPlan() ? 'text-amber-600' : 'text-gray-900' }}">
                        {{ $this->getPlanLabel() }}
                    </span>
                </div>
            </div>

            {{-- Notice --}}
            <div class="mt-6 p-4 bg-amber-50 rounded-xl border border-amber-100 flex items-start gap-3">
                <span class="text-lg">⏳</span>
                <div>
                    <p class="text-sm font-semibold text-amber-800">Review within 24 hours</p>
                    <p class="text-sm text-amber-700 mt-0.5">Our team will review your submission and email you once it's approved.{{ $this->isPaidPlan() ? ' A secure payment link will be sent after approval.' : '' }}</p>
                </div>
            </div>
        </div>
        <div class="px-8 py-4 border-t border-gray-100 bg-gray-50/30 flex justify-between">
            <button wire:click="prevStep" class="text-gray-600 hover:text-gray-800 font-medium px-5 py-2.5 rounded-xl border border-gray-200 hover:border-gray-300 hover:bg-gray-50 transition-all text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                Back
            </button>
            <button wire:click="submit" wire:loading.attr="disabled" class="gradient-amber text-white font-bold px-8 py-3 rounded-xl shadow-lg shadow-amber-500/20 hover:shadow-xl hover:-translate-y-0.5 transition-all flex items-center gap-2 text-sm">
                <span wire:loading.remove wire:target="submit" class="flex items-center gap-2">
                    🚀 Submit to Launchory
                </span>
                <span wire:loading wire:target="submit" class="flex items-center gap-2">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    Submitting...
                </span>
            </button>
        </div>
    </div>
    @endif

    {{-- Step 6: Success --}}
    @if($step === 6)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-emerald-400 to-green-500 flex items-center justify-center text-4xl mx-auto mb-6 shadow-lg shadow-emerald-500/20">
            🎉
        </div>
        <h2 class="text-2xl font-extrabold text-gray-900 mb-2">You're all set!</h2>
        <p class="text-gray-500 max-w-sm mx-auto mb-8">Your product <strong class="text-gray-700">{{ $name }}</strong> has been submitted and is pending review. We'll email you within 24 hours.</p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('dashboard.products') }}" class="gradient-amber text-white font-semibold px-7 py-3 rounded-xl shadow-md shadow-amber-500/20 hover:shadow-lg hover:-translate-y-0.5 transition-all text-sm flex items-center gap-2">
                View My Products
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
            <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-800 font-medium px-5 py-3 rounded-xl border border-gray-200 hover:border-gray-300 hover:bg-gray-50 transition-all text-sm">
                Back to Home
            </a>
        </div>
    </div>
    @endif
</div>
