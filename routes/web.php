<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\PasteController;
use Illuminate\Support\Facades\Route;


Route::get('/', [PasteController::class, 'index']);
Route::get('/paste/{hash}', [PasteController::class, 'show']);
Route::get('/create', [PasteController::class, 'create']);
Route::post('/paste', [PasteController::class, 'store']);

// Breeze routes
require __DIR__.'/auth.php';
