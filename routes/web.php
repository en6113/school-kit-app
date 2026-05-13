<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\Vendor\CategoryController;

// 一般ユーザー用認証
require __DIR__ . '/user/auth.php';

// 業者用認証
require __DIR__ . '/vendor/vendor_auth.php';

/* --- 一般ユーザーと業者共有（要ログイン） --- */
Route::middleware(['auth:web,vendor', 'verified'])->group(function () {
    //商品関連（一覧と詳細のみ）
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');
});

/* --- 一般ユーザー専用（要ログイン） --- */
Route::middleware(['auth:web', 'verified'])->group(function () {
    // カート関連
    Route::resource('cart',CartController::class)->only(['index','store','destroy'])
        ->names([
            'store' => 'cart.add',
            'destroy' => 'cart.remove',
        ])
        ->parameters([
            'cart' => 'cartDetail', // ルートモデルバインディングのパラメータ名を変更
        ]);

    //注文関連
    Route::patch('/orders/{id}', [OrderController::class, 'cancel'])->name('order.cancel');
    Route::resource('orders', OrderController::class)->only(['index', 'create', 'store', 'show']);
});

/* --- 業者専用（要ログイン） --- */
Route::middleware('auth:vendor')->prefix('vendor')->name('vendor.')->group(function () {
    Route::resource('categories', CategoryController::class);

    Route::resource('products', ProductController::class)->except(['index','show']);
});