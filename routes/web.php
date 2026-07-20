<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PlaceholderController;
use Illuminate\Support\Facades\Route;

// Public site
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{slug}', [CatalogController::class, 'show'])->name('catalog.show');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/placeholder.svg', [PlaceholderController::class, 'svg'])->name('placeholder');

// Admin auth
Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [LoginController::class, 'showLogin'])->name('admin.login');
    Route::post('/admin/login', [LoginController::class, 'login']);
});

// Admin panel
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', AdminProductController::class)->except(['show']);
    Route::get('/settings', [AdminSettingController::class, 'edit'])->name('settings.edit');
    Route::post('/settings/hero/{page}', [AdminSettingController::class, 'updateHero'])
        ->whereIn('page', ['home', 'catalog', 'about'])
        ->name('settings.hero.update');
});
