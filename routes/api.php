<?php

use App\Http\Controllers\Api\{
    UserController,
    ResourceController,
    PermissionUserController
};
use App\Http\Controllers\Api\Auth\{
    RegisterController,
    AuthController
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function() {
    return response()->json(['message' => 'ok']);
});

Route::post('/register', [RegisterController::class, 'store']);
Route::post('/auth', [AuthController::class, 'auth']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function() {
    Route::get('/users/{uuid}/permissions', [PermissionUserController::class, 'permissionsUser']);
    Route::post('/users/permissions', [PermissionUserController::class, 'addPermissionsUser']);
    Route::get('/users/can/{permission}', [PermissionUserController::class, 'userHasPermission']);

    Route::apiResource('/users', UserController::class);    
    
    Route::get('/resources', [ResourceController::class, 'index']);
});
