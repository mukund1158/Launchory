# Launchory — Claude Code Prompt
## Laravel 12 + Breeze (Livewire Volt Class) + Alpine.js + Tailwind CSS + MySQL

---

## CONTEXT

I am building **Launchory** — a product launch + directory platform similar to TinyLaunch.com.
Makers submit products, community votes, top 3 winners get a badge + dofollow backlink.
It also has a permanent searchable product directory.

**Already done:**
- Laravel 12 installed
- Breeze installed with Livewire (Volt Class) starter kit
- Auth working (login, register, email verification)
- Tailwind CSS + Alpine.js + Livewire working via Vite
- MySQL database connected

**Do NOT re-install or touch:**
- Breeze auth pages (login, register, forgot password)
- Existing User model and users migration
- Vite config

**App name:** Launchory

---

## STEP 1 — Install Required Packages

Run these composer and npm installs:

```bash
composer require livewire/livewire
composer require spatie/laravel-permission
composer require spatie/laravel-sluggable
composer require spatie/laravel-medialibrary
composer require spatie/laravel-sitemap
composer require artesaos/seotools
composer require filament/filament:"^3.0" -W
composer require laravel/cashier
php artisan filament:install --panels
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan vendor:publish --tag="seotools-config"
```

After installing confirm and wait for my go-ahead before Step 2.

---

## STEP 2 — Database Migrations

Create migrations in this exact order. Do NOT modify the existing users or password_reset migrations.

### 2a. Add columns to existing users table
Create a migration to add to users table:
- username (string, unique, nullable)
- avatar (string, nullable)
- bio (text, nullable)
- twitter_handle (string, nullable)
- website_url (string, nullable)

### 2b. categories table
```
id
name (string)
slug (string, unique)
icon (string) — emoji like 🤖
description (text, nullable)
sort_order (integer, default: 0)
timestamps
```

### 2c. products table
```
id
user_id (foreignId, constrained, cascadeOnDelete)
name (string)
slug (string, unique)
tagline (string, max 100)
description (text)
logo (string, nullable)
website_url (string)
category_id (foreignId, constrained)
listing_type (enum: launch, directory, both — default: both)
status (enum: pending, approved, rejected — default: pending)
is_featured (boolean, default: false)
featured_until (date, nullable)
launch_date (date, nullable)
vote_count (unsignedInteger, default: 0)
is_do_follow (boolean, default: false)
twitter_handle (string, nullable)
maker_comment (text, nullable)
pricing (enum: free, freemium, paid — default: free)
timestamps

Indexes: status, launch_date, listing_type, is_featured
```

### 2d. votes table
```
id
user_id (foreignId, constrained, cascadeOnDelete)
product_id (foreignId, constrained, cascadeOnDelete)
timestamps

unique constraint on [user_id, product_id]
```

### 2e. badges table
```
id
product_id (foreignId, constrained, cascadeOnDelete)
rank (enum: gold, silver, bronze)
launch_date (date)
timestamps
```

### 2f. featured_slots table
```
id
product_id (foreignId, constrained, cascadeOnDelete)
slot_type (enum: homepage, directory, launch)
starts_at (datetime)
ends_at (datetime)
amount_paid (decimal 8,2)
stripe_payment_id (string, nullable)
timestamps
```

### 2g. newsletter_subscribers table
```
id
email (string, unique)
name (string, nullable)
confirmed (boolean, default: false)
token (string, unique)
timestamps
```

### 2h. launch_periods table
```
id
starts_at (datetime)
ends_at (datetime)
status (enum: upcoming, active, completed — default: upcoming)
timestamps
```

Run: php artisan migrate
Confirm success before Step 3.

---

## STEP 3 — Models

Update/create these Eloquent models:

### User model (update existing)
Add to fillable: username, avatar, bio, twitter_handle, website_url
Relationships:
- hasMany(Product::class)
- hasMany(Vote::class)
Add accessor: getAvatarUrlAttribute() — return avatar if set, else generate UI avatar from name using https://ui-avatars.com/api/?name={name}&background=f59e0b&color=fff

