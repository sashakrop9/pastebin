<?php

use App\Http\Controllers\Web\ComplaintController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\PasteController;
use Illuminate\Support\Facades\Route;


Route::get('/paste', [PasteController::class, 'index'])->name('paste.index');
Route::get('/paste/create', [PasteController::class, 'create'])->name('paste.create');
Route::get('/paste/{hash}', [PasteController::class, 'show'])->name('paste.show');

Route::post('/paste', [PasteController::class, 'store'])->name('paste.store');

Route::middleware('auth')->group(function () {
    Route::get('/user/profile', [UserController::class, 'profile'])->name('user.profile');
});

Route::post('complaints', [ComplaintController::class, 'store'])->name('complaints.store');
Route::delete('complaints/{complaint}', [ComplaintController::class, 'destroy'])->name('complaints.destroy');

// Breeze routes
require __DIR__.'/auth.php';
