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

// Маршруты для аутентификации через GitHub
Route::prefix('auth')->group(function () {
    Route::get('/redirect', [UserController::class, 'create_git'])->name('github.login_git');
    Route::get('/github/callback', [UserController::class, 'callback_git'])->name('github.callback');
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