### Product model (create new)
Use HasSlug (Spatie), HasMedia (Spatie)
Fillable: all columns
Relationships:
- belongsTo(User::class)
- belongsTo(Category::class)
- hasMany(Vote::class)
- hasOne(Badge::class)
- hasMany(FeaturedSlot::class)

Scopes:
- scopeApproved($q) — where status = approved
- scopeLaunches($q) — whereIn listing_type [launch, both]
- scopeDirectory($q) — whereIn listing_type [directory, both]
- scopeFeatured($q) — where is_featured true AND featured_until >= today
- scopeToday($q) — where launch_date = today
- scopeByVotes($q) — orderBy vote_count desc

Helpers:
- hasVotedBy($userId): bool — check votes relationship
- getLogoUrlAttribute() — return logo url or placeholder

Spatie slug: generateSlugFrom('name')

### Category model
Fillable: name, slug, icon, description, sort_order
HasSlug: generateSlugFrom('name')
Relationship: hasMany(Product::class)
Scope: scopeOrdered($q) — orderBy sort_order asc

### Vote model
Fillable: user_id, product_id
Relationships: belongsTo User, belongsTo Product

### Badge model
Fillable: product_id, rank, launch_date
Relationship: belongsTo Product
Helper: getBadgeEmojiAttribute() — gold=🥇, silver=🥈, bronze=🥉
Helper: getBadgeColorAttribute() — gold=#f59e0b, silver=#9ca3af, bronze=#b45309

### FeaturedSlot model
Fillable: all
Relationships: belongsTo Product
Scope: scopeActive($q) — where starts_at <= now AND ends_at >= now

### NewsletterSubscriber model
Fillable: email, name, confirmed, token
Boot: generating token on creating using Str::random(32)

### LaunchPeriod model
Fillable: starts_at, ends_at, status
Scope: scopeActive($q) — where status = active
Static helper: current() — return active launch period or null

---

## STEP 4 — Seeders

### CategorySeeder
Seed these exactly:
```php
[
  ['name' => 'AI & Machine Learning', 'icon' => '🤖', 'sort_order' => 1],
  ['name' => 'Productivity', 'icon' => '⚡', 'sort_order' => 2],
  ['name' => 'Developer Tools', 'icon' => '🛠️', 'sort_order' => 3],
  ['name' => 'Marketing & Sales', 'icon' => '📈', 'sort_order' => 4],
  ['name' => 'SaaS & Tools', 'icon' => '💼', 'sort_order' => 5],
  ['name' => 'Design & Art', 'icon' => '🎨', 'sort_order' => 6],
  ['name' => 'Health & Wellness', 'icon' => '🏃', 'sort_order' => 7],
  ['name' => 'Finance & FinTech', 'icon' => '💰', 'sort_order' => 8],
  ['name' => 'Education & Learning', 'icon' => '📚', 'sort_order' => 9],
  ['name' => 'Social Media', 'icon' => '📱', 'sort_order' => 10],
  ['name' => 'E-commerce', 'icon' => '🛒', 'sort_order' => 11],
  ['name' => 'Startup & Business', 'icon' => '🚀', 'sort_order' => 12],
  ['name' => 'Video & Content', 'icon' => '🎬', 'sort_order' => 13],
  ['name' => 'Community & Networking', 'icon' => '🤝', 'sort_order' => 14],
]
```

### AdminSeeder
Create admin user:
- name: Admin
- email: admin@launchory.com
- username: admin
- password: password (hashed)
- Assign role: admin (using Spatie Permission)

### ProductSeeder (for development testing only)
Create 20 fake approved products across different categories with random vote counts, mix of launch + directory listing types. Use Laravel factories.

Run: php artisan db:seed
Confirm before Step 5.

---

## STEP 5 — Routes

Add these to routes/web.php (keep existing Breeze auth routes):

