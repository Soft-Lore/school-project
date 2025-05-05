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

        // Private test endpoint
        Route::get('/private-check', function (Request $request) {
            return response()->json([
                'message' => 'Ruta protegida funcionando',
                'user' => $request->user(),
            ]);
        });
    });

    Route::middleware('auth:sanctum')->prefix('users')->group(function () {
        Route::post('register', [UserController::class, 'register']);
        Route::put('/update', [UserController::class, 'update']);
        Route::get('/', [UserController::class, 'index']);
    });
});
