<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $productCount = $user->products()->count();
        $totalVotes = $user->products()->sum('vote_count');
        $pendingCount = $user->products()->where('status', 'pending')->count();
        $recentProducts = $user->products()->with('category')->latest()->limit(5)->get();

        return view('pages.dashboard.index', compact('productCount', 'totalVotes', 'pendingCount', 'recentProducts'));
    }

    public function products()
    {
        $products = auth()->user()->products()->with('category')->latest()->get();

        return view('pages.dashboard.products', compact('products'));
    }
}
