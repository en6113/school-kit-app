<?php

use App\Http\Controllers\Auth\Vendor\AuthenticatedSessionController as VendorAuthenticatedSessionController;
use App\Http\Controllers\Auth\Vendor\RegisteredUserController as VendorRegisteredUserController;
use Illuminate\Support\Facades\Route;

// 業者：ゲスト（未ログイン）専用
Route::middleware('guest:vendor')->prefix('vendor')->name('vendor.')->group(function () {
    Route::get('register', [VendorRegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [VendorRegisteredUserController::class, 'store']);

    Route::get('login', [VendorAuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [VendorAuthenticatedSessionController::class, 'store']);
});

// 業者：認証済み専用
Route::middleware('auth:vendor')->prefix('vendor')->name('vendor.')->group(function () {
    Route::post('logout', [VendorAuthenticatedSessionController::class, 'destroy'])->name('logout');
});
