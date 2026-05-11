<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\ProductController;
use App\Http\Controllers\User\CategoryController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\Vendor\ProductController as VendorProductController;
use App\Http\Controllers\Vendor\CategoryController as VendorCategoryController;

// 一般ユーザー用認証
require __DIR__ . '/user/auth.php';

// 業者用認証
require __DIR__ . '/vendor/vendor_auth.php';

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
    Route::resource('categories', VendorCategoryController::class);

    Route::resource('products', VendorProductController::class);
});