```php
// Public
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/launches', [LaunchController::class, 'index'])->name('launches.index');
Route::get('/launches/archive', [LaunchController::class, 'archive'])->name('launches.archive');
Route::get('/directory', [DirectoryController::class, 'index'])->name('directory.index');
Route::get('/directory/{category:slug}', [DirectoryController::class, 'category'])->name('directory.category');
Route::get('/product/{product:slug}', [ProductController::class, 'show'])->name('product.show');
Route::get('/makers/{user:username}', [MakerController::class, 'show'])->name('makers.show');
Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/badges/{product:slug}.svg', [BadgeController::class, 'generate'])->name('badge.generate');

// Newsletter
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/confirm/{token}', [NewsletterController::class, 'confirm'])->name('newsletter.confirm');

// Auth required
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/submit', [SubmitController::class, 'index'])->name('submit');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/products', [DashboardController::class, 'products'])->name('dashboard.products');
    Route::post('/vote/{product}', [VoteController::class, 'toggle'])->name('vote.toggle');
    Route::get('/settings/profile', ProfileController::class)->name('settings.profile'); // already exists from Breeze
});
```

---

## STEP 6 — Main Layout

Create resources/views/layouts/app.blade.php

**Design specs:**
- Font: Inter from Google Fonts
- Colors: white bg, #1a1a1a text, #f59e0b amber accent
- Clean, minimal, modern — like TinyLaunch

**Navbar:**
- Left: Launchory logo (amber colored rocket emoji 🚀 + bold text)
- Center links: Launches, Directory
- Right: "Submit Product" amber button + Login/Register OR user avatar dropdown (My Products, Settings, Logout)
- Mobile: hamburger menu with Alpine.js

**Footer:**
- Left: Launchory logo + tagline "Launch your product. Get discovered."
- Middle: Links — Launches, Directory, Submit, Pricing, Sitemap
- Right: Newsletter subscribe form (email input + Subscribe button)
- Bottom: © 2025 Launchory. All rights reserved.
- Social: Twitter/X link

**Flash messages:**
Alpine.js toast notification — bottom right corner, auto-dismiss after 3 seconds
Show session('success') in green, session('error') in red

**Include in head:**
- Google Fonts Inter
- SEOTools meta tags: {!! SEOMeta::generate() !!} {!! OpenGraph::generate() !!}
- Vite: @vite(['resources/css/app.css', 'resources/js/app.js'])
- @livewireStyles

**Include before </body>:**
- @livewireScripts
- Alpine.js (already included via Breeze)

---

## STEP 7 — Homepage

Controller: HomeController@index
- Query: today's launch products (approved, launch_date = today) ordered by vote_count desc
- Query: featured products (is_featured=true, featured_until >= today) limit 6
- Query: latest directory products (approved, directory scope) limit 6, ordered by created_at desc
- Query: latest winners (products with badges) limit 6
- Query: active launch period (LaunchPeriod::current())
- Pass all to view

View: resources/views/pages/home.blade.php

**Section 1 — Hero:**
Large centered hero:
- Headline: "Launch Your Product. Get Discovered." (large bold text)
- Subtext: "Submit your product to Launchory, get community votes, earn a dofollow backlink and a badge to show off."
- Two buttons: "Submit Your Product" (amber, filled) + "Browse Directory" (gray, outlined)
- Small social proof text: "Join 500+ makers who already launched"

**Section 2 — Launching Now:**
- Section title: "🚀 Launching Now"
- If active launch period: show countdown timer using Alpine.js (days/hours/mins/secs)
- Show top 3 products as large highlighted cards with 🥇🥈🥉 rank
- Show remaining products as a list below
- Each product card: logo (rounded, 48x48), rank number, name, tagline, category badge, vote count + Livewire VoteButton component
- If no active period: show "Next launch starts soon — submit your product now"

**Section 3 — Featured Products:**
- Only show if featured products exist
- Title: "⭐ Featured Products"
- Grid of 3 product cards with "Featured" amber badge in top right corner

**Section 4 — Latest Directory Listings:**
- Title: "📁 Recently Added to Directory"
- Grid of 6 product cards
- "Browse All →" link

**Section 5 — Latest Winners:**
- Title: "🏆 Past Launch Winners"
- List of recent badge winners with their badge emoji + rank
- "View Archive →" link

