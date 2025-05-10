<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;

Route::prefix('v1')->group(function () {
/*
|--------------------------------------------------------------------------
| Routes Publics (unauthenticated)
|--------------------------------------------------------------------------
*/

    Route::get('/ping', function () {
        return response()->json(['message' => 'API funcionando correctamente ✅']);
    });

    Route::post('/login', [AuthController::class, 'login'])->name('login');

/*
|--------------------------------------------------------------------------
| Protected routes (Sanctum token required)
|--------------------------------------------------------------------------
*/

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/register', [UserController::class, 'register']);
        Route::put('/update', [UserController::class, 'update']);
        Route::delete('/delete', [UserController::class, 'delete']);
        Route::post('/change-password', [UserController::class, 'changePassword']);
    });
});
