<?php

namespace App\Http\Controllers;

use App\Models\LaunchPeriod;
use App\Models\Product;

class LaunchController extends Controller
{
    public function index()
    {
        $all = Product::approved()
            ->launches()
            ->today()
            ->byVotes()
            ->with(['user', 'category', 'badge'])
            ->get();

        $top3 = $all->take(3)->values();
        $top3Ids = $top3->pluck('id')->all();
        $featured = $all->where('is_featured', true)->whereNotIn('id', $top3Ids)->values();
        $featuredIds = $featured->pluck('id')->all();
        $others = $all->whereNotIn('id', array_merge($top3Ids, $featuredIds))->values();

        return view('pages.launches.index', compact('top3', 'featured', 'others'));
    }

    public function archive()
    {
        $winners = Product::whereHas('badge')->with(['user', 'category', 'badge'])->latest()->paginate(20);

        return view('pages.launches.archive', compact('winners'));
    }
}
