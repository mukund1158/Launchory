<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\LaunchPeriod;
use App\Models\Product;
use App\Models\User;
use App\Models\Vote;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $todayLaunches = Product::approved()->launches()->today()->byVotes()->with(['user', 'category', 'badge'])->get();
        $launchPeriod = LaunchPeriod::current();
        $categories = Category::ordered()->withCount(['products' => fn ($q) => $q->where('status', 'approved')])->get();

        // Next launch at 12:00 PM (app timezone) for countdown
        $tz = config('app.timezone', 'Asia/Kolkata');
        $hour = config('app.launch_hour', 12);
        $now = Carbon::now($tz);
        $nextNoon = $now->copy()->startOfDay()->setTime($hour, 0, 0);
        $nextLaunchAt = $now->gte($nextNoon) ? $nextNoon->copy()->addDay() : $nextNoon;

        // Featured products grouped by category (for "Featured in [Category]" sections)
        $featuredByCategory = $categories
            ->filter(fn ($cat) => Product::approved()->featured()->where('category_id', $cat->id)->exists())
            ->take(6)
            ->map(fn ($cat) => [
                'category' => $cat,
                'products' => Product::approved()->featured()->where('category_id', $cat->id)->with(['user', 'category'])->limit(4)->get(),
            ])
            ->values();

        $latestDirectory = Product::approved()->directory()->with(['user', 'category'])->latest()->limit(8)->get();
        $latestWinners = Product::whereHas('badge')->with(['user', 'category', 'badge'])->latest()->limit(6)->get();

        $stats = [
            'products' => Product::approved()->count(),
            'makers' => User::whereHas('products', fn ($q) => $q->where('status', 'approved'))->count(),
            'votes' => Vote::count(),
        ];

        return view('pages.home', compact('todayLaunches', 'featuredByCategory', 'latestDirectory', 'latestWinners', 'launchPeriod', 'categories', 'stats', 'nextLaunchAt'));
    }
}
