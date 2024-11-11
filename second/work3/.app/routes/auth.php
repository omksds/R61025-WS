<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

// 認証ルートの定義
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// ... 他の認証関連のルートを追加 ... 