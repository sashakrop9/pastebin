<?php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PasteController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// Маршруты для аутентификации
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/paste', [PasteController::class, 'index']);
    Route::get('/paste/{hash}', [PasteController::class, 'show']);
    Route::post('/paste', [PasteController::class, 'store']);
    Route::put('/paste/{hash}', [PasteController::class, 'update']);
    Route::delete('/paste/{hash}', [PasteController::class, 'destroy']);

    Route::get('/user', [UserController::class, 'profile']);
    Route::get('/users', [UserController::class, 'users']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
