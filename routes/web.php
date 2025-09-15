<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\ToolsController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

// Landing page
Route::get('/', [LandingController::class, 'welcome'])->name('welcome');
Route::get('/api/live-updates', [LandingController::class, 'liveUpdates']);

// Public routes
Route::get('/tools', [ToolsController::class, 'index'])->name('tools.index');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{blog:slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/pricing', [PageController::class, 'pricing'])->name('pricing');
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');

// Dynamic pages
Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Website monitoring
    Route::resource('websites', WebsiteController::class);
    Route::get('/reports', [DashboardController::class, 'reports'])->name('reports.index');
    Route::get('/billing', [DashboardController::class, 'billing'])->name('billing.index');
});

require __DIR__.'/auth.php';
