<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('products.index');
    }
    
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// E-commerce Routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Products
    Route::get('/products', [App\Http\Controllers\ProductController::class, 'index'])
        ->name('products.index');
    
    // Shopping Cart
    Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])
        ->name('cart.index');
    Route::post('/cart', [App\Http\Controllers\CartController::class, 'store'])
        ->name('cart.store');
    Route::patch('/cart/{cartItem}', [App\Http\Controllers\CartController::class, 'update'])
        ->name('cart.update');
    Route::delete('/cart/{cartItem}', [App\Http\Controllers\CartController::class, 'destroy'])
        ->name('cart.destroy');
    
    // Checkout
    Route::post('/checkout', [App\Http\Controllers\CheckoutController::class, 'store'])
        ->name('checkout.store');
    
    // Order Confirmation
    Route::get('/order/confirmation', function () {
        return Inertia::render('Orders/Confirmation');
    })->name('order.confirmation');
    
    // Admin - Product Management
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/products', [App\Http\Controllers\ProductManagementController::class, 'index'])
            ->name('products.index');
        Route::get('/products/create', [App\Http\Controllers\ProductManagementController::class, 'create'])
            ->name('products.create');
        Route::post('/products', [App\Http\Controllers\ProductManagementController::class, 'store'])
            ->name('products.store');
        Route::get('/products/{product}/edit', [App\Http\Controllers\ProductManagementController::class, 'edit'])
            ->name('products.edit');
        Route::patch('/products/{product}', [App\Http\Controllers\ProductManagementController::class, 'update'])
            ->name('products.update');
        Route::delete('/products/{product}', [App\Http\Controllers\ProductManagementController::class, 'destroy'])
            ->name('products.destroy');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
