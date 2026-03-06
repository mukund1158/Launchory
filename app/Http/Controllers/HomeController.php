<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\LaunchPeriod;
use App\Models\Product;
use App\Models\User;
use App\Models\Vote;

class HomeController extends Controller
{
    public function index()
    {
        $todayLaunches = Product::approved()->launches()->today()->byVotes()->with(['user', 'category', 'badge'])->get();
        $featured = Product::approved()->featured()->with(['user', 'category'])->limit(6)->get();
        $latestDirectory = Product::approved()->directory()->with(['user', 'category'])->latest()->limit(6)->get();
        $latestWinners = Product::whereHas('badge')->with(['user', 'category', 'badge'])->latest()->limit(6)->get();
        $launchPeriod = LaunchPeriod::current();
        $categories = Category::ordered()->withCount(['products' => fn($q) => $q->where('status', 'approved')])->get();

        $stats = [
            'products' => Product::approved()->count(),
            'makers' => User::whereHas('products', fn($q) => $q->where('status', 'approved'))->count(),
            'votes' => Vote::count(),
        ];

        return view('pages.home', compact('todayLaunches', 'featured', 'latestDirectory', 'latestWinners', 'launchPeriod', 'categories', 'stats'));
    }
}