---

## STEP 8 — Launches Page

Controller: LaunchController@index
- Query: active launch period
- Query: all products for today's launch ordered by vote_count desc, eager load user + category + badge

View: resources/views/pages/launches/index.blade.php

Layout:
- Page title: "🚀 Today's Launches"
- Countdown timer (Alpine.js) — time until launch period ends
- Leaderboard table/list:
  - Rank number (1st highlighted gold, 2nd silver, 3rd bronze)
  - Product logo (40x40 rounded)
  - Product name + tagline
  - Maker name (links to maker profile)
  - Category badge (pill)
  - Livewire VoteButton
- Top 3 rows have colored left border (gold/silver/bronze)
- Empty state: "No launches today. Be the first to submit!"

Archive page: LaunchController@archive
- List past launch periods grouped by month
- Show winners with badge emojis
- Pagination (20 per page)

---

## STEP 9 — Directory Page

Controller: DirectoryController@index
Pass to view: categories list, current category if filtering

View: resources/views/pages/directory/index.blade.php

Layout:
- Page title: "📁 Product Directory"
- Subtitle: "Discover {total_count} products from indie makers"
- Two column layout: sidebar (left, 1/4) + main content (right, 3/4)

Sidebar:
- "All Categories" link (active state)
- List of all categories with icons + product count
- Each is a link to /directory/{category-slug}

Main content:
- **Livewire component: DirectorySearch** (handles search + filter + results)
- Search input at top: "Search products..."
- Filter pills: All, Free, Freemium, Paid
- Sort dropdown: Newest, Most Voted
- Product grid (3 columns on desktop, 2 on tablet, 1 on mobile)
- Each card: logo, name, tagline, category badge, pricing badge, "Visit →" button
- Featured products shown first with amber "Featured" badge
- Pagination: 12 per page

Category page: DirectoryController@category
- Same layout but pre-filtered by category
- Category name + icon as page title

---

## STEP 10 — Product Detail Page

Controller: ProductController@show
- Load product with: user, category, badge, votes
- Set SEO meta via SEOTools
- Check if current user has voted

View: resources/views/pages/product/show.blade.php

Layout:
- Top section:
  - Logo (80x80, rounded-xl, shadow)
  - Product name (large, bold) + badge emoji if winner
  - Tagline (gray subtitle)
  - Row: category badge | pricing badge | website link button | Twitter link
  - Livewire VoteButton (large, prominent)

- Main content (2 column):
  - Left (2/3): Full description, Maker's comment (if set)
  - Right (1/3):
    - Maker card: avatar + name + bio + "View profile" link
    - Product info: submitted date, listing type, category
    - Share buttons: Tweet this, Copy link (Alpine.js copy to clipboard)

- Badge section (if product has badge):
  - Show badge: 🥇 Gold Launch Winner — [date]
  - "Embed this badge on your website" section:
  - Code block with copy button (Alpine.js):
    ```html
    <a href="https://launchory.com/product/{slug}" rel="dofollow">
      <img src="https://launchory.com/badges/{slug}.svg" 
           alt="Featured on Launchory" width="200" />
    </a>
    ```

- SEO: set title = "{Product Name} - {Tagline} | Launchory"
- SEO: set description = first 160 chars of description
- SEO: set OG image = product logo

---

## STEP 11 — Livewire Volt Components

Since we are using Volt Class syntax, create these as Volt class components.

### Component 1: VoteButton
File: resources/views/livewire/vote-button.blade.php

