<?php

use App\Http\Controllers\ChannelController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/', [ChannelController::class, 'index'])->name('channels.index');
    Route::post('/channels', [ChannelController::class, 'store'])->name('channels.store');
    Route::get('/channels/{channel}', [ChannelController::class, 'show'])->name('channels.show');
    Route::post('/channels/{channel}/messages', [MessageController::class, 'store'])->name('messages.store');
});

require __DIR__.'/auth.php';
