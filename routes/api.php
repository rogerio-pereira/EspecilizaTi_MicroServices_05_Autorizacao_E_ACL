<?php

use App\Http\Controllers\Api\{
    UserController
};
use App\Http\Controllers\Api\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function() {
    return response()->json(['message' => 'ok']);
});

Route::apiResource('/users', UserController::class);
Route::post('/register', [RegisterController::class, 'store']);