```php
<?php
use Livewire\Volt\Component;
use App\Models\Vote;
use App\Models\Product;

new class extends Component {
    public int $productId;
    public int $voteCount;
    public bool $hasVoted = false;

    public function mount(int $productId, int $voteCount, bool $hasVoted): void
    {
        $this->productId = $productId;
        $this->voteCount = $voteCount;
        $this->hasVoted = $hasVoted;
    }

    public function toggle(): void
    {
        if (!auth()->check()) {
            $this->redirect(route('login'));
            return;
        }

        $product = Product::findOrFail($this->productId);
        $userId = auth()->id();
        $existing = Vote::where('user_id', $userId)->where('product_id', $this->productId)->first();

        if ($existing) {
            $existing->delete();
            $product->decrement('vote_count');
            $this->voteCount--;
            $this->hasVoted = false;
        } else {
            Vote::create(['user_id' => $userId, 'product_id' => $this->productId]);
            $product->increment('vote_count');
            $this->voteCount++;
            $this->hasVoted = true;
        }
    }
}
?>

<div>
    <button
        wire:click="toggle"
        class="flex flex-col items-center gap-1 px-4 py-2 rounded-xl border-2 transition-all duration-200
               {{ $hasVoted 
                  ? 'bg-amber-400 border-amber-400 text-white' 
                  : 'bg-white border-gray-200 text-gray-600 hover:border-amber-400 hover:text-amber-500' }}"
    >
        <svg class="w-5 h-5" fill="{{ $hasVoted ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
        </svg>
        <span class="text-sm font-bold">{{ $voteCount }}</span>
    </button>
</div>
```

### Component 2: DirectorySearch
File: resources/views/livewire/directory-search.blade.php

```php
<?php
use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\Product;

new class extends Component {
    use WithPagination;

    public string $search = '';
    public string $pricing = '';
    public string $sort = 'newest';
    public int $categoryId = 0;

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingPricing(): void { $this->resetPage(); }
    public function updatingSort(): void { $this->resetPage(); }

    public function products()
    {
        return Product::approved()
            ->directory()
            ->with(['user', 'category', 'badge'])
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('tagline', 'like', "%{$this->search}%"))
            ->when($this->pricing, fn($q) => $q->where('pricing', $this->pricing))
            ->when($this->categoryId, fn($q) => $q->where('category_id', $this->categoryId))
            ->when($this->sort === 'newest', fn($q) => $q->latest())
            ->when($this->sort === 'votes', fn($q) => $q->orderBy('vote_count', 'desc'))
            ->orderBy('is_featured', 'desc')
            ->paginate(12);
    }
}
?>

<div>
    {{-- Search + Filters --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-6">
        <input
            wire:model.live.debounce.300ms="search"
            type="text"
            placeholder="Search products..."
            class="flex-1 rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400"
        />
        <select wire:model.live="pricing" class="rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
            <option value="">All Pricing</option>
            <option value="free">Free</option>
            <option value="freemium">Freemium</option>
            <option value="paid">Paid</option>
        </select>
        <select wire:model.live="sort" class="rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
            <option value="newest">Newest</option>
            <option value="votes">Most Voted</option>
        </select>
    </div>

    {{-- Results count --}}
    <p class="text-sm text-gray-500 mb-4">{{ $this->products()->total() }} products found</p>

    {{-- Product grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($this->products() as $product)
            <div class="relative bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 p-4">
                @if($product->is_featured)
                    <span class="absolute top-3 right-3 text-xs bg-amber-100 text-amber-700 font-medium px-2 py-0.5 rounded-full">Featured</span>
                @endif
                <div class="flex items-start gap-3">
                    <img src="{{ $product->logo_url }}" alt="{{ $product->name }}" class="w-12 h-12 rounded-xl object-cover border border-gray-100" />
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-gray-900 truncate">{{ $product->name }}</h3>
                        <p class="text-sm text-gray-500 line-clamp-2 mt-0.5">{{ $product->tagline }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-4">
                    <div class="flex gap-2">
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">{{ $product->category->icon }} {{ $product->category->name }}</span>
                        <span class="text-xs px-2 py-1 rounded-full {{ $product->pricing === 'free' ? 'bg-green-100 text-green-700' : ($product->pricing === 'freemium' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700') }}">
                            {{ ucfirst($product->pricing) }}
                        </span>
                    </div>
                    <a href="{{ route('product.show', $product->slug) }}" class="text-sm font-medium text-amber-600 hover:text-amber-700">View →</a>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-16 text-gray-400">
                <p class="text-4xl mb-3">🔍</p>
                <p class="font-medium">No products found</p>
                <p class="text-sm mt-1">Try a different search or filter</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $this->products()->links() }}
    </div>
</div>
```

