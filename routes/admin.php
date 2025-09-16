<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WebsiteController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Users Management
    Route::resource('users', UserController::class);
    Route::post('users/{user}/impersonate', [UserController::class, 'impersonate'])->name('users.impersonate');
    Route::post('stop-impersonating', [UserController::class, 'stopImpersonating'])->name('stop-impersonating');

    // Websites Management
    Route::resource('websites', WebsiteController::class)->except(['create', 'store']);
    Route::post('websites/{website}/check', [WebsiteController::class, 'checkNow'])->name('websites.check');
    Route::post('websites/bulk-check', [WebsiteController::class, 'bulkCheck'])->name('websites.bulk-check');

    // Blog Management
    Route::resource('blogs', BlogController::class);

    // Pages Management
    Route::resource('pages', PageController::class);

    // Contact Messages
    Route::resource('contacts', ContactController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::post('contacts/{contact}/reply', [ContactController::class, 'reply'])->name('contacts.reply');

    // Settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');

    // Analytics & Reports
    Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('analytics/export', [AnalyticsController::class, 'export'])->name('analytics.export');

    // System Info
    Route::get('system', [AdminController::class, 'systemInfo'])->name('system.info');
});
