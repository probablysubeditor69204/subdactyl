<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/setup', [\App\Http\Controllers\SetupController::class, 'setup']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'me']);

    // Server management
    Route::get('/servers', [ServerController::class, 'index']);
    Route::post('/servers', [ServerController::class, 'store']);

    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::post('/sync', [AdminController::class, 'syncResources']);
        Route::post('/settings', [AdminController::class, 'updateSettings']);
    });
});

// Debug catch-all
Route::any('/{any}', function (Request $request) {
    return response()->json([
        'message' => 'Route not found (Debug)',
        'path' => $request->path(),
        'url' => $request->url(),
        'method' => $request->method()
    ], 404);
})->where('any', '.*');