### Component 3: SubmitProduct (Multi-step form)
File: resources/views/livewire/submit-product.blade.php

```php
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

    public function categories()
    {
        return Category::ordered()->get();
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

        $this->step = 5; // success state
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
                        <option value="">Select category</option>
                        @foreach($this->categories() as $cat)
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
            <button wire:click="nextStep" class="bg-amber-400 hover:bg-amber-500 text-white font-semibold px-8 py-2.5 rounded-xl transition-colors">
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
                @if($logo) <img src="{{ $logo->temporaryUrl() }}" class="mt-3 w-16 h-16 rounded-xl object-cover border" /> @endif
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
            <button wire:click="nextStep" class="bg-amber-400 hover:bg-amber-500 text-white font-semibold px-8 py-2.5 rounded-xl transition-colors">
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
            <button wire:click="nextStep" class="bg-amber-400 hover:bg-amber-500 text-white font-semibold px-8 py-2.5 rounded-xl transition-colors">
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
            <button wire:click="submit" class="bg-amber-400 hover:bg-amber-500 text-white font-bold px-8 py-2.5 rounded-xl transition-colors">
                🚀 Submit to Launchory
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
```

---

## STEP 12 — Maker Profile Page

Controller: MakerController@show($username)
- Find user by username, 404 if not found
- Load their approved products with category
- Count stats: total products, total votes received

View: resources/views/pages/makers/show.blade.php
- Avatar (80x80, rounded-full)
- Name + @username
- Bio
- Twitter link + Website link
- Stats row: X Products | X Total Votes
- Product grid (same cards as directory)
- Empty state if no products

---

## STEP 13 — Dashboard

Controller: DashboardController
View: resources/views/dashboard/ (extend the Breeze dashboard layout)

Dashboard index:
- Welcome message: "Welcome back, {name}! 👋"
- Quick stats: My Products count, Total Votes Received, Pending Products
- Quick action button: "Submit New Product"
- Recent products list (last 5)

Dashboard products page:
- Full list of user's products
- Each row: logo, name, status badge (pending=yellow, approved=green, rejected=red), listing_type, vote_count, created_at, "View" link
- "Submit New Product" button at top

---

## STEP 14 — Filament Admin Panel

Create these Filament resources (app/Filament/Resources/):

### ProductResource
Table columns: logo (image), name, user.name (maker), category.name, listing_type, status (badge), vote_count, is_featured, created_at
Filters: SelectFilter status, SelectFilter listing_type, SelectFilter category_id
Actions:
- Approve: sets status = approved
- Reject: sets status = rejected
- Mark Featured: sets is_featured = true, featured_until = +30 days
- Assign Badge: modal to pick rank (gold/silver/bronze), creates badge record

### UserResource
Table: name, email, username, created_at, products_count
Action: Make Admin (assigns admin role via Spatie)

### CategoryResource
Full CRUD, sortable by sort_order

### BadgeResource
Table: product.name, rank (with emoji), launch_date

### NewsletterSubscriberResource
Table: email, name, confirmed, created_at
Action: Export CSV

### Filament Dashboard Widgets:
- StatsOverviewWidget: Total Approved Products, Total Users, Today's Votes, Pending Review count
- If pending > 0: show warning notification in widget

---

## STEP 15 — Badge SVG Generator

Controller: BadgeController@generate($slug)
- Find product by slug, 404 if not found
- Return SVG response with Content-Type: image/svg+xml

SVG design:
```svg
<svg width="200" height="56" xmlns="http://www.w3.org/2000/svg">
  <rect width="200" height="56" rx="12" fill="#1a1a1a"/>
  <text x="16" y="22" font-family="Inter,sans-serif" font-size="10" fill="#f59e0b">FEATURED ON</text>
  <text x="16" y="42" font-family="Inter,sans-serif" font-size="16" font-weight="bold" fill="white">🚀 Launchory</text>
</svg>
```

---

## STEP 16 — SEO + Sitemap

