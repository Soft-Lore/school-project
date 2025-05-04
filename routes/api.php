<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;

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
 
});