<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\PasteController;
use Illuminate\Support\Facades\Route;


Route::get('/paste', [PasteController::class, 'index'])->name('paste.index');
Route::get('/paste/create', [PasteController::class, 'create'])->name('paste.create');
Route::get('/paste/{hash}', [PasteController::class, 'show'])->name('paste.show');

Route::post('/paste', [PasteController::class, 'store'])->name('paste.store');

Route::get('/paste/user_pastes', [PasteController::class, 'user_pastes'])->name('paste.user_pastes');
// Breeze routes
require __DIR__.'/auth.php';
