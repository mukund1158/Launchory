<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Vote;

class VoteController extends Controller
{
    public function toggle(Product $product)
    {
        $userId = auth()->id();
        $existing = Vote::where('user_id', $userId)->where('product_id', $product->id)->first();

        if ($existing) {
            $existing->delete();
            $product->decrement('vote_count');
        } else {
            Vote::create(['user_id' => $userId, 'product_id' => $product->id]);
            $product->increment('vote_count');
        }

        return back();
    }
}
