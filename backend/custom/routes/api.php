<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TravelRequestController;

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function() {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::get('/travel-requests', [TravelRequestController::class, 'index']);
    Route::get('/travel-requests/{travelRequest}', [TravelRequestController::class, 'show']);
    Route::post('/travel-requests', [TravelRequestController::class, 'store']);
    Route::patch('/travel-requests/{travelRequest}/status', [TravelRequestController::class, 'updateStatus']);
});
