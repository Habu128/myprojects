<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EnquiryController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/enquiries', [EnquiryController::class, 'index']);
    Route::post('/enquiries', [EnquiryController::class, 'store']);
    Route::get('/enquiries/{id}', [EnquiryController::class, 'show']);
    Route::put('/enquiries/{id}', [EnquiryController::class, 'update']);
    Route::delete('/enquiries/{id}', [EnquiryController::class, 'destroy']);
});
