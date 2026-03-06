<?php

namespace App\Http\Controllers;

use App\Models\Category;

class DirectoryController extends Controller
{
    public function index()
    {
        $categories = Category::ordered()->withCount(['products' => fn($q) => $q->approved()->directory()])->get();

        return view('pages.directory.index', compact('categories'));
    }

    public function category(Category $category)
    {
        $categories = Category::ordered()->withCount(['products' => fn($q) => $q->approved()->directory()])->get();

        return view('pages.directory.index', compact('categories', 'category'));
    }
}