### SEOTools setup in AppServiceProvider:
```php
SEOMeta::setTitleDefault('Launchory - Launch & Discover Products');
SEOMeta::setDescriptionDefault('Submit your product to Launchory, get community votes, earn a dofollow backlink.');
OpenGraph::setTitle('Launchory');
OpenGraph::setDescription('Launch your product. Get discovered.');
OpenGraph::setSiteName('Launchory');
```

### In ProductController@show:
```php
SEOMeta::setTitle($product->name . ' - ' . $product->tagline);
SEOMeta::setDescription(Str::limit($product->description, 160));
OpenGraph::setTitle($product->name);
OpenGraph::addProperty('type', 'website');
if ($product->logo_url) OpenGraph::addImage($product->logo_url);
```

### SitemapController@index:
Generate sitemap including all approved products, categories, maker profiles, static pages.
Return XML with correct Content-Type header.

---

## STEP 17 — Newsletter

### NewsletterController:
- subscribe(): validate email, check if exists, create subscriber with confirmed=false, send confirmation email, return back with success message
- confirm($token): find by token, set confirmed=true, redirect home with success message

### Mail: NewsletterConfirmationMail
Simple HTML email: "Confirm your subscription to Launchory's weekly digest"
Button: Confirm My Email → /newsletter/confirm/{token}

### Artisan Command: SendWeeklyNewsletter
```bash
php artisan newsletter:send-weekly
```
- Get top 5 products from last 7 days by vote_count
- Get all confirmed subscribers
- Send HTML email to each with product list
- Schedule in routes/console.php: every Monday at 9am

---

## STEP 18 — Pricing Page

View: resources/views/pages/pricing.blade.php

Three pricing cards side by side:

**Free** (gray border)
- Submit to launch queue
- Community voting
- Permanent directory listing
- Dofollow backlink if top 3
- Badge if winner
- $0 / forever

**Featured Launch** (amber border, "Popular" badge)
- Everything in Free
- Highlighted card on launch day
- Top placement in list
- Mentioned in weekly newsletter
- $19 / one-time

**Featured Directory** (dark border)
- Everything in Free
- Homepage spotlight
- Category page top placement
- Amber "Featured" badge
- $9 / month

Add CTA buttons — for now link to a contact/email for manual payment. Stripe integration comes later.

---

## IMPORTANT NOTES FOR CLAUDE CODE

1. Always use Volt Class syntax for Livewire components (not functional API)
2. Use `wire:model.live` for real-time updates, `wire:model.live.debounce.300ms` for search inputs
3. All Blade views should extend layouts.app
4. Use Laravel's `route()` helper everywhere, never hardcode URLs
5. Add `wire:loading` states to all buttons that trigger Livewire actions
6. All forms must have CSRF protection (@csrf in regular forms, handled automatically in Livewire)
7. Use `storage_path` and `asset(Storage::url(...))` for file URLs
8. Run `php artisan storage:link` after setting up media storage
9. Eager load all relationships to avoid N+1 queries
10. All controllers should be in App\Http\Controllers namespace

---

## BUILD ORDER — Do one step at a time, confirm with me before proceeding:

1. ✅ Laravel 12 + Breeze (Livewire Volt) — ALREADY DONE
2. Install all packages (Step 1)
3. Database migrations (Step 2)
4. Models + relationships (Step 3)
5. Seeders (Step 4)
6. Routes (Step 5)
7. Main layout — navbar + footer (Step 6)
8. Homepage (Step 7)
9. Launches page (Step 8)
10. Directory page + DirectorySearch Livewire component (Step 9)
11. Product detail page (Step 10)
12. VoteButton Livewire component (Step 11)
13. Submit Product — Livewire multi-step form (Step 11)
14. Maker profile page (Step 12)
15. Dashboard (Step 13)
16. Filament admin panel (Step 14)
17. Badge SVG generator (Step 15)
18. SEO + Sitemap (Step 16)
19. Newsletter (Step 17)
20. Pricing page (Step 18)

**Start with Step 2 (install packages) since Step 1 is already done. Confirm each step before moving to the next.**