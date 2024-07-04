<?php

use App\Http\Controllers\Web\ComplaintController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\PasteController;
use Illuminate\Support\Facades\Route;


// Маршруты для паст
Route::prefix('paste')->group(function () {
    Route::get('/', [PasteController::class, 'index'])->name('paste.index');
    Route::get('/create', [PasteController::class, 'create'])->name('paste.create');
    Route::get('/{hash}', [PasteController::class, 'show'])->name('paste.show');
    Route::post('/', [PasteController::class, 'store'])->name('paste.store');
});

// Маршруты для аутентификации через Socialite
Route::prefix('auth')->group(function () {
    Route::get('/redirect/{driver}', [UserController::class, 'create_socia'])->name('socia.login');
    Route::get('/{driver}/callback', [UserController::class, 'callback_socia'])->name('socia.callback');
});

// Маршруты для профиля пользователя с Middleware auth
Route::middleware('auth')->group(function () {
    Route::get('/user/profile', [UserController::class, 'profile'])->name('user.profile');
});

// Маршруты для жалоб
Route::prefix('complaints')->group(function () {
    Route::post('/', [ComplaintController::class, 'store'])->name('complaints.store');
    Route::delete('/{complaint}', [ComplaintController::class, 'destroy'])->name('complaints.destroy');
});


// Breeze routes
require __DIR__.'/auth.php';
