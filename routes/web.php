<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PosController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

// Duitku Webhook (No Auth)
Route::post('/api/duitku/callback', [\App\Http\Controllers\DuitkuCallbackController::class, 'callback'])->name('pos.duitku.callback');

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [PosController::class, 'catalog'])->name('home');
    Route::get('/product/detail/{id?}', [PosController::class, 'detail'])->name('pos.detail');
    Route::get('/cart', [PosController::class, 'cart'])->name('pos.cart');
    
    // Cart actions
    Route::post('/cart/add', [PosController::class, 'addToCart'])->name('pos.cart.add');
    Route::post('/cart/update', [PosController::class, 'updateCart'])->name('pos.cart.update');
    Route::post('/cart/remove', [PosController::class, 'removeFromCart'])->name('pos.cart.remove');
    
    // Checkout & Payment
    Route::get('/payment', [PosController::class, 'payment'])->name('pos.payment');
    Route::post('/checkout', [PosController::class, 'checkout'])->name('pos.checkout');
    Route::get('/payment/return/{order}', [PosController::class, 'success'])->name('pos.success');
    
    // Order tracking & history
    Route::get('/order-status/{order}', [PosController::class, 'orderStatus'])->name('pos.order-status');
    Route::get('/receipt/{order}', [PosController::class, 'receipt'])->name('pos.receipt');
    Route::get('/notifications', [PosController::class, 'notifications'])->name('pos.notifications');
    Route::get('/orders', [PosController::class, 'orders'])->name('pos.orders');
    
    // API: cek status order (untuk polling JS)
    Route::get('/api/order-status/{order}', [PosController::class, 'checkOrderStatus'])->name('pos.order-status.check');
    
    // ---- KASIR & ADMIN PANEL ----
    Route::middleware(['role:admin,kasir'])->group(function () {
        // Barista/Kasir orders panel
        Route::get('/kasir/orders', [PosController::class, 'adminOrders'])->name('pos.kasir.orders');
        Route::post('/kasir/order/{order}/status', [PosController::class, 'updateOrderStatus'])->name('pos.kasir.update-status');
    });
    
    // ---- ADMIN ONLY PANEL ----
    Route::middleware(['role:admin'])->group(function () {
        // Admin dashboard
        Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        
        // CRUD Menu
        Route::get('/admin/menu', [AdminController::class, 'menuIndex'])->name('admin.menu.index');
        Route::get('/admin/menu/create', [AdminController::class, 'menuCreate'])->name('admin.menu.create');
        Route::post('/admin/menu', [AdminController::class, 'menuStore'])->name('admin.menu.store');
        Route::get('/admin/menu/{menu}/edit', [AdminController::class, 'menuEdit'])->name('admin.menu.edit');
        Route::put('/admin/menu/{menu}', [AdminController::class, 'menuUpdate'])->name('admin.menu.update');
        Route::delete('/admin/menu/{menu}', [AdminController::class, 'menuDestroy'])->name('admin.menu.destroy');
        
        // CRUD Category
        Route::get('/admin/category', [AdminController::class, 'categoryIndex'])->name('admin.category.index');
        Route::post('/admin/category', [AdminController::class, 'categoryStore'])->name('admin.category.store');
        Route::put('/admin/category/{category}', [AdminController::class, 'categoryUpdate'])->name('admin.category.update');
        Route::delete('/admin/category/{category}', [AdminController::class, 'categoryDestroy'])->name('admin.category.destroy');
        
        // CRUD Kasir (User)
        Route::get('/admin/kasir', [AdminController::class, 'kasirIndex'])->name('admin.kasir.index');
        Route::post('/admin/kasir', [AdminController::class, 'kasirStore'])->name('admin.kasir.store');
        Route::put('/admin/kasir/{user}', [AdminController::class, 'kasirUpdate'])->name('admin.kasir.update');
        Route::delete('/admin/kasir/{user}', [AdminController::class, 'kasirDestroy'])->name('admin.kasir.destroy');
        
        // Laporan Penjualan
        Route::get('/admin/report', [AdminController::class, 'report'])->name('admin.report');
    });
});
