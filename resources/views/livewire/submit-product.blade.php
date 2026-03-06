<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Models\Product;
use App\Models\Category;

new class extends Component {
    use WithFileUploads;

    public int $step = 1;
    public int $totalSteps = 4;

    // Step 1
    public string $name = '';
    public string $tagline = '';
    public string $website_url = '';
    public int $category_id = 0;
    public string $pricing = 'free';

    // Step 2
    public string $description = '';
    public string $twitter_handle = '';
    public string $maker_comment = '';
    public $logo;

    // Step 3
    public string $listing_type = 'both';
    public string $launch_date = '';

    public function with(): array
    {
        return [
            'categories' => Category::ordered()->get(),
        ];
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

    public function validateStep(): void
    {
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
            default => null,
        };
    }

    public function submit(): void
    {
        $this->validateStep();

        $logoPath = null;
        if ($this->logo) {
            $logoPath = $this->logo->store('logos', 'public');
        }

        Product::create([
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
        ]);

        $this->step = 5;
    }
}

?>

<div class="max-w-2xl mx-auto">
    {{-- Progress bar --}}
    @if($step <= $totalSteps)
    <div class="mb-8">
        <div class="flex justify-between text-sm text-gray-500 mb-2">
            <span>Step {{ $step }} of {{ $totalSteps }}</span>
            <span>{{ round(($step / $totalSteps) * 100) }}% complete</span>
        </div>
        <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
            <div class="h-full bg-amber-400 rounded-full transition-all duration-500"
                 style="width: {{ ($step / $totalSteps) * 100 }}%"></div>
        </div>
        <div class="flex justify-between mt-3">
            @foreach(['Basics', 'Details', 'Listing', 'Review'] as $i => $label)
                <span class="text-xs font-medium {{ $step > $i ? 'text-amber-600' : 'text-gray-400' }}">
                    {{ $label }}
                </span>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Step 1: Basics --}}
    @if($step === 1)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
        <h2 class="text-xl font-bold mb-6">Tell us about your product</h2>
        <div class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Product Name *</label>
                <input wire:model="name" type="text" placeholder="My Awesome Product"
                       class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-amber-400" />
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Tagline * <span class="text-gray-400 font-normal">(max 100 chars)</span></label>
                <input wire:model="tagline" type="text" placeholder="The best tool for doing X"
                       class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-amber-400" />
                <p class="text-xs text-gray-400 mt-1">{{ strlen($tagline) }}/100</p>
                @error('tagline') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Website URL *</label>
                <input wire:model="website_url" type="url" placeholder="https://yourproduct.com"
                       class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-amber-400" />
                @error('website_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Category *</label>
                    <select wire:model="category_id" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-amber-400">
                        <option value="0">Select category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Pricing *</label>
                    <select wire:model="pricing" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-amber-400">
                        <option value="free">Free</option>
                        <option value="freemium">Freemium</option>
                        <option value="paid">Paid</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="flex justify-end mt-8">
            <button wire:click="nextStep" wire:loading.attr="disabled" class="bg-amber-400 hover:bg-amber-500 text-white font-semibold px-8 py-2.5 rounded-xl transition-colors">
                Next: Details →
            </button>
        </div>
    </div>
    @endif

    {{-- Step 2: Details --}}
    @if($step === 2)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
        <h2 class="text-xl font-bold mb-6">Add more details</h2>
        <div class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Description *</label>
                <textarea wire:model="description" rows="5" placeholder="Describe what your product does, who it's for, and what makes it special..."
                          class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-amber-400"></textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Product Logo</label>
                <input wire:model="logo" type="file" accept="image/*"
                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-amber-50 file:text-amber-700 file:font-medium hover:file:bg-amber-100" />
                @if($logo)
                    <img src="{{ $logo->temporaryUrl() }}" class="mt-3 w-16 h-16 rounded-xl object-cover border" />
                @endif
                @error('logo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Twitter / X Handle</label>
                <input wire:model="twitter_handle" type="text" placeholder="@yourhandle"
                       class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-amber-400" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Maker's Comment <span class="text-gray-400 font-normal">(optional — shown on product page)</span></label>
                <textarea wire:model="maker_comment" rows="3" placeholder="Why did you build this? What problem does it solve?"
                          class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-amber-400"></textarea>
            </div>
        </div>
        <div class="flex justify-between mt-8">
            <button wire:click="prevStep" class="text-gray-500 hover:text-gray-700 font-medium px-6 py-2.5 rounded-xl border border-gray-200 transition-colors">
                ← Back
            </button>
            <button wire:click="nextStep" wire:loading.attr="disabled" class="bg-amber-400 hover:bg-amber-500 text-white font-semibold px-8 py-2.5 rounded-xl transition-colors">
                Next: Listing →
            </button>
        </div>
    </div>
    @endif

    {{-- Step 3: Listing Type --}}
    @if($step === 3)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
        <h2 class="text-xl font-bold mb-2">How do you want to list?</h2>
        <p class="text-gray-500 text-sm mb-6">Choose how Launchory should feature your product</p>
        <div class="space-y-4">
            @foreach([
                ['value' => 'both', 'title' => '🚀 Launch + Directory', 'desc' => 'Compete in a launch period AND get a permanent directory listing. Recommended!'],
                ['value' => 'launch', 'title' => '⚡ Launch Only', 'desc' => 'Compete for votes during a launch period. Top 3 win badges.'],
                ['value' => 'directory', 'title' => '📁 Directory Only', 'desc' => 'Get a permanent listing in the directory. No voting competition.'],
            ] as $option)
            <label class="flex items-start gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all
                          {{ $listing_type === $option['value'] ? 'border-amber-400 bg-amber-50' : 'border-gray-200 hover:border-gray-300' }}">
                <input wire:model.live="listing_type" type="radio" value="{{ $option['value'] }}" class="mt-1 accent-amber-500" />
                <div>
                    <p class="font-semibold text-gray-900">{{ $option['title'] }}</p>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $option['desc'] }}</p>
                </div>
            </label>
            @endforeach
        </div>
        @if($listing_type !== 'directory')
        <div class="mt-5">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Preferred Launch Date *</label>
            <input wire:model="launch_date" type="date" min="{{ date('Y-m-d') }}"
                   class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-amber-400" />
            @error('launch_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        @endif
        <div class="flex justify-between mt-8">
            <button wire:click="prevStep" class="text-gray-500 hover:text-gray-700 font-medium px-6 py-2.5 rounded-xl border border-gray-200 transition-colors">
                ← Back
            </button>
            <button wire:click="nextStep" wire:loading.attr="disabled" class="bg-amber-400 hover:bg-amber-500 text-white font-semibold px-8 py-2.5 rounded-xl transition-colors">
                Next: Review →
            </button>
        </div>
    </div>
    @endif

    {{-- Step 4: Review --}}
    @if($step === 4)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
        <h2 class="text-xl font-bold mb-6">Review & Submit</h2>
        <div class="space-y-3 text-sm">
            <div class="flex justify-between py-2 border-b border-gray-100"><span class="text-gray-500">Product</span><span class="font-medium">{{ $name }}</span></div>
            <div class="flex justify-between py-2 border-b border-gray-100"><span class="text-gray-500">Tagline</span><span class="font-medium">{{ $tagline }}</span></div>
            <div class="flex justify-between py-2 border-b border-gray-100"><span class="text-gray-500">Website</span><span class="font-medium text-amber-600">{{ $website_url }}</span></div>
            <div class="flex justify-between py-2 border-b border-gray-100"><span class="text-gray-500">Pricing</span><span class="font-medium">{{ ucfirst($pricing) }}</span></div>
            <div class="flex justify-between py-2 border-b border-gray-100"><span class="text-gray-500">Listing</span><span class="font-medium">{{ ucfirst(str_replace('_', ' + ', $listing_type)) }}</span></div>
            @if($launch_date)<div class="flex justify-between py-2 border-b border-gray-100"><span class="text-gray-500">Launch Date</span><span class="font-medium">{{ $launch_date }}</span></div>@endif
        </div>
        <div class="bg-amber-50 rounded-xl p-4 mt-6 text-sm text-amber-800">
            ⏳ Your product will be reviewed by our team within 24 hours. You'll get an email when it's approved.
        </div>
        <div class="flex justify-between mt-8">
            <button wire:click="prevStep" class="text-gray-500 hover:text-gray-700 font-medium px-6 py-2.5 rounded-xl border border-gray-200 transition-colors">
                ← Back
            </button>
            <button wire:click="submit" wire:loading.attr="disabled" class="bg-amber-400 hover:bg-amber-500 text-white font-bold px-8 py-2.5 rounded-xl transition-colors">
                <span wire:loading.remove wire:target="submit">🚀 Submit to Launchory</span>
                <span wire:loading wire:target="submit">Submitting...</span>
            </button>
        </div>
    </div>
    @endif

    {{-- Step 5: Success --}}
    @if($step === 5)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
        <div class="text-6xl mb-4">🎉</div>
        <h2 class="text-2xl font-bold mb-2">You're submitted!</h2>
        <p class="text-gray-500 mb-6">Your product is pending review. We'll email you within 24 hours once it's approved.</p>
        <a href="{{ route('dashboard.products') }}" class="inline-block bg-amber-400 hover:bg-amber-500 text-white font-semibold px-8 py-3 rounded-xl transition-colors">
            View My Products →
        </a>
    </div>
    @endif
</div>
