<?php

use App\Http\Controllers\BadgeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DirectoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LaunchController;
use App\Http\Controllers\MakerController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SubmitController;
use App\Http\Controllers\VoteController;
use Illuminate\Support\Facades\Route;

// Public
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/launches', [LaunchController::class, 'index'])->name('launches.index');
Route::get('/launches/archive', [LaunchController::class, 'archive'])->name('launches.archive');
Route::get('/directory', [DirectoryController::class, 'index'])->name('directory.index');
Route::get('/directory/{category:slug}', [DirectoryController::class, 'category'])->name('directory.category');
Route::get('/product/{product:slug}', [ProductController::class, 'show'])->name('product.show');
Route::get('/makers/{user:username}', [MakerController::class, 'show'])->name('makers.show');
Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/badges/{product:slug}.svg', [BadgeController::class, 'generate'])->name('badge.generate');

// Newsletter
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/confirm/{token}', [NewsletterController::class, 'confirm'])->name('newsletter.confirm');

// Polar webhook (no auth, no CSRF)
Route::post('/webhooks/polar', App\Http\Controllers\PolarWebhookController::class)->name('webhooks.polar');

// Auth required
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/submit', [SubmitController::class, 'index'])->name('submit');
    Route::get('/submit/success/{product}', [SubmitController::class, 'success'])->name('submit.success');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/products', [DashboardController::class, 'products'])->name('dashboard.products');
    Route::post('/vote/{product}', [VoteController::class, 'toggle'])->name('vote.toggle');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
