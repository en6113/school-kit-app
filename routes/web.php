<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SchoolKitController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CategoryController;

// 一般ユーザー用認証
require __DIR__ . '/user/auth.php';

// 業者用認証
require __DIR__ . '/vendor/vendor_auth.php';

/* 共有ルート(管理者、一般ユーザー、業者)*/
Route::middleware(['auth:web,vendor', 'verified'])->group(function () {
    //商品関連（一覧と詳細のみ）
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');

    //スターターキット関連（一覧と詳細のみ）
    Route::get('schoolKits',[SchoolKitController::class, 'index'])->name('schoolKits.index');
    Route::get('schoolKits/{schoolKit}', [SchoolkitController::class, 'show'])->name('schoolKits.show');
});

/* 管理者用ルート*/
Route::middleware(['auth', 'can:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('schoolKits', SchoolKitController::class)->except('index', 'show');
});

/* 一般ユーザー専用ルート */
Route::middleware(['auth:web', 'verified'])->group(function () {
    // カート関連
    Route::post('cart/add-kit', [CartController::class, 'storeKit'])->name('cart.add_kit');
    Route::resource('cart',CartController::class)->only(['index', 'store', 'destroy'])
        ->names([
            'store' => 'cart.add',
        ])
        // ルートモデルバインディングのパラメータ名を変更
        ->parameters([
            'cart' => 'cartDetail',
        ]);

    //注文関連
    Route::patch('/orders/{id}', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::resource('orders', OrderController::class)->only(['index', 'create', 'store', 'show']);
});

/* 業者専用ルート */
Route::middleware('auth:vendor')->prefix('vendor')->name('vendor.')->group(function () {
    Route::resource('products', ProductController::class)->except(['index','show']);
});