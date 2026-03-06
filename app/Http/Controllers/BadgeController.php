<?php

namespace App\Http\Controllers;

use App\Models\Product;

class BadgeController extends Controller
{
    public function generate(Product $product)
    {
        $svg = <<<'SVG'
<svg width="200" height="56" xmlns="http://www.w3.org/2000/svg">
  <rect width="200" height="56" rx="12" fill="#1a1a1a"/>
  <text x="16" y="22" font-family="Inter,sans-serif" font-size="10" fill="#f59e0b">FEATURED ON</text>
  <text x="16" y="42" font-family="Inter,sans-serif" font-size="16" font-weight="bold" fill="white">🚀 Launchory</text>
</svg>
SVG;

        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
