<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class SubmitController extends Controller
{
    public function index()
    {
        return view('pages.submit');
    }

    /**
     * Shown after user completes Polar checkout (success_url).
     */
    public function success(Request $request, Product $product)
    {
        if ($product->user_id !== $request->user()?->id) {
            abort(404);
        }

        return view('pages.submit-success', ['product' => $product]);
    }
}
