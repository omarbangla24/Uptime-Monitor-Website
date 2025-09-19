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

    // Subscription Plans Management
    // To avoid conflicts with the resourceful show route (subscriptions/{subscription}),
    // we register manual assignment routes with a unique slug *before* the resource.
    Route::get('subscriptions/manual-assign', [App\Http\Controllers\Admin\SubscriptionController::class, 'assignForm'])
        ->name('subscriptions.assignForm');
    Route::post('subscriptions/manual-assign', [App\Http\Controllers\Admin\SubscriptionController::class, 'assign'])
        ->name('subscriptions.assign');

    // Register CRUD routes for subscription plans. Allows admins to view,
    // create, edit and delete plans from the dashboard.
    Route::resource('subscriptions', App\Http\Controllers\Admin\SubscriptionController::class);

    // Additional routes for subscriptions: toggle plan status and view users under a plan.
    Route::post('subscriptions/{subscription}/toggle-status', [App\Http\Controllers\Admin\SubscriptionController::class, 'toggleStatus'])
        ->name('subscriptions.toggleStatus');
    Route::get('subscriptions/{subscription}/users', [App\Http\Controllers\Admin\SubscriptionController::class, 'users'])
        ->name('subscriptions.users');
});
