<?php

namespace App\Http\Controllers;

use App\Models\LaunchPeriod;
use App\Models\Product;

class LaunchController extends Controller
{
    public function index()
    {
        $launchPeriod = LaunchPeriod::current();
        $products = Product::approved()->launches()->today()->byVotes()->with(['user', 'category', 'badge'])->get();

        return view('pages.launches.index', compact('launchPeriod', 'products'));
    }

    public function archive()
    {
        $winners = Product::whereHas('badge')->with(['user', 'category', 'badge'])->latest()->paginate(20);

        return view('pages.launches.archive', compact('winners'));
    }
}
