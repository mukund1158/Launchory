<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function show(Product $product)
    {
        $product->load(['user', 'category', 'badge', 'votes']);

        $hasVoted = auth()->check() ? $product->hasVotedBy(auth()->id()) : false;

        SEOMeta::setTitle($product->name . ' - ' . $product->tagline);
        SEOMeta::setDescription(Str::limit($product->description, 160));
        OpenGraph::setTitle($product->name);
        OpenGraph::addProperty('type', 'website');
        if ($product->logo) {
            OpenGraph::addImage($product->logo_url);
        }

        return view('pages.product.show', compact('product', 'hasVoted'));
    }
}
