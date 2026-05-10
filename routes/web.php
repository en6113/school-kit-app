<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CategoryController;

// 一般ユーザー用認証
require __DIR__ . '/auth.php';

// 業者用認証
require __DIR__ . '/vendor_auth.php';

/* --- 一般ユーザー専用（要ログイン） --- */
Route::middleware(['auth', 'verified'])->group(function () {
    //商品関連（ユーザーは一覧と詳細のみアクセス可能）
    Route::resource('products', ProductController::class)->only(['index', 'show']);

    //カテゴリ関連（ユーザーは一覧と詳細のみアクセス可能）
    Route::resource('categories', CategoryController::class)->only(['index', 'show']);

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

    Route::resource('products', ProductController::class);
});