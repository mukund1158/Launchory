<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemap = Sitemap::create()
            ->add(Url::create(route('home')))
            ->add(Url::create(route('launches.index')))
            ->add(Url::create(route('directory.index')))
            ->add(Url::create(route('pricing')));

        Product::approved()->each(function (Product $product) use ($sitemap) {
            $sitemap->add(Url::create(route('product.show', $product->slug)));
        });

        Category::each(function (Category $category) use ($sitemap) {
            $sitemap->add(Url::create(route('directory.category', $category->slug)));
        });

        User::whereHas('products', fn($q) => $q->approved())->each(function (User $user) use ($sitemap) {
            $sitemap->add(Url::create(route('makers.show', $user->username)));
        });

        return $sitemap->toResponse(request());
    }
}
