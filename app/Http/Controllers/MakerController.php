<?php

namespace App\Http\Controllers;

use App\Models\User;

class MakerController extends Controller
{
    public function show(User $user)
    {
        $products = $user->products()->approved()->with('category')->latest()->get();
        $totalVotes = $user->products()->sum('vote_count');

        return view('pages.makers.show', compact('user', 'products', 'totalVotes'));
    }
}
